<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Administrator Page</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="style/style.css"/>
</head>

<body>

<?php 
// TODO: Include files auth.php and include/db_credentials.php
include 'auth.php';
include 'header.php';
include 'include/db_credentials.php';
?>

<?php
// TODO: Write SQL query that prints out total order amount by day
/** Connecting to database **/
	$conn = sqlsrv_connect($server, $connectionInfo);
	if($conn) {
		//echo "Connection establish.<br />";
	} else {
		echo "Connection could not be established.<br />";
		die(print_r(sqlsrv_errors(), true));
	}
	
/** Query the total sale of each day **/
	$sql = "SELECT SUM(totalAmount) as totalAmount, orderDate FROM ordersummary GROUP BY orderDate ORDER BY orderDate";
	$stmt = sqlsrv_query($conn, $sql);
	if($stmt === false)
	{
		die(print_r( sqlsrv_errors(), true));
	}
	
/** Display the total sale and date of the sale **/
	echo "<h1>Administrator Sales Report by Day</h1>";
	echo "<table><tr> <th>Order Date</th> <th>Total Order Amount</th> </tr>";
	$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	$time = $row['orderDate']->format('Y-m-d');
	$sum = $row['totalAmount'];
	$ordertime = "";
	while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
		$ordertime = $row['orderDate']->format('Y-m-d');
		if ($ordertime == $time)
			$sum = $sum + $row['totalAmount'];
		else{
			echo("<tr class=orderAdministrator><td>" . $time . "</td><td>" . $sum . "</td><td></tr>");
			$time = $ordertime;
			$sum = $row['totalAmount'];
		}
	}
	echo("<tr class=orderAdministrator><td>" . $time . "</td><td>" . $sum . "</td><td></tr>");
	echo "</table>";
	
	
/** Display customer information **/
	$sql = "SELECT firstName, lastName, email FROM customer";
	$stmt = sqlsrv_query($conn, $sql);
	if($stmt === false)
	{
		die(print_r( sqlsrv_errors(), true));
	}
	
	echo "<h1>List of Customers</h1>";
	echo "<table><tr> <th>First Name</th> <th>Last Name</th> <th>Email Address</th> </tr>";
	while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
		echo("<tr class=orderAdministrator><td>" . $row['firstName']. "</td><td>" . $row['lastName'] . "</td><td>".$row['email']."</td></tr>");
	}
	echo "</table>";

sqlsrv_free_stmt($stmt);
@sqlsrv_close($con);
?>
</body>
</html>