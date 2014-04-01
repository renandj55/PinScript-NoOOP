<?php

require('inc/config.inc.php');

$transaction_hash = $_GET['transaction_hash'];
$input_transaction_hash = $_GET['input_transaction_hash'];
$input_address = $_GET['input_address'];
$satoshi = $_GET['value'];
$btc_value = $satoshi / 100000000;

if($_GET['test'] == true) {
	$stmt = $db->prepare("SELECT pending_btc.btc_email, pending_btc.item_id, code.code_content FROM `pending_btc`
		JOIN `code`
		ON pending_btc.item_id = code.product_id
		WHERE `btc_amount` = :btcVal AND `btc_address` = :address");
	$stmt->execute(array(':btcVal' => $btc_value, ':address' => $input_address));
	$data = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($data)) {
		mail($data['btc_email'], 
			'You have received a code from ' . $website_title, 
			'You have received a code: ' . $data['code_content'], 
			"From: $website_title <automated@tenquota.com> \r\n"
		);

		$stmr = $db->prepare("INSERT INTO `btc_purchases` VALUES('', :itemId, :code, :email, UNIX_TIMESTAMP())");
		$stmr->execute(array(
			':itemId' => $data['item_id'], 
			':code' => $data['code_content'], 
			':email' => $data['btc_email'])
		);

		$del = $db->prepare("DELETE FROM `pending_btc` WHERE `btc_amount` = :btcVal AND `btc_address` = :address");
		$del->execute(array(':btcVal' => $btc_value, ':address' => $input_address));
	}
}