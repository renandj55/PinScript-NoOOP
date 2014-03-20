<?php

require('inc/config.inc.php');

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<title><?php echo $website_title; ?></title>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
</head>

<body>

<div id="headerContainer">
	<div id="header">
		<h1><?php echo $website_title; ?></h1>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="admin.php">Admin</a></li>
		</ul>
	</div>
</div>

<div id="contentContainer">
	<main>