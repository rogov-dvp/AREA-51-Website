<!DOCTYPE html>
<html>
<head>
        <title>AREA 51 Gift Store</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="style/style.css"/>

</head>

<body>
<?php 
    session_start();
    // TODO: Display user name that is logged in (or nothing if not logged in)
	if(isset($_SESSION['authenticatedUser'])){
		if($_SESSION['authenticatedUser'] != null){
			$user = $_SESSION['authenticatedUser'];
			echo "<h1 align=center>Hello customer ". $user."</h1>";
			echo "<h2 align=center><a href=logout.php class='indexPage login-btn'>Log out</a></h2>";
		} 
		else{
			echo "<h2 align=center><a href=login.php class='indexPage login-btn'>Login</a></h2>";
		}
	}
	else{
			echo "<h2 align=center><a href=login.php class='indexPage login-btn'>Login</a></h2>";
		}
?>
 <img id="alienGif" src="images/alien.gif"/>
<div>
	<h1 align="center">Welcome to</h1>
	<img style="display:block; margin-left:auto; margin-right:auto;" src="images/logo.gif" alt="Area 51 Gift Store"/>
				
</div>

<h2 align="center"><a href="listprod.php" class='indexPage'>Begin Shopping</a></h2>

<h2 align="center"><a href="listorder.php" class='indexPage'>List All Orders</a></h2>

<h2 align="center"><a href="customer.php" class='indexPage'>Customer Info</a></h2>

<h2 align="center"><a href="admin.php" class='indexPage'>Administrators</a></h2>


</body>
</head>


