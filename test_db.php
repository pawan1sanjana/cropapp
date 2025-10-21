<?php
$servername = "localhost";
$username = "nav_user"; // same as your actual username
$password = "yourpassword"; // same as your actual password
$dbname = "crop";

// Try to connect
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Database connected successfully!";
$conn->close();
?>
