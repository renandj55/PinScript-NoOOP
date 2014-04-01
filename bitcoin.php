<?php
require('header.php');

if(isset($_POST['itemPrice'])) {
	$_SESSION['btc']['itemPrice'] = $_POST['itemPrice'];
	$_SESSION['btc']['itemName'] = $_POST['itemName'];
	$_SESSION['btc']['itemId'] = $_POST['itemId'];
}

if(isset($_POST['confirmPurchase'])) {

	$errors = [];

	if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors[] = 'E-Mail must be valid.';
	}
	if(empty($_POST['btc_address'])) {
		$errors[] = 'You must provide your BTC address.';
	}

	if(!empty($errors)) {
		echo '<div class="error">' . $errors[0] . '</div>';
	} else {

		$my_address = '1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq';
		$my_callback_url = 'https://www.tenquota.com/';
		$root_url = 'https://blockchain.info/api/receive';

		$parameters = 'method=create&address=' . $my_address .'&callback='. urlencode($my_callback_url);

		$response = file_get_contents($root_url . '?' . $parameters);

		$object = json_decode($response);

		$stmt = $db->prepare("INSERT INTO `pending_btc`(btc_email, btc_address, btc_amount, item_id, btc_timestamp) 
			VALUES(:email, :address, :amount, :itemId, UNIX_TIMESTAMP())");
		$stmt->execute(array(
			':email' => $_POST['email'], 
			':address' => $_POST['btc_address'], 
			':amount' => $_SESSION['btc']['btcPrice'],
			':itemId' => $_SESSION['btc']['itemId']
			)
		);
		echo '<div class="success">You may now send <b>' . $_SESSION['btc']['btcPrice'] . '</b> to: <b>' 
		. $object->input_address . '</b>. Check e-mail a few minutes after sending.';
	}
} else {
	$btcPrice = file_get_contents('https://blockchain.info/tobtc?currency=USD&value=' . $_SESSION['btc']['itemPrice']);
	$_SESSION['btc']['btcPrice'] = $btcPrice;
}

?>

<h1><?php echo $_SESSION['btc']['itemName']; ?></h1>

<p>You are purchasing this item for $<?php echo $_SESSION['btc']['itemPrice']; ?> USD / <?php echo $_SESSION['btc']['btcPrice']; ?> BTC.</p>

<p>Fill out the fields below and press the "Confirm" button. Do NOT send the money yet.

<form action="" method="POST">
	<label>Your E-mail - Code destination</label>
	<input type="email" name="email" required/>
	<label>Your Bitcoin Address</label>
	<input type="text" name="btc_address" required/>
	<button type="submit" name="confirmPurchase">Confirm</button>
</form>