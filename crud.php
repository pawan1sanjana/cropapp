<?php
include 'config.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {

  // CREATE
  case 'create':
    $user_id = $_POST['user_id'];
    $route = $_POST['route'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $lat = $_POST['latitude'];
    $lon = $_POST['longitude'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO users (user_id, route, name, address, latitude, longitude, phone)
           VALUES ('$user_id', '$route', '$name', '$address', '$lat', '$lon', '$phone')";

    echo $conn->query($sql) ? "User added" : "Error adding user: " . $conn->error;
    break;

  // READ
  case 'read':
    if (isset($_GET['id'])) {
      $id = intval($_GET['id']);
      $res = $conn->query("SELECT * FROM users WHERE id=$id");
      echo json_encode($res->fetch_assoc());
    } else {
      $res = $conn->query("SELECT * FROM users ORDER BY id DESC");
      $rows = [];
      while ($r = $res->fetch_assoc()) $rows[] = $r;
      echo json_encode($rows);
    }
    break;

  // UPDATE
  case 'update':
    $id = intval($_POST['id']);
    $user_id = $_POST['user_id'];
    $route = $_POST['route'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $lat = $_POST['latitude'];
    $lon = $_POST['longitude'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users 
            SET user_id='$user_id', 
                route='$route',
                name='$name', 
                address='$address', 
                latitude='$lat', 
                longitude='$lon', 
                phone='$phone' 
            WHERE id=$id";

    echo $conn->query($sql) ? "User updated" : "Error updating user: " . $conn->error;
    break;

  // DELETE
  case 'delete':
    $id = intval($_GET['id']);
    $sql = "DELETE FROM users WHERE id=$id";
    echo $conn->query($sql) ? "User deleted" : "Error deleting user: " . $conn->error;
    break;

  default:
    echo "No valid action specified.";
    break;
}

$conn->close();
?>





