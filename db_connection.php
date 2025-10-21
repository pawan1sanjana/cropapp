<?php
$servername = "localhost";
$username = "nav_user";
$password = "yourpassword";
$dbname = "crop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
?>
