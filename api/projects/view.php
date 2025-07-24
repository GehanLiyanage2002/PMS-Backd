<?php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Project ID missing"]);
    exit;
}

try {
    // Fetch only viewable data
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.description, p.start_date, p.end_date, u.name AS manager_name
        FROM projects p
        JOIN users u ON p.manager_id = u.id
        WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        echo json_encode(["success" => false, "message" => "Project not found"]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "project" => $project
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
