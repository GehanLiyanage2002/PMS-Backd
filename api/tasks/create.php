<?php
require_once '../../config/db.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Read raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['project_id'], $data['title'], $data['status'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields: project_id, title, or status"
    ]);
    exit;
}

$project_id = $data['project_id'];
$title = $data['title'];
$status = $data['status'];

try {
    $stmt = $pdo->prepare("INSERT INTO project_tasks (project_id, title, status) VALUES (?, ?, ?)");
    $stmt->execute([$project_id, $title, $status]);

    echo json_encode([
        "success" => true,
        "message" => "Task created successfully"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
