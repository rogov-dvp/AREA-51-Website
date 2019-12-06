<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="style/style.css"/>
<title>Find your password</title>
</head>
<body>

<div class="ribbon">
		<a href="index.php"><span>Area 51 Gift Store</span></a>
</div>

<?php 
    session_start();
	if(isset($_SESSION['pwMessage'])){
		if ($_SESSION['pwMessage']  != null)	
			echo ("<h2>" . $_SESSION['pwMessage'] . "</h2>");
		
		if($_SESSION['pwMessage']  == "Update successful!")
			if (session_status() == PHP_SESSION_ACTIVE) { session_destroy(); }
	}
?>

<form method="POST" action="resetpassword.php">
	<h2>Enter your new email</h2>
	<input type="text" name="pw1">
	<h2>Confirm your new email</h2>
	<input type="text" name="pw2">
	<br/>
	<input type="submit" name="Submit"  value='Confirm' class='btn btn-primary'>
	
</form>

<?php
	$email = $_SESSION['emailaddress'];
	
	if(isset($_POST['Submit'])){
		/** Confirm two passwords are identical **/
		$pw1 = $_POST['pw1'];
		$pw2 = $_POST['pw2'];
		if($pw1 == $pw2){
			
			/** Connect to database **/
			include 'include/db_credentials.php';
			$con = sqlsrv_connect ($server, $connectionInfo);
			if ( $con == true ){
				echo '<script>console.log("Connection established")</script>';	
			} else {
				echo '<script>console.log("Connection not established")</script>';
				die( print_r( sqlsrv_errors(), true));
			}
			
			/** Update customer data with optional information **/
			$sql ="UPDATE customer SET password=? WHERE email = ?";
			$params = array($pw1, $email);
			$stmt = sqlsrv_query( $con, $sql, $params);
			if( $stmt === false ) 
				 die( print_r( sqlsrv_errors(), true));	
			else{
				$_SESSION['pwMessage'] = "Update successful!";
				header ('Location: resetpassword.php');
			}
		}else{
			$_SESSION['pwMessage'] = "Entered password are not the same!";
			header ('Location: resetpassword.php');
		}
	}
sqlsrv_free_stmt($stmt);
sqlsrv_close($con);
?>

</body>