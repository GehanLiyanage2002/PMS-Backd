<?php
require_once '../../config/db.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_GET['id'])) {
  echo json_encode(["success" => false, "message" => "Task ID missing"]);
  exit;
}

$id = $_GET['id'];

try {
  $stmt = $pdo->prepare("SELECT id, title, status FROM project_tasks WHERE id = ?");
  $stmt->execute([$id]);
  $task = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($task) {
    echo json_encode(["success" => true, "task" => $task]);
  } else {
    echo json_encode(["success" => false, "message" => "Task not found"]);
  }
} catch (Exception $e) {
  echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
