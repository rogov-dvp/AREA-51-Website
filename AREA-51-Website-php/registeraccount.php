<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="style/style.css"/>
<title>Register Account</title>
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
    if(session_status()==2){} else {session_start();}
	if(isset($_SESSION['registerMessage']))
		if ($_SESSION['registerMessage']  != null)	
			echo ("<h2>" . $_SESSION['registerMessage'] . "</h2>");
?>

<form method="POST" action="registeraccount.php">

	<h2>User name:
		<input type="text" name="username"  size=10 maxlength=10>
	</h2>
	<h2>
		Fist name:
		<input type="text" name="first-name" size=10 maxlength="10">
	</h2>
	<h2>
		Last name:
		<input type="text" name="last-name" size=10 maxlength="10">
	</h2>
	<h2>Password:
		<input type="password" name="password" size=10 maxlength="10">
	</h2>
	<h2>
		Email:
		<input type="text" name="email" size=10 maxlength="10">
	</h2>
	<h2>
		Phonenum (optional):
		<input type="text" name="phonenum" size=10 maxlength="10">
	</h2>
	<h2>
		Address (optional):
		<input type="text" name="address" size=10 maxlength="10">
	</h2>
	<h2>
		City (optional):
		<input type="text" name="city" size=10 maxlength="10">
	</h2>
	<h2>
		State (optional):
		<input type="text" name="state" size=10 maxlength="10">
	</h2>
	<h2>
		Postal code (optional):
		<input type="text" name="postalcode" size=10 maxlength="10">
	</h2>
	<h2>
		Country (optional):
		<input type="text" name="country" size=10 maxlength="10">
	</h2>
	
	<input type="submit" class="btn btn-primary" name="Submit" value="Sumbit">
	
</form>

<?php
	include 'include/db_credentials.php';
	
	if(isset($_POST['Submit'])){
		$user = $_POST['username'];
		$firstname = $_POST['first-name'];
		$lastname = $_POST['last-name'];
		$pw = $_POST['password'];
		$email = $_POST['email'];
		$pnum = $_POST['phonenum'];
		$add = $_POST['address'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$pcode = $_POST['postalcode'];
		$country = $_POST['country'];
		
		/** Check if user entered necessory information in correct form **/
		if ($user == null || $pw == null || $firstname == null || $lastname==null || $email==null){
			$_SESSION["registerMessage"] = "Not enough infomation!";
			header('Location: registeraccount.php');
		}else if ((strlen($user) == 0) || (strlen($pw) == 0) || (strlen($firstname) == 0) || (strlen($lastname) == 0) || (strlen($email) == 0) )
		{
			$_SESSION["registerMessage"] = "Name has to be a string!";
			header('Location: registeraccount.php');
		}else if(strpos($email, "@") === false || strpos($email, ".com") === false  ){
			$_SESSION["registerMessage"] = "Email format is not valid.";
			header('Location: registeraccount.php');
		}
		else{
		
			/** Connect to database **/
			$con = sqlsrv_connect ($server, $connectionInfo);
			if ( $con == true ){
				echo '<script>console.log("Connection established")</script>';	
			} else {
				echo '<script>console.log("Connection not established")</script>';
				die( print_r( sqlsrv_errors(), true));
			}
			
			/** Check if the email address is registered **/
			$sql = "SELECT firstName FROM customer WHERE email = ?";
			$params = array($email);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
			if(($emailcheck = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) !== null){
				$_SESSION["registerMessage"] = "This email is registered.";
				header('Location: registeraccount.php');
			}
			else{				
				/** Check if the phone number is registered **/
				if(isset($pnum)){
					$sql = "SELECT firstName FROM customer WHERE phonenum = ?";
					$params = array($pnum);
					$stmt = sqlsrv_query( $con, $sql, $params);
					if( $stmt === false ) {
						 die( print_r( sqlsrv_errors(), true));
					}
					if(($phonenumcheck = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) !== null){
						$_SESSION["registerMessage"] = "This phone number is registered.";
						header('Location: registeraccount.php');
					}else{
						logininformation($con, $user,$firstname,$lastname,$pw,$email,$pnum,$add,$city,$state,$pcode,$country);
					}
				}else{
					logininformation($con, $user,$firstname,$lastname,$pw,$email,$pnum,$add,$city,$state,$pcode,$country);
				}
				
				header('Location: registeraccount.php');
			}
			
			sqlsrv_free_stmt($stmt);
			sqlsrv_close($con);
		}
	}
	
	
	function logininformation($con, $user,$firstname,$lastname,$pw,$email,$pnum,$add,$city,$state,$pcode,$country){
		/** Update customer table with necessory information **/
		$sql ="INSERT INTO customer (userid, firstName, lastName, password, email) VALUES (?,?,?,?,?)";
		$params = array($user, $firstname, $lastname, $pw, $email);
		$stmt = sqlsrv_query( $con, $sql, $params);
		if( $stmt === false ) 
			 die( print_r( sqlsrv_errors(), true));
		
		
		/** Update customer data with optional information **/
		$sql ="UPDATE customer SET phonenum = ?, address=?, city=?, state=?, postalCode=?, country=? WHERE email = ?";
		$params = array($pnum, $add, $city, $state, $pcode, $country, $email);
		$stmt = sqlsrv_query( $con, $sql, $params);
		if( $stmt === false ) 
			 die( print_r( sqlsrv_errors(), true));			
		
		/** Retrieve customer ID **/
		$sql = "SELECT customerId FROM customer WHERE email = ?";
		$pstmt = sqlsrv_query($con,$sql,array($email));
		if( $pstmt === false ) {
		 die( print_r( sqlsrv_errors(), true));
		}
		$custId = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);	
		$custId = $custId['customerId'];
		
		$_SESSION["registerMessage"] = "Successfully registered! Have fun shopping! \n Your customer ID is: ".$custId." Please keep your customer ID down for furture orders.";
	}
	
?>

</body>
</html>