<?php
include 'config.php'; // your database connection

$action = $_REQUEST['action'] ?? '';

switch ($action) {

  // CREATE
  case 'create':
    $farmer_id = $_POST['farmer_id'];
    $farmer_name = $_POST['farmer_name'];
    $supply_date = $_POST['supply_date'];
    $quantity = $_POST['quantity'];
    $level = $_POST['level'];

    $sql = "INSERT INTO supply (farmer_id, farmer_name, supply_date, quantity, level)
            VALUES ('$farmer_id', '$farmer_name', '$supply_date', '$quantity', '$level')";
    echo $conn->query($sql) ? json_encode(['success'=>true, 'message'=>'Supply added']) : json_encode(['success'=>false, 'message'=>$conn->error]);
    break;

  // READ
  case 'read':
    if (isset($_GET['id'])) {
      $id = intval($_GET['id']);
      $res = $conn->query("SELECT * FROM supply WHERE id=$id");
      echo json_encode($res->fetch_assoc());
    } else {
      $res = $conn->query("SELECT * FROM supply ORDER BY id DESC");
      $rows = [];
      while ($r = $res->fetch_assoc()) $rows[] = $r;
      echo json_encode($rows);
    }
    break;

  // UPDATE
  case 'update':
    $id = intval($_POST['id']);
    $farmer_id = $_POST['farmer_id'];
    $farmer_name = $_POST['farmer_name'];
    $supply_date = $_POST['supply_date'];
    $quantity = $_POST['quantity'];
    $level = $_POST['level'];

    $sql = "UPDATE supply SET 
              farmer_id='$farmer_id', 
              farmer_name='$farmer_name', 
              supply_date='$supply_date', 
              quantity='$quantity', 
              level='$level' 
            WHERE id=$id";
    echo $conn->query($sql) ? json_encode(['success'=>true,'message'=>'Supply updated']) : json_encode(['success'=>false,'message'=>$conn->error]);
    break;

  // DELETE
  case 'delete':
    $id = intval($_GET['id']);
    $sql = "DELETE FROM supply WHERE id=$id";
    echo $conn->query($sql) ? json_encode(['success'=>true,'message'=>'Supply deleted']) : json_encode(['success'=>false,'message'=>$conn->error]);
    break;

  default:
    echo json_encode(['success'=>false,'message'=>'No valid action specified.']);
    break;
}

$conn->close();
?>
