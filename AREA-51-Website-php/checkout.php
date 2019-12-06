<!DOCTYPE html>
<html>
<head>
        <title>AREA 51 Gift Store</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="style/style.css"/>
<title>Gift Store CheckOut Line</title>
</head>
<body>

<h3><a href="index.php">Area 51 Gift Store</a></h3>

<h2>Enter your customer id to complete the transaction:</h2>

<form method="post" action="order.php">
<input type="text" name="customerId" size="50" id="input-primary">
<h2>Enter your password to confirm the transcation</h2>
<input type="password" name="password" size="50" id="input-secondary">
<br>
<input type="submit" value="Submit" class="btn btn-primary">
<input type="reset" value="Reset" class="btn btn-secondary">
</form>



</body>
</html>

