<?php
// /PMS-Backd/api/projects/get_all.php
require_once '../../config/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$managerId = $_GET['manager_id'] ?? null;

if (!$managerId) {
    echo json_encode(["success" => false, "message" => "Manager ID missing"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT p.*,
        (SELECT COUNT(*) FROM project_tasks WHERE project_id = p.id AND status = 'TODO') as todo_count,
        (SELECT COUNT(*) FROM project_tasks WHERE project_id = p.id AND status = 'In Progress') as in_progress_count,
        (SELECT COUNT(*) FROM project_tasks WHERE project_id = p.id AND status = 'Testing') as testing_count,
        (SELECT COUNT(*) FROM project_tasks WHERE project_id = p.id AND status = 'Done') as done_count
    FROM projects p
    WHERE p.manager_id = ?
    ORDER BY p.created_at DESC");

    $stmt->execute([$managerId]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "projects" => $projects]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
