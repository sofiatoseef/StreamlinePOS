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


$stmt = $conn->prepare("INSERT INTO Orders (total, orderdata) VALUES (?, ?)");
$stmt->bind_param("ds", $db_total, $db_orderdata);

$db_total = $total;
$db_orderdata = $orderdata;

$stmt->execute();
$stmt->close();


$conn->close();

header("Location: pos.php");

?>
