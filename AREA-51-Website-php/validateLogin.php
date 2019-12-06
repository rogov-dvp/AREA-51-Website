<?php 
    session_start(); 

	if(isset($_POST['Forgetpassword']))
		header('Location: forgetpassword.php');
	else if(isset($_POST['Register']))
		header('Location: registeraccount.php');          
	else{
	
		$authenticatedUser = validateLogin();
		
		if ($authenticatedUser != null)
			header('Location: index.php');     		// Successful login
		else
			header('Location: login.php');	             // Failed login - redirect back to login page with a message     
    }
	
	function validateLogin()
	{	  
	    $user = $_POST["username"];	 
	    $pw = $_POST["password"];
		$retStr = null;

		if ($user == null || $pw == null)
			return null;
		if ((strlen($user) == 0) || (strlen($pw) == 0))
			return null;

		include 'include/db_credentials.php';
		$con = sqlsrv_connect($server, $connectionInfo);
		
		// TODO: Check if userId and password match some customer account. If so, set retStr to be the username.
		$sql = "SELECT password FROM customer WHERE userId = ?";
		$params = array($user);
		$stmt = sqlsrv_query($con, $sql, $params);
		if( $stmt === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}
		$valid = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
		if($valid['password'] == $pw)
			$retStr = $user;
		else
			$retStr = null;
		
		sqlsrv_free_stmt($pstmt);
		sqlsrv_close($con);
		
		if ($retStr != null)
		{	$_SESSION["loginMessage"] = null;
	       	$_SESSION["authenticatedUser"] = $user;
		}
		else
		    $_SESSION["loginMessage"] = "Could not connect to the system using that username/password.";

		return $retStr;
	}
sqlsrv_free_stmt($stmt);
sqlsrv_close($con);

?>