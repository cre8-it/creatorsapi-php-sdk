# Creators API PHP SDK Example

This distribution's **2.0.0** release imports Amazon's **1.3.0** SDK. The Composer
release number differs from Amazon's upstream version because the update includes
breaking public API changes. Package versions are derived from Git tags.

## Prerequisites

### PHP Version Support
- **Supported**: To run the SDK you need PHP version 8.1 or higher.

## Setup Instructions

### 1. Install and Configure PHP

For PHP installation, you can download it from the official website: https://www.php.net/downloads

```bash
# Check PHP version
php --version
```

### 2. Install SDK (Composer)
```bash
composer require cre8-it/creatorsapi-php-sdk:^2.0
```
If you're running from a cloned repo, run `composer install` in the repo root instead.

### 3. Run Sample Code
Navigate to the examples directory to run the samples.

```bash
cd {path_to_project}/vendor/cre8-it/creatorsapi-php-sdk/examples
```

If you're running from a cloned repo, use `cd {path_to_project}/creatorsapi-php-sdk/examples`.

Before running the samples, you'll need to configure your API credentials in the sample files by replacing the following placeholders:

- `<YOUR CREDENTIAL ID>` - Your API credential ID
- `<YOUR CREDENTIAL SECRET>` - Your API credential secret  
- `<YOUR CREDENTIAL VERSION>` - Use the version issued with your credentials: "3.1" for NA, "3.2" for EU, or "3.3" for FE with Login with Amazon (LWA). The SDK also understands legacy Cognito versions "2.1", "2.2", and "2.3", but Amazon may no longer accept those credentials.
- `<YOUR MARKETPLACE>` - Your marketplace (e.g., "www.amazon.com" for US marketplace)
- `<YOUR PARTNER TAG>` - Add valid Partner Tag for the requested marketplace in applicable sample code snippet files

Run the following commands to run the sample files:

**Get detailed product information:**
```bash
php SampleGetItems.php
```

**Search for products:**
```bash
php SampleSearchItems.php
```

#### Other Samples
Check the `examples` directory for additional sample files with various API operations.

## Migrating to 2.0

- Generate new LWA credentials in Amazon's PartnerNet and update the credential ID,
  secret, and version together. Do not relabel legacy credentials as version 3.x.
- For EU credentials, version `3.2` automatically selects
  `https://api.amazon.co.uk/auth/o2/token`, including for `www.amazon.de`.
- Remove old custom Cognito authentication endpoint overrides. Endpoint, scope,
  and JSON/form encoding are selected from the credential version.
- `OAuth2Config::setScope()` was removed upstream; the scope is selected automatically.
- All `DefaultApi::searchItems*` methods now require the request argument, and
  `SearchItemsRequestContent` requires `partnerTag`.
- Feed and report models now include `FeedType` and `ReportType` fields.

## Tests

```bash
composer install
vendor/bin/phpunit
```

Authentication tests use mocked HTTP responses; no Amazon credentials or live
requests are needed.
