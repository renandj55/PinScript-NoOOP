<?php

require('inc/config.inc.php');

if(!empty($_POST['product'])) {
	$stmt = $db->prepare("SELECT code.*, product.product_name FROM `code` 
		INNER JOIN `product` 
		ON code.product_id = product.product_id 
		WHERE code.product_id = :id 
		ORDER BY code.code_timestamp");
	$stmt->execute(array(':id' => $_POST['product']));
	$_SESSION['codes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Location: admin.php');

?>