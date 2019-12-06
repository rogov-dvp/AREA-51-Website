<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Area 51 Gift Store</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="style/style.css"/>

</head>
<body>

<div class="ribbon">
		<a href="index.php"><span>Area 51 Gift Store</span></a>
</div>
<h1>Search for the products you want to buy:</h1>

<div class="site container-fluid">

<nav class="navbar" id="filter">
		<form class="form-inline col-md-8" method="get" action="listprod.php">
			<div class="form-group col-md-8">
				<select name = "myList" class="form-control" id="searchFilter" placeholder="Category" style="width:200px;">
				   <option value = "" selected disabled hidden>Category</option>
				   <option value = "1">Cute</option>
				   <option value = "2">Disney</option>
				   <option value = "3">Scary</option>
				   <option value = "4">Old School</option>
				   <option value = "5">Happy</option>
				   <option value = "6">Green</option>
				   <option value = "7">HypnoToad Category</option>
				 </select>
				<input class="form-control" id="searchFilter" style="width:200px;" type="text" name="productName" placeholder="Search product"/>
		
			</div>
			<input type="submit" class="btn btn-primary" value="Submit"/>

			<form action="listprod.php">
			<input type="submit" class="btn btn-secondary" name="Insert_Ad" value="reset" style="margin-left:8px;">
			</form>
		</form>

		<div class="form-inline">
			<a class="btn" id="cart" href="showcart.php">
				<i id="cart" class="material-icons">shopping_cart</i>
			</a>
		</div>
		

	</nav>




<?php
	include 'include/db_credentials.php';

	//session_start();

	$formSearch = "%";
	$catId = "";
	
	session_start();
	
	/** Get product name to search for **/
	if (isset($_GET['productName'])){
		$name = $_GET['productName'];
		$formSearch = $formSearch . $name . "%";
	}

	/** Get category ID to search for **/
	if (isset($_GET['myList'])){
		$catId = $_GET['myList'];
		
	}

	/** $name now contains the search string the user entered
	 Use it to build a query and print out the results. **/
	 
	/** Create and validate connection **/

	$con = sqlsrv_connect ($server, $connectionInfo);

	if ( $con == true ){
		echo '<script>console.log("Connection established")</script>';	
	} else {
		echo '<script>console.log("Connection not established")</script>';
		die( print_r( sqlsrv_errors(), true));
	}


	/** Print out the ResultSet **/
	if (isset($_GET['myList'])){
		$sqlQuery = "SELECT productId, productName, productPrice, categoryName FROM product JOIN category on category.categoryId = product.categoryId WHERE productName LIKE ? AND category.categoryId = ?";
		$params = array(&$formSearch, &$catId);
	} else{
		$sqlQuery = "SELECT productId, productName, productPrice, categoryName FROM product JOIN category on category.categoryId = product.categoryId WHERE productName LIKE ?";
		$params = array(&$formSearch);
	}
		
	$pstmt = sqlsrv_prepare($con, $sqlQuery, $params);
	
	if( !$pstmt ) {
	   die( print_r( sqlsrv_errors(), true));
   }

	$result = sqlsrv_execute($pstmt);
	if( $result === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	   
	echo "<div id='prod-container'>";
	// echo("<table id=\"productTable\"> <title>Stuff We Have</title>");

	while($row = sqlsrv_fetch_array ($pstmt, SQLSRV_FETCH_ASSOC)){
		$productId = $row['productId'];
		$productName = $row['productName'];
		$productPrice = $row['productPrice'];
		$categoryName = $row['categoryName'];
		

		// echo("<tr><td><a href='addcart.php?id=" . $productId . "&name=" . urlencode($productName) .  "&price=" . $productPrice . "'>Add to Cart</a></td>");
		// echo("<td><a href=product.php?id=" . $productId . " class=product-name>". $productName . "</a></td><td>" . $categoryName . "</td><td align=\"right\"> $" . $productPrice . "</td></tr>");

		echo("<div class='prod-items'><h3><a href=product.php?id=" . $productId . " class=product-name>".$productName ."</a></h3><br><h4>".$categoryName."<br> $".$productPrice."</h4>");
		echo("<a style='width:100%' href='addcart.php?id=" . $productId . "&name=" . urlencode($productName) .  "&price=" . $productPrice . "'>Add to Cart</a></div>");

	
	/** 
	For each product create a link of the form
	addcart.php?id=<productId>&name=<productName>&price=<productPrice>
	Note: As some product names contain special characters, you may need to encode URL parameter for product name like this: urlencode($productName)
	**/

	}
	// echo("</table>");
	/** Close connection **/
	sqlsrv_close($con);

	/**
        Useful code for formatting currency:
	       number_format(yourCurrencyVariableHere,2)
     **/
?>
</div>
</body>
</html>