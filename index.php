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
		?>
		<button id="paypal<?php echo $product['product_id']; ?>">Purchase with Paypal</button>
		<button id="bitcoin<?php echo $product['product_id']; ?>">Purchase with Bitcoin</button>
	</article>
</div>

<?php
endforeach;

require('footer.php');

?>