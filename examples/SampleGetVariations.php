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

// Run `composer install` locally before executing the following code with `php SampleGetVariations.php`

/**
 * Sample script demonstrating how to use the CreatorsAPI PHP SDK for GetVariations API
 * GetVariations operation retrieves variations of a product
 * along with detailed information including images, item info, offers, and other product data.
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use Amazon\CreatorsAPI\v1\Configuration;
use Amazon\CreatorsAPI\v1\com\amazon\creators\api\DefaultApi;
use Amazon\CreatorsAPI\v1\com\amazon\creators\model\GetVariationsRequestContent;
use Amazon\CreatorsAPI\v1\com\amazon\creators\model\GetVariationsResource;
use Amazon\CreatorsAPI\v1\ApiException;

/**
 * Sample function to demonstrate GetVariations API usage
 */
function getVariations()
{
    // Create configuration with OAuth2 credentials
    $config = new Configuration();
    
    // Specify your credentials here
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
        
        /** Please add your partner tag (store/tracking id) here */
        $partnerTag = '<YOUR PARTNER TAG>';
        
        /** Specify ASIN for which to retrieve variations */
        $asin = 'B0DLFMFBJW';
        
        /**
         * Choose resources you want from GetVariationsResource enum
         * For more details, refer: https://affiliate-program.amazon.com/creatorsapi/docs/en-us/api-reference/operations/get-variations#resources-parameter
         */
        $resources = [
            GetVariationsResource::IMAGES_PRIMARY_MEDIUM,
            GetVariationsResource::ITEM_INFO_TITLE,
            GetVariationsResource::OFFERS_V2_LISTINGS_AVAILABILITY,
            GetVariationsResource::OFFERS_V2_LISTINGS_CONDITION,
            GetVariationsResource::OFFERS_V2_LISTINGS_DEAL_DETAILS,
            GetVariationsResource::OFFERS_V2_LISTINGS_IS_BUY_BOX_WINNER,
            GetVariationsResource::OFFERS_V2_LISTINGS_LOYALTY_POINTS,
            GetVariationsResource::OFFERS_V2_LISTINGS_MERCHANT_INFO,
            GetVariationsResource::OFFERS_V2_LISTINGS_PRICE,
            GetVariationsResource::OFFERS_V2_LISTINGS_TYPE,
            GetVariationsResource::VARIATION_SUMMARY_VARIATION_DIMENSION
        ];
        
        // Create GetVariations request
        $getVariationsRequest = new GetVariationsRequestContent();
        $getVariationsRequest->setPartnerTag($partnerTag);
        $getVariationsRequest->setAsin($asin);
        $getVariationsRequest->setResources($resources);
        
        // Call the GetVariations API
        $response = $apiInstance->getVariations($marketplace, $getVariationsRequest);
        
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
getVariations();
