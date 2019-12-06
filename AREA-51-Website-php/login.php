<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="style/style.css"/>
<title>Login Screen</title>
</head>
<body>


<div class="ribbon">
		<a href="index.php"><span>Area 51 Gift Store</span></a>
</div>

<div style="margin:0 auto;text-align:center;display:inline">

<h1>Please Login to System</h1>

<?php 
    session_start();
	if(isset($_SESSION['loginMessage']))
		if ($_SESSION['loginMessage']  != null)	
			echo ("<h2>" . $_SESSION['loginMessage'] . "</h2>");
?>

<br>

<form name="MyForm" method="post" action="validateLogin.php" id="login-form">
	<table style="display:inline">
		<tr>
			<td><div align="right">Username:</div></td>
			<td><input type="text" name="username"  size=10 maxlength=10></td>
		</tr>
		<tr>
			<td><div align="right">Password:</div></td>
			<td><input type="password" name="password" size=10 maxlength="10"></td>
		</tr>
	</table>
	
	<input class="btn btn-primary" type="submit" name="Submit2" value="Log In">
	<br/>
	<input class="btn btn-secondary" type="submit" name="Forgetpassword" value="Forget password">
	<input class="btn btn-secondary" type="submit" name="Register" value="Register an account">
</form>

</div>

</body>
</html>

