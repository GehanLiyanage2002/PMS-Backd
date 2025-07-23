<?php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"));

if (
    empty($data->id) ||
    empty($data->title) ||
    empty($data->description) ||
    empty($data->start_date) ||
    empty($data->end_date)
) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?");
    $stmt->execute([$data->title, $data->description, $data->start_date, $data->end_date, $data->id]);

    $updated = $stmt->rowCount();
    if ($updated) {
        echo json_encode(["success" => true, "message" => "Project updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or invalid project ID"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Failed to update project: " . $e->getMessage()]);
}
