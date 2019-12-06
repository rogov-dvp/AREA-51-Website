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

<form method="POST" action="forgetpassword.php">
	<h2>Enter your email to get your password reset</h2>
	<h2>A code will be sent to your email address (since we can't send emails, the validation code will show up below)</h2>
<?php
	session_start();
	
	if(isset($_SESSION['validCode'])){ //when user is at the validation code page	
		/** Check user input for validation code **/
		if(isset($_POST['userValideCode'])){
			$_SESSION['validMessage'] = ($_POST['userValideCode'] == $_SESSION['validCode'])? 4:2;
		}
		/** switch cases for status of entered validation code **/
		switch ($_SESSION['validMessage']){
			case 2: //the case when user entered a wrong validation code
				echo ("<h2>The validation code is not correct!</h2>");
				echo ("<h2>The validation code is " . $_SESSION['validCode'] . ". Please put it in the box below.</h2>");
				echo "<h3>Validation code: </h3><input type=\"text\" name=\"userValideCode\" size=30>";
				break;
			case 4: // after user entered the correct validation code
				header ('Location: resetpassword.php');
				break;
			default: //initial page for validation code
				echo ("<h2>The validation code is " . $_SESSION['validCode'] . ". Please put it in the box below.</h2>");
				echo "<h3>Validation code: </h3><input type=\"text\" name=\"userValideCode\" size=30>";
				break;
		}
		
	}else if(isset($_SESSION['validMessage'])){ // user is at validate email page
		/** Validate user email address **/
		if(isset($_POST['email'])){
			$_SESSION['validMessage'] = validateEmail()? 0:1;
		}
		/** switch cases for status of entered email address **/
		switch ($_SESSION['validMessage']){
			case 1: //the case when user entered a non-registered email
				echo ("<h2>This email is not registered.</h2>");
				echo "<h3>Email: </h3><input type='text' name='email' size=30>";
				break;
			case 0: //the case when user entered a registered email
				createRandomValidationCode();
				header('Location: forgetpassword.php');
				break;
			default: //initial page for email validation
				echo "<h3>Email: </h3><input type='text' name='email' size=30>";
		}
		
	}else{ //initial page with validMessage and validCode being null
		if(isset($_POST['email'])){
			$_SESSION['validMessage'] = validateEmail()? 0:1;
			header('Location: forgetpassword.php');
		}
		echo "<h3>Email: </h3><input type='text' name='email' size=30>";
	}
?>

<input type='submit' name='Submit' value='Submit' class='btn btn-primary'>
</form>

<?php

	function validateEmail()
	{
		if(isset($_POST['email'])){
			/** Get input email address**/
			$email = $_POST['email'];
			$_SESSION['emailaddress'] = $_POST['email'];
			
			/** Connect to database **/
			include 'include/db_credentials.php';
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
			if(($emailcheck = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) === null)
				return false;
			else
				return true;
		}else
			return false;
	}

	function createRandomValidationCode()
	{
		$random_number= mt_rand(1000, 9999);
		$_SESSION['validCode'] = $random_number;
	}

sqlsrv_free_stmt($stmt);
sqlsrv_close($con);
?>

</body>