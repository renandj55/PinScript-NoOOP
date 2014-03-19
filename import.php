<?php

require('inc/config.inc.php');

$path = $_FILES['list']['tmp_name'];
$product_id = $_POST['product'];

$handle = fopen($path, "r");

if($handle) {
	while(($line = fgets($handle)) !== false) {
		echo $line;
		$stmt = $db->prepare("INSERT INTO code(`product_id`, `code_content`, `code_timestamp`) VALUES(:id, :content, UNIX_TIMESTAMP())");
		$stmt->execute(array(':id' => $product_id, ':content' => $line));
	}
}

$_SESSION['admin']['message'] = 'Successfully imported list.';

header('Location: admin.php');