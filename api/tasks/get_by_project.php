<?php
require_once '../../config/db.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_GET['project_id'])) {
  echo json_encode(["success" => false, "message" => "Project ID missing"]);
  exit;
}

$project_id = $_GET['project_id'];

try {
  $stmt = $pdo->prepare("SELECT id, title, status FROM project_tasks WHERE project_id = ?");
  $stmt->execute([$project_id]);
  $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    "success" => true,
    "tasks" => $tasks
  ]);
} catch (Exception $e) {
  echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
