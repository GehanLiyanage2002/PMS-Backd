<?php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->title) || empty($data->manager_id)) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

$title = htmlspecialchars(strip_tags($data->title));
$desc = htmlspecialchars(strip_tags($data->description ?? ''));
$manager_id = $data->manager_id;

try {
    $stmt = $pdo->prepare("INSERT INTO projects (manager_id, title, description) VALUES (?, ?, ?)");
    $stmt->execute([$manager_id, $title, $desc]);

    echo json_encode(["success" => true, "message" => "Project created successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Failed to create project."]);
}
