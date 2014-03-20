<?php
require('inc/config.inc.php');

$errors = [];

if(empty($_POST['name']) || empty($_POST['description'] || empty($_POST['price']))) {
	$errors[] = 'All fields must be filled.';
} else {

	if(strlen($_POST['name']) > 100) {
		$errors[] = 'Product name is too long.';
	}

}

if(!empty($errors)) {
	$_SESSION['add_product']['name'] = $_POST['name'];
	$_SESSION['add_product']['description'] = $_POST['description'];
	$_SESSION['add_product']['price'] = $_POST['price'];
	$_SESSION['add_product']['error'] = $errors[0];
	header('Location: admin.php');
} else {
	$name = $_POST['name'];
	$description = $_POST['description'];
	$price = $_POST['price'];
	unset($_SESSION['add_product']);
}

$stmt = $db->prepare("INSERT INTO product(`product_name`, `product_description`, `product_price`, `product_timestamp`) VALUES(:name, :description, :price, UNIX_TIMESTAMP())");
$stmt->execute(array(
		':name' => $name,
		':description' => $description,
		':price' => $price
	)
);

header('Location: index.php');