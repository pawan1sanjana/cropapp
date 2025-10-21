<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Use MD5 for now (same as DB insert)
$sql = "SELECT * FROM accounts WHERE username='$username' AND password=MD5('$password')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $_SESSION['username'] = $username;
  header("Location: dashboard.html");
} else {
  echo "<script>alert('Invalid username or password'); window.location.href='index.html';</script>";
}
$conn->close();
?>

