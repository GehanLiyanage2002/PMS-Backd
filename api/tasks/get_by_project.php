<?php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    echo json_encode(["success" => false, "message" => "Missing project_id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, title, status FROM project_tasks WHERE project_id = ? ORDER BY created_at DESC");
    $stmt->execute([$project_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "tasks" => $tasks]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
