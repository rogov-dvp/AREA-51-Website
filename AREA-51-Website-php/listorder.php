<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Area 51 Gift Store Orders</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="style/style.css"/>
</head>
<body>
	
<div class="ribbon">
  <a href="index.php"><span>Area 51 Gift Store</span></a>
<?php
 session_start();
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

<div class="site">
<h1>Order List</h1>

<?php
	include 'include/db_credentials.php';

	/** Create connection, and validate that it connected successfully **/

	$con = sqlsrv_connect ($server, $connectionInfo);

	if ( $con == true ){
		echo '<script>console.log("Connection established")</script>';	
	} else {
		echo '<script>console.log("Connection not established")</script>';
		die( print_r( sqlsrv_errors(), true));
	}


	/**
	Useful code for formatting currency:
		number_format(yourCurrencyVariableHere,2)
	**/
	/** Write query to retrieve all order headers **/
	
	$sql = "SELECT orderId, orderDate, firstName, lastName, totalAmount FROM ordersummary as os join customer as c on c.customerId = os.customerId";

	$stmt = sqlsrv_query( $con, $sql);
	if( $stmt === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	echo("<table><title>Orders</title><tr align=right><th>Order ID</th><th>Order Date</th><th>Customer Name</th><th>Total Amount</th></tr>");
	while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
		$ordertime = $row['orderDate']->format('Y-m-d H:i:s');
		echo("<tr class='orderCustomer' ><td>" . $row['orderId'] . "</td><td>" . $ordertime . "</td><td>" . $row['firstName'] ." ". $row['lastName']. "</td><td>" . "$".$row['totalAmount'] . "</td></tr>");
		
		/** For each order in the results
		Print out the order header information
		Write a query to retrieve the products in the order
			- Use sqlsrv_prepare($connection, $sql, array( &$variable ) 
				and sqlsrv_execute($preparedStatement) 
				so you can reuse the query multiple times (just change the value of $variable)
		For each product in the order
			Write out product information 
		**/
		
		$sql = "SELECT productId, quantity, price FROM orderproduct WHERE orderId = ?";
		$params = array($row['orderId']);

		$product = sqlsrv_query( $con, $sql, $params);
		if( $product === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}
		
		echo("<tr class=dropdown><td colspan=5 align=right><table class=dropdown-content border=2 width=300><title>Ordered Products</title><tr align=right><th>Product ID</th><th>Quantity</th><th>Price</th></tr>");
		
		while($line = sqlsrv_fetch_array($product, SQLSRV_FETCH_ASSOC))
		{
			$listprice = number_format($line['price'],2);
			echo("<tr><td>" . $line['productId']."</td><td>".$line['quantity']."</td><td>"."$".$listprice."</td></tr>");
		}
		echo("</table></td></tr>");

	}
	echo("</table>");
	
	
	/** Close connection **/
	sqlsrv_close($con);
	echo("</p><script>console.log(\"Loading Complete!\")</script>");
?>
</div>
</body>
</html>