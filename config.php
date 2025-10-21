<?php
$servername = "localhost";
$username = 'root';   // your MySQL username
$password = 'root'; // your MySQL password
$dbname = "crop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
