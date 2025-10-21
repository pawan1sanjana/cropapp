<?php
header('Content-Type: application/json');
session_start();

// Optional login check
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// DB connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "crop";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Filter by Reg No (user_id) if provided
if (isset($_GET['user_ids'])) {
    $user_ids = explode(',', $_GET['user_ids']);
    $user_ids = array_map(function($v) use ($conn) {
        return "'" . $conn->real_escape_string(trim($v)) . "'";
    }, $user_ids);
    $user_ids_list = implode(',', $user_ids);
    $sql = "SELECT id, user_id, name, address, latitude, longitude, phone FROM users WHERE user_id IN ($user_ids_list)";
} else {
    $sql = "SELECT id, user_id, name, address, latitude, longitude, phone FROM users";
}

$result = $conn->query($sql);
$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'id' => (int)$row['id'],
            'user_id' => $row['user_id'],
            'name' => $row['name'],
            'address' => $row['address'],
            'latitude' => (float)$row['latitude'],
            'longitude' => (float)$row['longitude'],
            'tel' => $row['phone']
        ];
    }
}

echo json_encode($users);
$conn->close();
?>
