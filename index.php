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
	</article>
</div>

<?php
endforeach;

require('footer.php');

?>