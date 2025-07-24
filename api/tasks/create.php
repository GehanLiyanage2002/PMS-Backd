<?php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->title) || empty($data->project_id)) {
    echo json_encode(["success" => false, "message" => "Missing title or project ID"]);
    exit;
}

$title = htmlspecialchars(strip_tags($data->title));
$status = htmlspecialchars(strip_tags($data->status ?? 'TODO'));
$project_id = (int)$data->project_id;

try {
    $stmt = $pdo->prepare("INSERT INTO project_tasks (project_id, title, status) VALUES (?, ?, ?)");
    $stmt->execute([$project_id, $title, $status]);
    echo json_encode(["success" => true, "message" => "Task created successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Failed to create task."]);
}
