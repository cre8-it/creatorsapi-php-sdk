<?php
/**
 * Copyright 2025 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

// Run `composer install` locally before executing the following code with `php SampleListFeeds.php`

require_once(__DIR__ . '/../vendor/autoload.php');

use Amazon\CreatorsAPI\v1\Configuration;
use Amazon\CreatorsAPI\v1\com\amazon\creators\api\DefaultApi;
use Amazon\CreatorsAPI\v1\ApiException;

/**
 * Sample function to demonstrate ListFeeds API usage
 */
function listFeeds()
{
    // Create configuration with OAuth2 credentials
    $config = new Configuration();
    
    // Specify your credentials here. 
    // Please add your credential id here
    $config->setCredentialId("<YOUR CREDENTIAL ID>");
    
    // Please add your credential secret here
    $config->setCredentialSecret("<YOUR CREDENTIAL SECRET>");
    
    /**
     * Please add your credential version here
     * For eg-
     * - 2.1 for North America (NA) region
     * - 2.2 for Europe (EU) region 
     * - 2.3 for Far East (FE) region
     */
    $config->setVersion("<YOUR CREDENTIAL VERSION>");
    
    try {
        // Create API instance with OAuth2 configuration
        $apiInstance = new DefaultApi(null, $config);
        
        /**
         * Specify the marketplace to which you want to send the request
         * Eg- "www.amazon.com" for US marketplace
         * For more details, refer: https://affiliate-program.amazon.com/creatorsapi/docs/en-us/api-reference/common-request-headers-and-parameters#marketplace-locale-reference
         */
        $marketplace = "<YOUR MARKETPLACE>";
        
        // Call the ListFeeds API
        $response = $apiInstance->listFeeds($marketplace);
        
        echo 'API called successfully.' . PHP_EOL;
        echo "Complete Response: " . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
        
    } catch (ApiException $e) {
        echo 'Error calling Creators API!' . PHP_EOL;
        echo $e . PHP_EOL;
    } catch (Exception $e) {
        echo "Unexpected error: " . $e . PHP_EOL;
    }
}

// Run the sample
listFeeds();
