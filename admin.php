<?php

require('header.php');

$products = getProducts();

?>

<h1>Settings</h1>

<?php
if(isset($_POST['title'])) {
	$xml_file->website_title = $_POST['title'];
	$xml_file->asXML($main_directory . '/settings.xml');
}

?>

<form action="" method="POST">
	<input type="text" placeholder="Website Title" name="title"/>
	<button type="submit">Change Title</button>
</form>

<?php

if (isset($_SESSION['add_product']['error'])) {
	echo $_SESSION['add_product']['error'];
}
?>
<a href="#addProduct" id="addProductDisplay">Add Product</a><br />

<form action="add_product.php" method="POST" id="addProductForm">
	<input type="text" name="name" placeholder="Title" value="<?php echo (isset($_SESSION['add_product']['name']) ? $_SESSION['add_product']['name'] : ''); ?>" maxlength="100"/>
	<textarea name="description" placeholder="Description"><?php echo (isset($_SESSION['add_product']['description']) ? $_SESSION['add_product']['description'] : ''); ?></textarea>
	<input type="text" name="price" placeholder="Price" value="<?php echo (isset($_SESSION['add_product']['price']) ? $_SESSION['add_product']['price'] : ''); ?>" />
	<button type="submit">Add Product</button>
</form>

<a href="#importList" id="importListDisplay">Add Codes to Product</a><br />

<form enctype="multipart/form-data" action="import.php" method="POST" id="importListForm">
	<?php
	if(isset($_SESSION['admin']['message'])) {
		echo $_SESSION['admin']['message'];
		unset($_SESSION['admin']['message']);
	}
	?>
	<p>If single code is entered, list will be ignored.</p>
	<select name="product">
		<?php
		foreach($products as $product) {
			echo '<option value="' . $product['product_id'] . '">' . $product['product_name'] . '</option>';
		}
		?>
	</select>
	<input type="text" name="code" placeholder="code"/>
	<input type="file" name="list" />
	<button type="submit">Add Code(s)</button>
</form>

<?php
if(!empty($products)):
?>
<a href="#removeCode" id="removeCodeDisplay">Remove Code or Product</a>
<form action="remove.php" method="POST" id="removeCodeForm">
	<?php
	if(isset($_SESSION['remove']['message'])) {
		echo $_SESSION['remove']['message'];
		unset($_SESSION['remove']);
	}
	?>
	<p>If no code is provided, the product will be removed. Otherwise the code of product picked will be removed.</p>
	<select name="product">
		<?php
		foreach($products as $product) {
			echo '<option value="' . $product['product_id'] . '">' . $product['product_name'] . '</option>';
		}
		?>
	</select>
	<input type="text" name="code" />
	<button type="submit" onclick="return confirm('Are you sure?');">Remove</button>
</form>
<?php
endif;
?>
<script>
$(document).ready(function() {

$("#addProductDisplay").click(function() {
	$("#addProductForm").toggle();
});

$("#importListDisplay").click(function() {
	$("#importListForm").toggle();
});

$("#removeCodeDisplay").click(function() {
	$("#removeCodeForm").toggle();
});

});
</script>
<?php

require('footer.php');

?>

