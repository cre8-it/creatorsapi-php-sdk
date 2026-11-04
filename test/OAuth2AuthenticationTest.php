<?php

namespace Amazon\CreatorsAPI\v1\Test;

use Amazon\CreatorsAPI\v1\com\amazon\creators\auth\OAuth2Config;
use Amazon\CreatorsAPI\v1\com\amazon\creators\auth\OAuth2TokenManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class OAuth2AuthenticationTest extends TestCase
{
    public static function credentialVersions(): array
    {
        return [
            'Cognito NA' => ['2.1', 'https://creatorsapi.auth.us-east-1.amazoncognito.com/oauth2/token', false],
            'Cognito EU' => ['2.2', 'https://creatorsapi.auth.eu-south-2.amazoncognito.com/oauth2/token', false],
            'Cognito FE' => ['2.3', 'https://creatorsapi.auth.us-west-2.amazoncognito.com/oauth2/token', false],
            'LWA NA' => ['3.1', 'https://api.amazon.com/auth/o2/token', true],
            'LWA EU' => ['3.2', 'https://api.amazon.co.uk/auth/o2/token', true],
            'LWA FE' => ['3.3', 'https://api.amazon.co.jp/auth/o2/token', true],
        ];
    }

    /** @dataProvider credentialVersions */
    public function test_authentication_uses_the_correct_endpoint_scope_and_encoding(string $version, string $endpoint, bool $lwa): void
    {
        $config = new OAuth2Config('example-id', 'example-secret', $version, null);
        $this->assertSame($endpoint, $config->getTokenEndpoint());
        $this->assertSame($lwa, $config->isLwa());

        $history = [];
        $manager = $this->tokenManager($config, $history, [
            new Response(200, [], '{"access_token":"example-token","expires_in":3600}'),
        ]);

        $this->assertSame('example-token', $manager->getToken());
        $this->assertTrue($manager->isTokenValid());
        $this->assertSame('example-token', $manager->getToken());
        $this->assertCount(1, $history, 'A valid token should be reused.');

        $request = $history[0]['request'];
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($endpoint, (string) $request->getUri());
        $this->assertSame(
            $lwa ? 'application/json' : 'application/x-www-form-urlencoded',
            $request->getHeaderLine('Content-Type'),
        );

        if ($lwa) {
            $payload = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } else {
            parse_str((string) $request->getBody(), $payload);
        }

        $this->assertSame([
            'grant_type' => 'client_credentials',
            'client_id' => 'example-id',
            'client_secret' => 'example-secret',
            'scope' => $lwa ? 'creatorsapi::default' : 'creatorsapi/default',
        ], $payload);
    }

    public function test_custom_authentication_endpoints_are_preserved(): void
    {
        $config = new OAuth2Config('example-id', 'example-secret', '3.2', 'https://auth.example.test/token');

        $this->assertSame('https://auth.example.test/token', $config->getTokenEndpoint());
        $this->assertSame('creatorsapi::default', $config->getScope());
    }

    public function test_unknown_credential_versions_are_rejected(): void
    {
        $config = new OAuth2Config('example-id', 'example-secret', '4.2', null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported version: 4.2');

        $config->getTokenEndpoint();
    }

    public function test_clearing_a_token_fetches_a_new_one(): void
    {
        $history = [];
        $manager = $this->tokenManager(new OAuth2Config('example-id', 'example-secret', '3.2', null), $history, [
            new Response(200, [], '{"access_token":"first-token","expires_in":3600}'),
            new Response(200, [], '{"access_token":"second-token","expires_in":3600}'),
        ]);

        $this->assertSame('first-token', $manager->getToken());
        $manager->clearToken();
        $this->assertFalse($manager->isTokenValid());
        $this->assertSame('second-token', $manager->getToken());
        $this->assertCount(2, $history);
    }

    public function test_a_response_without_an_access_token_is_rejected(): void
    {
        $history = [];
        $manager = $this->tokenManager(new OAuth2Config('example-id', 'example-secret', '3.2', null), $history, [
            new Response(200, [], '{}'),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No access token received');

        $manager->getToken();
    }

    private function tokenManager(OAuth2Config $config, array &$history, array $responses): OAuth2TokenManager
    {
        $handler = HandlerStack::create(new MockHandler($responses));
        $handler->push(Middleware::history($history));
        $manager = new OAuth2TokenManager($config);

        // Upstream creates its own auth client; replace it only in tests so all
        // authentication requests are intercepted without changing SDK sources.
        $client = new ReflectionProperty(OAuth2TokenManager::class, 'httpClient');
        $client->setValue($manager, new Client(['handler' => $handler]));

        return $manager;
    }
}
