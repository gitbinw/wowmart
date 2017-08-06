<?php
/********************************************
	Module contains calls to PayPal APIs
	********************************************/

	include('paypalConfig.php');

/*
	* Purpose: 	Gets the access token from PayPal
	* Inputs:
	* Returns:  access token
	*
	*/
function getAccessToken(){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v1/oauth2/token";
	$clientId = (SANDBOX_FLAG ? SANDBOX_CLIENT_ID : LIVE_CLIENT_ID);
	$clientSecret = (SANDBOX_FLAG ? SANDBOX_CLIENT_SECRET : LIVE_CLIENT_SECRET);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $curlServiceUrl);

	$method = 'POST';
	$postvals = "grant_type=client_credentials";
	$headers = array("Accept: application/json", "Accept-Language: en_US");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

	$options = array(
		CURLOPT_HEADER => true,
		CURLINFO_HEADER_OUT => true,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_VERBOSE => true,
		CURLOPT_TIMEOUT => 10
	);
		
	if ($method == 'POST'){
		$options[CURLOPT_POSTFIELDS] = $postvals;
		$options[CURLOPT_CUSTOMREQUEST] = $method;
	}

	curl_setopt_array($ch, $options);

	$response = curl_exec($ch);
	$err = curl_error($ch);

	$access_token="";
	
	if ($err) {
  		//echo "cURL Error #:" . $err;
	} else {
		$header = substr($response, 0, curl_getinfo($ch,CURLINFO_HEADER_SIZE));
    	$result = json_decode(substr($response, curl_getinfo($ch,CURLINFO_HEADER_SIZE)));
		
    	$access_token = $result->access_token;

		//echo $access_token;
	}

	return $access_token;
}


/*
	* Purpose: 	Gets the PayPal approval URL to redirect the user to.
	* Inputs:
	*		access_token    : The access token received from PayPal
	* Returns:              approval URL
	*/
function getApprovalURL($access_token, $postData){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v1/payments/payment";
	$curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$access_token);//, "PayPal-Partner-Attribution-Id:".SBN_CODE);

	$curlResponse = curlCall($curlServiceUrl, $curlHeader, $postData);
	$jsonResponse = $curlResponse['json'];
	return $jsonResponse;
	/*
	foreach ($jsonResponse['links'] as $link) {
	//foreach ($curlResponse['links'] as $link) {
		if($link['rel'] == 'approval_url'){
			$approval_url = $link['href'];
			echo($approval_url);
			return $approval_url;
		}
	 }*/

}

/*
	* Purpose: 	Look up a payment resource, to get details about payments that have not yet been completed
	* Inputs:
	*		paymentID    : The id of the created payment
	* Returns:              the payment object
	*/
function lookUpPaymentDetails($paymentID, $access_token){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v1/payments/payment/". $paymentID;
	$curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$access_token, "PayPal-Partner-Attribution-Id:".SBN_CODE);

	$curlResponse = curlCall($curlServiceUrl, $curlHeader, NULL);
	return $curlResponse['json'];

}


/*
	* Purpose: 	Executes the previously created payment for a given paymentID for a specific user's payer id.
	* Inputs:
	*		paymentID    : The id of the previously created PayPal payment
	*       payerID      : The id of the user received from PayPal
	*       transactionAmountArray   : amount array if updating the payment amount
	* Returns:
	*		array["http_code"]   : the http status code   
	*		array["jason"]       : the response string
	*/
function doPayment($paymentID, $payerID, $transactionAmountArray){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
    $curlServiceUrl = $curlServiceUrl. "/v1/payments/payment/". $paymentID ."/execute";
    $curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$_SESSION['access_token'], "PayPal-Partner-Attribution-Id:".SBN_CODE);

	$postData = array(
                    "payer_id" => $payerID
                    );

    if(!is_null($transactionAmountArray)){
    	$postData ["transactions"][0] = $transactionAmountArray;
    }

    $curlPostData = json_encode($postData);
    $curlResponse = curlCall($curlServiceUrl, $curlHeader, $curlPostData);
    return $curlResponse;
}

?>