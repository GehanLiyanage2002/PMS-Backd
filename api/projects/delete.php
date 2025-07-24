<?php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Project ID missing"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true, "message" => "Project deleted."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Failed to delete project."]);
}
