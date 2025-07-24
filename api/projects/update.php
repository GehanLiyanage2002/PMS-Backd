<?php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? null;

if (!$id || empty($data->title)) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

$title = htmlspecialchars(strip_tags($data->title));
$desc = htmlspecialchars(strip_tags($data->description ?? ''));
$start = $data->start_date ?? null;
$end = $data->end_date ?? null;

try {
    $stmt = $pdo->prepare("
        UPDATE projects
        SET title = ?, description = ?, start_date = ?, end_date = ?
        WHERE id = ?
    ");
    $stmt->execute([$title, $desc, $start, $end, $id]);

    echo json_encode(["success" => true, "message" => "Project updated"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Failed to update project"]);
}
