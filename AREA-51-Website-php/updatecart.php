<?php
// Get the current list of products
session_start();
$productList = null;
if (isset($_SESSION['productList'])){
	$productList = $_SESSION['productList'];
}

// Update product quantity
// Get product quantity information
$alterList = array();
// Update current list of products
foreach ($productList as $id => $prod) {
	$alterList[$id] = array( "id"=>$prod['id'], "name"=>$prod['name'], "price"=>$prod['price'], "quantity"=>$prod['quantity'] );
	$newquantity = "quantity-".$prod['id'];
	if($_GET[$newquantity]!=null){
		$newquantity = $_GET[$newquantity];
		$alterList[$id]['quantity'] = $newquantity;
	}
}

$_SESSION['productList'] = $alterList;
header('Location: showcart.php');
?>
