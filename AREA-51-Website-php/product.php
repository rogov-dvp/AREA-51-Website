<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Area 51 Gift Store</title>
<style> 
	div.images {
		display: flex;
		justify-items: center;
		align-items: center;
		/* box-shadow: 4px 8px #392E2E; */
	}
	img.imagesInner {
		max-width:250px; 
		max-height:450px;
	}
	textarea.review  {	
		width: 60%;		
		height: 150px;
		padding: 12px 20px;
		box-sizing: border-box;
		border: 2px solid #ccc;
		border-radius: 4px;
		background-color: #f8f8f8;
		resize: none;
	
	}
</style>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="style/style.css"/>
<title>Area 51 Gift Store - Product Information</title>
</head>
<body>

<div class="ribbon">
		<a href="index.php"><span>Area 51 Gift Store</span></a>
		

<?php 
    include 'include/db_credentials.php';
?>

<?php
	session_start();
	$user = "";
    // Display user name that is logged in (or nothing if not logged in)
	if(isset($_SESSION['authenticatedUser'])){
		if($_SESSION['authenticatedUser'] != null){
			$user = $_SESSION['authenticatedUser'];
			echo "<a href=customer.php><span>Hello customer ". $user."</span></a>";
			echo "<a href=logout.php><span>Log out</span></a>";
			
		}else{
			echo "<a href=login.php><span>Login</span></a>";
		}
	}
	else{
		echo "<a href=login.php><span>Login</span></a>";
	}
	echo "</div>";



// Get product name to search for
// TODO: Retrieve and display info for the product
// $id = $_GET['id'];

include 'include/db_credentials.php';

/** Connect to database **/
$con = sqlsrv_connect ($server, $connectionInfo);

if ( $con == true ){
	echo '<script>console.log("Connection established")</script>';	
} else {
	echo '<script>console.log("Connection not established")</script>';
	die( print_r( sqlsrv_errors(), true));
}

// review section
// if(null !== ($_POST(['new-review']))) {
// 	$sql = "INSERT INTO review(reviewDate,customerId,productId,reviewComment) VALUES (?,?,?,?)";
// 	$pstmt = sqlsrv_query($con,$sql,array(date("Y-m-d H:i:s"),$custId,$productId,$_POST(['description-new'])));
// 	$idk = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
// }
// if(null !== ($_POST(['rewrite-review']))) {
// 	$sql = "UPDATE review SET reviewDate=?, reviewComment=? WHERE customerId=$custId";
// 	$pstmt = sqlsrv_query($con,$sql,array(date("Y-m-d H:i:s"),$_POST(['description-old'])));
// }

/** Obtain product ID **/
//session_start(); 
if (isset($_GET['id'])){
	$productId = $_GET['id'];
} else{ 	// No products currently in list.  Create a list.
	$productId = "";
} 
/** Query for product information **/
$sql = "SELECT * FROM product WHERE productId = ?";
$params = array($productId);
$stmt = sqlsrv_query($con, $sql, $params);
if( $stmt === false ) {
	 die( print_r( sqlsrv_errors(), true));
}
$prod = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

/** Offer customers some other options **/
echo "<div class=\"form-inline\">
<a class=\"btn\" id=\"cart\" href=\"showcart.php\">
	<i id=\"cart\" class=\"material-icons\">shopping_cart</i>
</a>
</div>";
echo "<div style=display:inline-block;>";
echo "<h3><a href='addcart.php?id=" . $prod['productId'] . "&name=" . urlencode($prod['productName']) .  "&price=" . $prod['productPrice'] . "'>Add to Cart</a></h3>";
echo("<h3><a href=\"checkout.php\">Check Out</a></h3>");

echo "<h3><a href=listprod.php>Continue Shopping</a></h3></div>";

/**Next and Previous Buttons*/
$sql = "SELECT COUNT(*) as max FROM product";
$stmt = sqlsrv_query($con, $sql);
if( $stmt === false ) {
	die( print_r( sqlsrv_errors(), true));
}
$prodcount = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
echo "<div id='next-prev'>";
if($prod['productId']>=1 AND $prod['productId']<$prodcount['max'])
echo "<h3 class='btn'><a class='prod-btn' style='right:5%;' href=product.php?id=".($prod['productId']+1).">Next product</a></h3>";
if($prod['productId']<=$prodcount['max'] AND $prod['productId']>1)
echo "<h3 class='btn'><a class='prod-btn' style='left:5%;' href=product.php?id=".($prod['productId']-1).">Previous product</a></h3>";
echo "</div>";


echo "<div class='prod'>";
if($prod['productImageURL'] != null){
	echo "<div id=prodimage class='images'>";
	echo "<a href='displayImage.php?id=" . $prod['productId'] . "'>";
	echo "<img id='productImage' src='" . $prod['productImageURL'] . "' class='imagesInner'/></a></div>";

}
/** Display product information **/

echo "<div id='prod-details'>";
echo "<h4>Product ID: ".$prod['productId']."</h4>";
echo "<h4>Name: </td><td>".$prod['productName']."</h4>";
echo "<h4>Price: </td><td>".$prod['productPrice']."<h4>";
echo "<hr>Decription: </td><td>".$prod['productDesc']."</h4></div>";

echo "</div>";

// echo "<table style='vertical-align:bottom;'><title></title>";
// echo "<tr><td>Product ID: </td><td>".$prod['productId']."</td></tr>";
// echo "<tr><td>Name: </td><td>".$prod['productName']."</td></tr>";
// echo "<tr><td>Price: </td><td>".$prod['productPrice']."</td></tr>";
// echo "<tr><td>Decription: </td><td>".$prod['productDesc']."</td></tr></table>";


echo "<div id='reviews'>";

echo "<hr>";
// TODO: If there is a productImageURL, display using IMG tag

/** Offer costumers the option to go to the next and previous product **/

//Adding review to product.php
//First off, restrict user to one review per product:
echo "<section style=margin-left:10px;>";
echo "<h2>Reviews</h2>";
$sql = "SELECT customerId FROM customer WHERE userid= ?";
$pstmt = sqlsrv_query($con, $sql, array($user));
if( $pstmt === false ) {
	 die( print_r( sqlsrv_errors(), true));
}
$exist = sqlsrv_fetch_array( $pstmt, SQLSRV_FETCH_ASSOC);
	$custId = $exist['customerId'];
if ($user === "") {
//not logged in
echo	"Please log-in to write a review: ";
echo 	"<a href=login.php><span>Login Here!</span></a>";

}else {

	$sqlNew = "SELECT customerId FROM review WHERE customerId = ? AND productId=?";
	$newstmt = sqlsrv_query($con,$sqlNew,array($custId,$productId));
	if( $newstmt === false ) {
	 die( print_r( sqlsrv_errors(), true));
	} else {
	$line = sqlsrv_fetch_array($newstmt, SQLSRV_FETCH_ASSOC);
	if($line['customerId'] == null) {
		//write a new review (first time)
		echo "<form method='GET' action='updateReview.php'>";
		$_SESSION['productId'] = $productId;
		echo "<textarea class='review' placeholder='Write a review for this product' name='description-new'></textarea><br>";
		echo "<button style='vertical-align:bottom;' name='new-review'>Add review</button>";
		echo "</form>";
	}
	else if ($exist['customerId'] != null){
		//already has a review which gives him the option to change it
		echo "<form method='GET' action='updateReview.php'>";
		$_SESSION['productId'] = $productId;
		echo "<textarea class='review' placeholder='Update your review' name='description-old'></textarea><br>";
		echo "<button style='vertical-align:bottom;' name='rewrite-review'>Add review</button>";
		echo "</form>";
	}
	}
	echo "</section>";
	echo "<br>";
	
	//search for the three most recent reviews(including the user's ID)
	$sql = "SELECT firstName, lastName FROM customer WHERE customerId=?";
	$stmt = sqlsrv_query($con, $sql,array($custId));
	if( $stmt === false ) {
		die( print_r( sqlsrv_errors(), true));
	} 
	$sql2 = "SELECT reviewComment, reviewDate FROM review WHERE customerId = ? AND productId = ?";
	$stmt2 = sqlsrv_query($con, $sql2,array($custId,$productId));
		if( $stmt2 === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		$line = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
		$date = @date_format($line['reviewDate'], 'Y-m-d H:i:s');

		echo "<div style='border: 5px solid black; width:60%; height:150px;'>";
		echo "<div>" . $date . " | " . $row['firstName'] . " " . $row['lastName'] . " </div><hr>";
		echo "<div>" . $line['reviewComment'] . " </div>";
		echo "</div>";
}
	$date = date('Y-m-d H:i:s');
	$sql = "SELECT TOP 3 firstName, lastName, reviewDate, reviewComment FROM customer AS c FULL OUTER JOIN review AS r ON 'c.customerId' = 'r.customerId' 
	WHERE reviewDate < ? AND productId= ? AND r.customerId != ? ORDER BY reviewDate DESC"; 
	$stmt = sqlsrv_query($con, $sql, array($date,$productId,$custId));
	if( $stmt === false ) {
		die( print_r( sqlsrv_errors(), true));
	} else {
		while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
			echo "<br>";
			$date = date_format($row['reviewDate'], 'Y-m-d H:i:s');
			echo "<div>" . $date . " | " . $row['firstName'] . " " . $row['lastName'] . " </div>";
			echo "<div>" . $row['reviewComment'] . " </div>";
		}

	}

echo '</div>';

?>

</body>
</html>