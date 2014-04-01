<?php
require('inc/config.inc.php');
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
require_once('vendor/PPBootStrap.php');

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

$returnUrl = $url . '/confirm.php';
$cancelUrl = $url . '/index.php';

//Create an item details to send to payment details
$itemDetails = new PaymentDetailsItemType();

//Set the item name
$itemDetails->Name = $_POST['itemName'];

//Set the item price
$itemPrice = new BasicAmountType();
$itemPrice->currencyID = 'USD';
$itemPrice->value = getItemPrice($_POST['itemId']);

//Set the item tax
$taxTotal = new BasicAmountType();
$taxTotal->currencyID = 'USD';
$taxTotal->value = '0.0';

$itemDetails->Amount = $itemPrice;

//Set the item quantity (will change later)
$itemDetails->Quantity = '1';

//Set the item category (will always be digital)
$itemDetails->ItemCategory = 'Digital';

//Create a payment details to send to PayPal
$paymentDetails = new PaymentDetailsType();

//Set the first item equivalency to previously set item details
$paymentDetails->PaymentDetailsItem[0] = $itemDetails;

//Set the payment type (will always be Sale)
$paymentDetails->PaymentAction = 'Sale';

//Set the order total, item total and tax total
$paymentDetails->OrderTotal = $itemPrice;
$paymentDetails->ItemTotal = $itemPrice;
$paymentDetails->TaxTotal = $taxTotal;

//Crete the EC request details type
$ECRDetails = new SetExpressCheckoutRequestDetailsType();

//Set the payment details to EC
$ECRDetails->PaymentDetails[0] = $paymentDetails;
$ECRDetails->ReturnURL = $returnUrl;
$ECRDetails->CancelURL = $cancelUrl;

//Set the shipping address requirement (0 = no address needed, 1 = address needed)
$ECRDetails->ReqConfirmShipping = 0;

//Set the shipment details (0 = address fields, 1 = no address fields, 2 = get address fields from user profile)
$ECRDetails->NoShipping = 1;

//Create the EC request type to add details
$ECRType = new SetExpressCheckoutRequestType();
$ECRType->SetExpressCheckoutRequestDetails = $ECRDetails;

//Create the EC request to add the previous type
$ECR = new SetExpressCheckoutReq();
$ECR->SetExpressCheckoutRequest = $ECRType;

//Create the PayPal Service using $paypalConfig from config.inc.php
$paypalService = new PayPalAPIInterfaceServiceService(Configuration::getAcctAndConfig());

//Make the API call using Paypal Service
$setECResponse = $paypalService->setExpressCheckout($ECR);

//Check to see if API call was successful
if($setECResponse->Ack = "Success") {

	//Set the token
	$token = urldecode($setECResponse->Token);

	//Redirect user to PayPal to complete transaction
	$paypalRedirect = 'https://www.sandbox.paypal.com/incontext?token=' . $token;
	header('Location: ' . $paypalRedirect);

} else {
	echo 'Something strange went wrong.';
}