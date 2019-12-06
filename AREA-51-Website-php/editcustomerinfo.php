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

<?php 
    include 'include/db_credentials.php';
?>
<form method="POST" action="editcustomerinfo.php">


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
    echo ("<tr><th>First Name</th><td><input type='text' name='fname' placeholder='".$firstName."'></td></tr>");
    echo ("<tr><th>Last Name</th><td><input type='text' name='lname' placeholder='".$lastName."'></td></tr>");
    echo ("<tr><th>Email</th><td><input type='text' name='email' placeholder='".$email."'></td></tr>");
    echo ("<tr><th>Phone</th><td><input type='text' name='pnum' placeholder='".$phonenum."'></td></tr>");
    echo ("<tr><th>Address</th><td><input type='text' name='add' placeholder='".$address."'></td></tr>");
    echo ("<tr><th>City</th><td><input type='text' name='city' placeholder='".$city."'></td></tr>");
    echo ("<tr><th>State</th><td><input type='text' name='state' placeholder='".$state."'></td></tr>");
    echo ("<tr><th>Postal Code</th><td><input type='text' name='pcode' placeholder='".$postalCode."'></td></tr>");
    echo ("<tr><th>Country</th><td><input type='text' name='country' placeholder='".$country."'></td></tr>");
    echo ("<tr><th>User ID</th><td>".$userid."</td></tr>");
	echo ("<tr><td colspan=2><input type='submit' name='Submit' value='Confirm'  class='btn btn-primary'></td></tr>");
	
}

echo "</table>";
 
// Make sure to close connection 
sqlsrv_free_stmt($pstmt);
sqlsrv_close($con);

?>

</form>

<?php
	if(isset($_POST['Submit'])){
		
		/** Obtain updated information **/
		if(session_status()==2){}else{session_start();}
		$fn = $_POST['fname'];
		$ln = $_POST['lname'];
		$em = $_POST['email'];
		$pn = $_POST['pnum'];
		$add = $_POST['add'];
		$ct = $_POST['city'];
		$st = $_POST['state'];
		$contry = $_POST['country'];
		$pc = $_POST['pcode'];
		
		/** Connect to database **/
		include 'include/db_credentials.php';
		$con = sqlsrv_connect ($server, $connectionInfo);
		if ( $con == true ){
			echo '<script>console.log("Connection established")</script>';	
		} else {
			echo '<script>console.log("Connection not established")</script>';
			die( print_r( sqlsrv_errors(), true));
		}
		
		if($fn != null){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET firstName=? WHERE userId = ?";
			$params = array($fn, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		
		if($ln != null){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET lastName=? WHERE userId = ?";
			$params = array($ln, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		
		if($em != null){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET email=? WHERE userId = ?"; //trigger on update email
			$params = array($em, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		
		if($pn != null){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET phonenum=? WHERE userId = ?";
			$params = array($pn, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		
		if($add != null){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET address=? WHERE userId = ?";
			$params = array($add, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		
		if($ct != null){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET city=? WHERE userId = ?";
			$params = array($ct, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		
		if($st != null){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET state=? WHERE userId = ?";
			$params = array($st, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		
		if(strlen($country) != 0){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET country=? WHERE userId = ?";
			$params = array($country, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		if(strlen($pc) != 0){
			/** Update customer data with new info **/
			$sql ="UPDATE customer SET postalCode=? WHERE userId = ?";
			$params = array($pc, $user);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
		}
		header ('Location: customer.php');
	}

?>

</body>