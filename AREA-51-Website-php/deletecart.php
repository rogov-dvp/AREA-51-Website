
<?php
// Get the current list of products
session_start();
$productList = null;
if (isset($_SESSION['productList'])){
	$productList = $_SESSION['productList'];
}

// Delete product selected
// Get product information
$did = null;
if (isset($_GET['did'])){
	$did = $_GET['did'];
}else{
	header('Location: showcart.php');
}

// Update current list of products
if (isset($productList[$did])){
	unset($productList[$did]);
}

$_SESSION['productList'] = $productList;
header('Location: showcart.php');
?>
