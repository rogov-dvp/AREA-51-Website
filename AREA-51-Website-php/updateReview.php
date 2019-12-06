<?php
session_start();


	$user = "";
    // Display user name that is logged in (or nothing if not logged in)
	if(isset($_SESSION['authenticatedUser'])){
		if($_SESSION['authenticatedUser'] != null){
			$user = $_SESSION['authenticatedUser'];
			echo "<h1>Hello customer ". $user."</h1>";
			echo "<a href=logout.php><span>Log out</span></a>";
			
		}else{
			echo "<a href=login.php><span>Login</span></a>";
		}
	}
	else{
		echo "<a href=login.php><span>Login</span></a>";
	}
	echo "</div>";



include 'include/db_credentials.php';
//connection
$con = sqlsrv_connect ($server, $connectionInfo);
if ( $con == true ){
	echo '<script>console.log("Connection established")</script>';	
} else {
	echo '<script>console.log("Connection not established")</script>';
	die( print_r( sqlsrv_errors(), true));
}

$productId = $_SESSION['productId'];
// if( $productId !== 2) {
// 	echo $productId;
// 	die( print_r( sqlsrv_errors(), true));
// }

//custId
$sql = "SELECT customerId FROM customer WHERE userid= ?";
$pstmt = sqlsrv_query($con, $sql, array($user));
if( $pstmt === false ) {
	 die( print_r( sqlsrv_errors(), true));
}
$exist = sqlsrv_fetch_array( $pstmt, SQLSRV_FETCH_ASSOC);
	$custId = $exist['customerId'];


if(isset($_GET['description-new'])) {
	$sql = "INSERT INTO review(reviewDate,customerId,productId,reviewComment) VALUES (?,?,?,?)";
	$pstmt = sqlsrv_query($con,$sql,array(date("Y-m-d H:i:s"),$custId,$productId,$_GET['description-new']));
	$idk = sqlsrv_fetch_array( $pstmt, SQLSRV_FETCH_ASSOC);
	header('Location: product.php?id=' . $productId);        
}
if(isset($_GET['description-old'])) {
	$sql = "UPDATE review SET reviewDate=?, reviewComment=? WHERE customerId=? AND productId=?";
	$pstmt = sqlsrv_query($con,$sql,array(date("Y-m-d H:i:s"),$_GET['description-old'],$custId,$productId));
	$idk = sqlsrv_fetch_array( $pstmt, SQLSRV_FETCH_ASSOC);

	header('Location: product.php?id=' . $productId);          
}


?>