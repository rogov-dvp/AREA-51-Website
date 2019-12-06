<!DOCTYPE html>
<html>
<head>
        <title>AREA 51 Gift Store</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="style/style.css"/>
<title>AREA 51 Gift Store Grocery Order Processing</title>
</head>
<body>

<h3><a href="index.php">Area 51 Gift Store</a></h3>

<?php
include 'include/db_credentials.php';

/**
 Determine if valid customer id was entered
 Determine if there are products in the shopping cart
 If either are not true, display an error message
 **/
 
/** Get customer id **/
	$custId = null;
	if(isset($_POST['customerId'])){			
		$custId = $_POST['customerId'];
		//if custId is a number
		if(!is_numeric($custId)) {
			die("<h1>Customer Id is not a number and is not valid.</h1>");
		}
	}
	
/** $productList holds array of customer's products during their session on the site?**/
	session_start();
	$productList = null;
	if (isset($_SESSION['productList'])){
		$productList = $_SESSION['productList'];
	} else {
		die("<h1>Your shopping cart is empty!</h1>");
	}

/** Make connection and validate **/

	$conn = sqlsrv_connect($server, $connectionInfo);
	
	if($conn) {
		//echo "Connection establish.<br />";
	} else {
		echo "<h1>Connection could not be established.</h1>";
		die(print_r(sqlsrv_errors(), true));
	}

/** Check if user ID exists in customer table **/
	$validId = "SELECT firstName FROM customer WHERE customerId = ?";
	$params = array($custId);
	$stmt = sqlsrv_query($conn, $validId, $params);
	if( $stmt === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}
	$valid = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
	if($valid == null)
		echo "<h1>User not found</h1>" ;
	else{

/** Validate user password **/

/** Validate input password with the stored password in customer table **/
	if(isset($_POST['password'])) {
		if(!isset($_POST['password'])) {
			$error['password'] = "<p>Please supply your password.</p>\n";
		}
		else{
			$validpw = "SELECT password FROM customer WHERE customerId = ?";
			$params = array($custId);
			$stmt = sqlsrv_query($conn, $validpw, $params);
			if( $stmt === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
			$valid = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
			$pswd = $_POST['password'];
			if($valid['password'] != $pswd)
				echo "Password not correct!<br />";
			else{
	
	
/** Save order information to database **/

	/** Update order summary without total amount **/
	$date = date("Y-m-d H:i:s");
	$sql = "INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (?,?,0)";
	$pstmt = sqlsrv_query($conn,$sql,array($custId,$date));
	if( $pstmt === false ) {
	 die( print_r( sqlsrv_errors(), true));
	}
	
	/** Retrieve order ID **/
	$sql = "SELECT orderId FROM ordersummary WHERE customerId = ? AND orderDate = ?";
	$pstmt = sqlsrv_query($conn,$sql,array($custId,$date));
	if( $pstmt === false ) {
	 die( print_r( sqlsrv_errors(), true));
	}
	$orderId = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);	
	$orderId = $orderId['orderId'];

	/** Output order summary with a table **/
	echo "<table border=2><title>Ordered Products</title>";
	echo "<tr>	<th>Product ID</th>	<th>Product name</th>	<th>Quantity</th>	<th>Price</th>	<th>Subtotal</th>	</tr>";
	
	/** Insert each product in the cart to ordered product **/
	echo "<h1>Your Order Summary</h1>";
	$total =0;
	$price = 0;
	$id = 0;
	$pname = "";
	$quantity = 0;
	foreach ($productList as $id => $prod) {

		$price = $prod['price'];
		$id = $prod['id'];
		$pname = $prod['name'];
		$quantity = $prod['quantity'];

		/** Inserte into ordered product **/
		$sqlOP = "INSERT INTO orderproduct (orderId,productId,quantity,price) VALUES (?,?,?,?)";
		$pstmt = sqlsrv_query($conn,$sqlOP,array($orderId,$id, $quantity, $price));
		if( $pstmt === false ) {
		 die( print_r( sqlsrv_errors(), true));
		}
			
		/** Update total amount for order record **/
		$subtotal = $prod['quantity']*$price;
		$total = $total + $subtotal;		
		
		/** Update output table **/
		echo "<tr> <td>".$id."</td><td>".$pname."</td><td>".$quantity."</td><td>$".number_format($price,2)."</td><td>$".number_format($subtotal,2)."</td></tr>";
		
	}
	
	/** Output total amount and close the table **/
	echo "<tr><td colspan=4 align=right> Order total </td><td>$".number_format($total,2)."</td></tr>";
	echo "</table>";
	
	echo "<h2>Order Complete, Warning aliens bite! :)" . "</h2>";
	echo "<h2>Your order reference number is: " . $orderId . "</h2>";
	
	/** Display customer infomation **/
	$sqlCust = "SELECT customerId, firstName, lastName, email, phonenum, address, city, state, postalCode, country FROM customer WHERE customerId = $custId"; 
	$stmtCust = sqlsrv_query($conn, $sqlCust);
	$line = sqlsrv_fetch_array($stmtCust, SQLSRV_FETCH_ASSOC);
	
	if($stmtCust === false) {
		die( print_r(sqlsrv_errors(), true));
	}

	$firstName = $line['firstName'];
	$lastName = $line['lastName'];
	$email = $line['email'];
	$phonenum = $line['phonenum'];
	$address = $line['address'];
	$city = $line['city'];
	$state = $line['state'];
	$postalCode = $line['postalCode'];
	$country = $line['country'];

	echo "<h2> Shipping to customer: ". $firstName . " " . $lastName . " | ID " . $custId . "</h2>";
	echo "<h2>Contant Info: ". $email . " | " . $phonenum . "</h2>"; 
	echo "<h2>Address to: " . $address . ", " . $city . ", " . $state . ", " . $country . ", " . $postalCode . "</h2>";
	
	/** Update the total amount in order summary **/
	$sql4 = "UPDATE OrderSummary SET totalAmount=? WHERE orderId=?";
	sqlsrv_query($conn, $sql4, array( $total, $orderId));
		


/** Clear session/cart **/
 if (session_status() == PHP_SESSION_ACTIVE) { session_destroy(); }
 	//echo "classified documents have been destroyed.";
				
			}
		}
	}	
	};
	echo("<h2><a href=\"showcart.php\">Your cart</a></h2>");
//ALEX:Close connection
	sqlsrv_close($conn);
	//echo "Connection out..."
?>



</body>
</html>


	
