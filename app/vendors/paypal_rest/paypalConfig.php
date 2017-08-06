<?php
    /*
        * Config for PayPal specific values
    */

    //Whether Sandbox environment is being used, Keep it true for testing
    define("SANDBOX_FLAG", true);

    //PayPal REST API endpoints
    define("SANDBOX_ENDPOINT", "https://api.sandbox.paypal.com");
    define("LIVE_ENDPOINT", "https://api.paypal.com");

    //Merchant ID
    define("MERCHANT_ID","E9GCL5FX4TU2C");

    //PayPal REST App SANDBOX Client Id and Client Secret
    define("SANDBOX_CLIENT_ID" , "ASEg-oxQONgTpyuzC_X4HHo-ZeCPWrAahGZuxgrYMrNVtlEbIcFtBcVPiDkhshoy1-FBFIRq01bnXR7h");
    define("SANDBOX_CLIENT_SECRET", "EMar2122M75ArvUJfu4MAvI0zjkAQSdLRiQNUw_55VlSwgnjSKQuwElkdP7zwHfZj56th_LGnGCEItS1");

    //Environments -Sandbox and Production/Live
    define("SANDBOX_ENV", "sandbox");
    define("LIVE_ENV", "production");

    //PayPal REST App SANDBOX Client Id and Client Secret
    define("LIVE_CLIENT_ID" , "live_Client_Id");
    define("LIVE_CLIENT_SECRET" , "live_Client_Secret");

    //ButtonSource Tracker Code
    define("SBN_CODE","PP-DemoPortal-EC-IC-php-REST");

    if(SANDBOX_FLAG) {
        $environment = SANDBOX_ENV;
    } else {
        $environment = LIVE_ENV;
    }

?>