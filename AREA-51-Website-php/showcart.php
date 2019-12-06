<!DOCTYPE html>
<html>
<head>
        <title>AREA 51 Gift Store</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="style/style.css"/>

<title>Your Shopping Cart</title>
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
			echo "<a href=logout.php><span>Hello ".$user."</span></a>";
			
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
// Get the current list of products
//session_start();
$productList = null;
if (isset($_SESSION['productList'])){
	$productList = $_SESSION['productList'];
	echo("<h1>Your Shopping Cart</h1>");
	echo("<form action=\"updatecart.php\"><table><tr><th>Product Id</th><th>Product Name</th><th>Quantity</th>");
	echo("<th>Price</th><th>Subtotal</th><th>Modification</th></tr>");

	$total =0;
	foreach ($productList as $id => $prod) {
		echo("<tr><td>". $prod['id'] . "</td>");
		echo("<td>" . $prod['name'] . "</td>");

		echo("<td align=\"center\"><input type='text' name='quantity-".$prod['id']."' size='3' placeholder='". $prod['quantity'] . "'\></td>");
		$price = $prod['price'];

		echo("<td align=\"right\">$" . number_format($price ,2) ."</td>");
		echo("<td align=\"right\">$" . number_format($prod['quantity']*$price, 2) . "</td>");
		echo("<td><a class=deletecartoption href='deletecart.php?did=".$prod['id']."'>Delete</a></td></tr>");
		echo("</tr>");
		$total = $total +$prod['quantity']*$price;
	}
	echo("<tr><td colspan=\"4\" align=\"right\"><b>Order Total</b></td><td align=\"right\">$" . number_format($total,2) ."</td></tr>");
	echo("<tr><td colspan=\"5\" align=\"right\"> <input type=\"submit\" class=\"btn btn-primary\" value=\"Update\"/></td></tr>");
	echo("</table></form>");

	echo("<h2 align=right><a href=\"checkout.php\">Check Out</a></h2>");
} else{
	echo("<H1>Your shopping cart is empty!</H1>");
}
?>
<h2 align="right"><a href="listprod.php">Continue Shopping</a></h2>
</body>

</html>

