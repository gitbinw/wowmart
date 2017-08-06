<?php
/************
  * Use these settings to set defaults for the Paypal Helper class.
  * The PaypalHelper class will help you create paynow, subscribe, donate, or addtocart buttons for you.
  * 
  * All these options can be set on the fly as well within the helper
  */
  
class PaypalIpnConfig {

  /************
    * Each settings key coresponds to the Paypal API.  Review www.paypal.com for more. 
    */
  var $settings = array(
    'business' => 'marco@freshla.com.au', //'live_email@paypal.com', //Your Paypal email account
    'server' => 'https://www.paypal.com', //Main paypal server.
    'notify_url' => 'http://www.freshla.com.au/paypal_ipn/process', //'http://www.yoursite.com/paypal_ipn/process', //Notify_url... set this to the process path of your paypal_ipn::instant_payment_notification::process action
    'cancel_return' => 'http://www.freshla.com.au/checkout/payment',
    'image_url' => 'http://www.freshla.com.au/img/home/logo_small.gif',
    'currency_code' => 'AUD', //Currency
    'lc' => 'AU', //Locality
    'item_name' => 'Paypal_IPN', //Default item name.
    'amount' => '15.00' //Default item amount.
  );
  
  /***********
    * Test settings to test with using a sandbox paypal account.
    */
  var $testSettings = array(
    'business' => 'job.bi_1295487418_biz@gmail.com', //'sandbox_email@paypal.com',
    'server' => 'https://www.sandbox.paypal.com',
    'notify_url' => 'http://www.freshla.com.au/paypal_ipn/process', //'http://www.yoursite.com/paypal_ipn/process',
  	'cancel_return' => 'http://www.freshla.com.au/checkout/payment',
  	'image_url' => 'http://www.freshla.com.au/img/home/logo.gif',
    'currency_code' => 'AUD',
    'lc' => 'AU',
    'item_name' => 'Paypal_IPN',
    'amount' => '15.00'
  );

}
?>
