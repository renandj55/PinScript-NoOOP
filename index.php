<?php

require('header.php');

foreach(getProducts() as $product):
?>

<div class="product">
	<article>
		<header>
			<h2><?php echo $product['product_name']; ?></h2>
		</header>
		<p><?php echo $product['product_description']; ?></p>
		<span id="price">
			<?php echo $product['product_price']; ?>
		</span>
		<?php
			if($code_count == 'yes'):
		?>
			<span id="codeCount">
				<?php echo getCodeCount($product['product_id']); ?>
			</span>
		<?php
			endif;
			if($pp_allow == 'yes'):
		?>
		<form action="setExpressCheckout.php" method="POST">
			<input type="hidden" name="itemId" value="<?php echo $product['product_id']; ?>" />
			<input type="hidden" name="itemName" value="<?php echo $product['product_name']; ?>" />
			<button type="submit">Purchase with Paypal</button>
		</form>
		<?php
			endif;
			if($btc_allow == 'yes'):
		?>
		<form action="bitcoin.php" method="POST">
			<input type="hidden" name="itemId" value="<?php echo $product['product_id']; ?>" />
			<input type="hidden" name="itemName" value="<?php echo $product['product_name']; ?>" />
			<input type="hidden" name="itemPrice" value="<?php echo $product['product_price']; ?>" />
			<button type="submit">Purchase with Bitcoin</button>
		</form>
		<?php
			endif;
		?>
	</article>
</div>

<?php
endforeach;

require('footer.php');

?>