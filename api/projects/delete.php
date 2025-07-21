<?php
require_once '../../config/db.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"));
$projectId = $data->id ?? null;

if (!$projectId) {
    echo json_encode(["success" => false, "message" => "Project ID required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);

    echo json_encode(["success" => true, "message" => "Project deleted successfully"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Failed to delete project"]);
}
