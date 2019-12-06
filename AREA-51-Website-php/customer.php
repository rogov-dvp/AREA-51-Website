<!DOCTYPE html>
<html>
<head>
        <title>AREA 51 Gift Store</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="style/style.css"/>
<title>Customer page</title>
</head>
<body>

<div class="ribbon">
		<a href="index.php"><span>Area 51 Gift Store</span></a>
<?php
	if(session_status()==2){}else{session_start();}
    // TODO: Display user name that is logged in (or nothing if not logged in)
	if(isset($_SESSION['authenticatedUser'])){
		if($_SESSION['authenticatedUser'] != null){
			$user = $_SESSION['authenticatedUser'];
			echo "<a href=customer.php><span>Hello ".$user."</span></a>";
			echo "<a href=logout.php><span>Log out</span></a>";
			
		}else{
			echo "<a href=login.php><span>Login</span></a>";
		}
	}
	else{
		echo "<a href=login.php><span>Login</span></a>";
	}
	echo "</div>";
	
	
?>

<?php 
    include 'auth.php';
    include 'include/db_credentials.php';
?>

<?php
$user = $_SESSION['authenticatedUser'];
    
// TODO: Print Customer information
$sql = "SELECT * FROM customer WHERE userid = ?";

$con = sqlsrv_connect($server, $connectionInfo);
$pstmt = sqlsrv_query($con, $sql, array($user));

echo ("<table>");

if ($row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC)){
    $customerId = $row['customerId'];
    $firstName = $row['firstName'];    
    $lastName = $row['lastName'];
    $email = $row['email'];
    $phonenum = $row['phonenum'];
    $address = $row['address'];
    $city = $row['city'];
    $state = $row['state'];
    $postalCode = $row['postalCode'];
    $country = $row['country'];
    $userid = $row['userid'];


    echo ("<tr><th>ID</th><td>".$customerId."</td></tr>");
    echo ("<tr><th>First Name</th><td>".$firstName."</td></tr>");
    echo ("<tr><th>Last Name</th><td>".$lastName."</td></tr>");
    echo ("<tr><th>Email</th><td>".$email."</td></tr>");
    echo ("<tr><th>Phone</th><td>".$phonenum."</td></tr>");
    echo ("<tr><th>Address</th><td>".$address."</td></tr>");
    echo ("<tr><th>City</th><td>".$city."</td></tr>");
    echo ("<tr><th>State</th><td>".$state."</td></tr>");
    echo ("<tr><th>Postal Code</th><td>".$postalCode."</td></tr>");
    echo ("<tr><th>Country</th><td>".$country."</td></tr>");
    echo ("<tr><th>User ID</th><td>".$userid."</td></tr>");

}

echo "</table>";
                    
sqlsrv_free_stmt($pstmt);
sqlsrv_close($con);
// Make sure to close connection
?>

<form method="GET" action="customer.php">
	<input type="submit" class="btn btn-primary" name="listorders" value="View your orders">
	<input type="submit" class="btn btn-primary" name="change" value="Edit your information">
</form>

<?php
	if(isset($_GET['change']))
		header ('Location: editcustomerinfo.php');
	if(isset($_GET['listorders'])){

		include 'include/db_credentials.php';
		
		$con = sqlsrv_connect ($server, $connectionInfo);
		if ( $con == true ){
			echo '<script>console.log("Connection established")</script>';	
		} else {
			echo '<script>console.log("Connection not established")</script>';
			die( print_r( sqlsrv_errors(), true));
		}

		$user = $_SESSION['authenticatedUser'];
		$sql = "SELECT orderId, orderDate, totalAmount FROM ordersummary as os join customer as c on c.customerId = os.customerId WHERE c.userId = ?";
		$params = array($user);
		$stmt = sqlsrv_query( $con, $sql, $params);
		if( $stmt === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}
		
		echo("<table><title>Orders</title><tr align=right><th>Order ID</th><th>Order Date</th><th>Total Amount</th></tr>");
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
			$ordertime = $row['orderDate']->format('Y-m-d H:i:s');
			echo("<tr class='orderCustomer' ><td>" . $row['orderId'] . "</td><td>" . $ordertime . "</td><td>" . "$".$row['totalAmount'] . "</td></tr>");
			
			/** List products in the order **/
			$sql = "SELECT productId, quantity, price FROM orderproduct WHERE orderId = ?";
			$params = array($row['orderId']);
			$product = sqlsrv_query( $con, $sql, $params);
			if( $product === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
			echo("<tr class=dropdown><td colspan=4 align=right><table class=dropdown-content border=2 width=300><title>Ordered Products</title><tr align=right><th>Product ID</th><th>Quantity</th><th>Price</th></tr>");
			while($line = sqlsrv_fetch_array($product, SQLSRV_FETCH_ASSOC))
			{
				$listprice = number_format($line['price'],2);
				echo("<tr><td>" . $line['productId']."</td><td>".$line['quantity']."</td><td>"."$".$listprice."</td></tr>");
			}
			echo("</table></td></tr>");
		}
		echo("</table>");
	}
	
		
?>

</body>
</html>