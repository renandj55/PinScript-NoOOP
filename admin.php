<?php

require('header.php');

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
<a href="#addProduct" id="addProductDisplay">Add Product</a>

<form action="add_product.php" method="POST" id="addProductForm">
	<input type="text" name="name" placeholder="Title" value="<?php echo (isset($_SESSION['add_product']['name']) ? $_SESSION['add_product']['name'] : ''); ?>" maxlength="100"/>
	<textarea name="description" placeholder="Description"><?php echo (isset($_SESSION['add_product']['description']) ? $_SESSION['add_product']['description'] : ''); ?></textarea>
	<button type="submit">Add Product</button>
</form>


<script>
$(document).ready(function() {

$("#addProductDisplay").click(function() {
	$("#addProductForm").toggle();
});

});
</script>
<?php

require('footer.php');

?>

