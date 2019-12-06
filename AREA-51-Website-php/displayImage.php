<html>
<head>
<style>

    body{
        overflow: none;
        background-color:#474235;
    }

    div.box {
        position: relative;
        width:100%;
        height:100%;
        margin: auto;
    }
    img.image {
        display: block;
    max-width:600px;
    position: relative;
        margin: auto;
        top:50%;
        margin-top: -15%;
    
    }
    
    div.text1{
	text-align: center;
    }
    h1.text {
	font-size: 50px;
    background: red; /* For browsers that do not support gradients */
    background: -webkit-linear-gradient(left, orange , yellow, green, cyan, blue, violet); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(right, orange, yellow, green, cyan, blue, violet); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(right, orange, yellow, green, cyan, blue, violet); /* For Firefox 3.6 to 15 */
    background: linear-gradient(to right, orange , yellow, green, cyan, blue, violet);
    }


</style>
</head>
<body class="color">

<?php

include 'include/db_credentials.php';

/** Obtain product ID **/
session_start();
if (isset($_GET['id'])){
	$id = $_GET['id'];
} else{ 	// No products currently in list.  Create a list.
    echo "failed to retrieve Id";
    $id = "";
} 

//Connection
$con = sqlsrv_connect ($server, $connectionInfo);

if ( $con == true ){
    echo '<script>console.log("Connection established")</script>';	
} else {
    echo '<script>console.log("Connection not established")</script>';
    die( print_r( sqlsrv_errors(), true));
}

// TODO: Modify SQL to retrieve productImage given productId
$sql = "SELECT productImageURL FROM product WHERE productId = ?";
$pstmt = sqlsrv_query($con, $sql, array($id));
if( $pstmt === false ) {
     die( print_r( sqlsrv_errors(), true));
    }
if ($rst = sqlsrv_fetch_array( $pstmt, SQLSRV_FETCH_ASSOC)) 
{
    // header("Content-Type: image/" . exif_imagetype($rst['productImageURL']) );
    // echo "hehexd";

    echo "<div class='box'><img src='" . $rst['productImageURL'] . "'class='image'></div>";  
    if($id == 8) {
	echo "<div class='text1'><h1 class='text'>ALL GLORY TO THE HYPNOTOAD</h1></div>";
    }
	
}


sqlsrv_free_stmt($pstmt);
sqlsrv_close($con);
?>
</body>
</html>
