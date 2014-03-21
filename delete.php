<?php

require('inc/config.inc.php');

if(!empty($_POST['codes'])) {
	foreach($_POST['codes'] as $code) {
		$stmt = $db->prepare("DELETE FROM `code` WHERE `code_id` = :id");
		$stmt->execute(array(':id' => $code));
	}
}

header('admin.php');

?>