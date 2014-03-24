<?php

require('inc/config.inc.php');

if(!empty($_POST['code'])) {
	$stmt = $db->prepare("DELETE FROM `code` WHERE `product_id` = :id AND `code_content` = :code");
	$stmt->execute(array(':id' => $_POST['product'], ':code' => $_POST['code']));
	$_SESSION['admin']['message'] = $stmt->rowCount() . ' code(s) were removed.';
} else {
	$stmt = $db->prepare("DELETE FROM `product` WHERE `product_id` = :id");
	$stmt->execute(array(':id' => $_POST['product']));

	$stmz = $db->prepare("DELETE FROM `code` WHERE `product_id` = :id");
	$stmz->execute(array(':id' => $_POST['product']));

	$_SESSION['admin']['message'] = $stmt->rowCount() . ' product was removed and ' . $stmz->rowCount() . ' code(s) were removed.';
}

header('Location: admin.php');