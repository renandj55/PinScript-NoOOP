<?php

//debug mode - turned off by default
define("DEBUG", true);

//sandbox mode - live by default
define("SANDBOX", true);

//error and success logging
define("LOG_FILE", './ipn.log');

$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);

$post_data = array();

foreach($raw_post_array as $keyval) {
	$keyval = explode('=', $keyval);
	if(count($keyval) == 2) {
		$post_data[$keyval[0]] = urldecode($keyval[1]);
	}
}

$request = 'cmd=_notify-validate';

foreach($post_data as $key => $value) {
	$value = urlencode($value);
	$request .= "&$key=$value";
}

if(SANDBOX == true) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);

if($ch == false) {
	return false;
}

curl_setopt($ch, CURL_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURL_POST, 1);
curl_setopt($ch, CURL_RETURNTRANSFER, 1);
curl_setopt($ch, CURL_POSTFIELDS, $request);
curl_setopt($ch, SSL_VERIFYPEER, 1);
curl_setopt($ch, SSL_VERIFYHOST, 2);
curl_setopt($ch, CURL_FORBID_REUSE, 1);

if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

$response = curl_exec($ch);

if (curl_errno($ch) != 0) // cURL error
	{
	if(DEBUG == true) {	
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;

} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

			// Split response headers and payload
			list($headers, $res) = explode("\r\n\r\n", $res, 2);
		}
		curl_close($ch);
}

// Inspect IPN validation result and act accordingly

if (strcmp ($res, "VERIFIED") == 0) {
	// check whether the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment and mark item as paid.

	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
	}
} else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	// Add business logic here which deals with invalid IPN messages
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	}
}

