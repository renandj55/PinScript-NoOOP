<?php
session_start();


$db = new PDO('mysql:host=localhost;dbname=pinscript', 'root', '');

$main_directory = dirname(__DIR__);

$xml_file = simplexml_load_file($main_directory . '/settings.xml');

$website_title = $xml_file->website_title;
$code_count = $xml_file->code_count;
$btc_allow = $xml_file->btc_allow;
$pp_allow = $xml_file->pp_allow;

$paypalConfig = array(
	
);

function getProducts() {
	global $db;
	$stmt = $db->prepare("SELECT * FROM `product`");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCodeCount($product_id) {
	global $db;
	$stmt = $db->prepare("SELECT COUNT(`code_id`) FROM `code` WHERE `product_id` = :id");
	$stmt->execute(array(':id' => $product_id));
	return $stmt->fetch(PDO::FETCH_COLUMN);
}

function getItemPrice($product_id) {
	global $db;
	$stmt = $db->prepare("SELECT `product_price` FROM `product` WHERE `product_id` = :id");
	$stmt->execute(array(':id' => $product_id));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}