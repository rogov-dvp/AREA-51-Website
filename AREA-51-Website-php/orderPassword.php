<!DOCTYPE html>
<html>
<head>
<title>AREA 51 Gift Store Grocery Order Processing</title>
</head>
<body>

<?php
include 'include/db_credentials.php';


/**
 Determine if valid customer id was entered
 Determine if there are products in the shopping cart
 If either are not true, display an error message
 **/
 
/** Get customer id **/
	$custId = null;
	if(isset($_GET['customerId'])){			
		$custId = $_GET['customerId'];
		//if custId is a number
		if(!is_numeric($custId)) {
			die("<div>Customer Id is not a number and is not valid.</div>");
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
		echo "Connection could not be established.<br />";
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
		echo "User not found<br />" ;
	else{

/** Validate user password **/
		echo "<h1>Enter your password to confirm your transaction:</h1>";
		echo "<form method=post action=<?=$_SERVER['PHP_SELF']?>> <?=$error['passwordValidate']?>";
		echo "<input type=text id=password size=50 id=password name=password value=<?=($_POST['password'] ? htmlentities($_POST['password']) : '')?> />";
		echo "<input type=submit name=submit value=Submit> </form>";
		
		if(isset($_POST['submit'])) {
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
			echo "Your Order Summary";
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
			
			echo "<br/>Order Complete, Warning aliens bite! :)" . "<br/>";
			echo "Your order reference number is: " . $orderId . "<br/>";
			
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

			echo "Shipping to customer: ". $firstName . " " . $lastName . " | ID " . $custId . "</br>";
			echo "Contant Info: ". $email . " | " . $phonenum . "<br/>"; 
			echo "Address to: " . $address . ", " . $city . ", " . $state . ", " . $country . ", " . $postalCode . "<br/>";

			echo ("<a href=shop.html> Return to Shopping </a><br/>");
			
			/** Update the total amount in order summary **/
			$sql4 = "UPDATE OrderSummary SET totalAmount=? WHERE orderId=?";
			sqlsrv_query($conn, $sql4, array( $total, $orderId));
				


			/** Clear session/cart **/
			 if (session_status() == PHP_SESSION_ACTIVE) { session_destroy(); }
				//echo "classified documents have been destroyed.";
		}
	};

//ALEX:Close connection
	sqlsrv_close($conn);
	//echo "Connection out..."
?>
</body>
</html>

