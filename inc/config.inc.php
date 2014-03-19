<?php
session_start();


$db = new PDO('mysql:host=localhost;dbname=pinscript', 'root', '');

$main_directory = dirname(__DIR__);

$xml_file = simplexml_load_file($main_directory . '/settings.xml');

$website_title = $xml_file->website_title;

function getProducts() {
	global $db;
	$stmt = $db->prepare("SELECT * FROM `product`");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}