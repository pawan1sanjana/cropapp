<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
include('db_connection.php'); // Reuse your connection

$sql = "SELECT 
          farmer_id,
          farmer_name,
          supply_date,
          quantity,
          level
        FROM supply_data"; // <-- Replace with your actual table name

$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

echo json_encode($data);

$conn->close();
?>
