<?php

$servername = "localhost";
$username = "root";
$password = "sofiatoseef";
$dbname = "streamline_pos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$total = $_POST["form_total"];

$orderdata = $_POST["form_orderdata"];

$email = $_POST["email"];

if ( strlen($email) > 0 ) {

$message = "Your total order price is £" . $total . "\n\n Here are your order details \n\n " . $orderdata;

// Send
// mail($email, 'Here is the receipt for your order', $message);
    
}

$stmt = $conn->prepare("INSERT INTO Orders (total, orderdata, email) VALUES (?, ?, ?)");
$stmt->bind_param("dss", $db_total, $db_orderdata, $db_email);

$db_total = $total;
$db_orderdata = $orderdata;
$db_email = $email;

$stmt->execute();
$stmt->close();


$conn->close();

header("Location: pos.php");

?>
