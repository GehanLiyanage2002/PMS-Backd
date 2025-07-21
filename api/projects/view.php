<?php
require_once '../../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$projectId = $_GET['id'] ?? null;

if (!$projectId) {
    echo json_encode(["success" => false, "message" => "Project ID is required"]);
    exit;
}

// Fetch project info
$projectStmt = $pdo->prepare("SELECT p.*, u.name as manager_name
    FROM projects p
    JOIN users u ON p.manager_id = u.id
    WHERE p.id = ?");
$projectStmt->execute([$projectId]);
$project = $projectStmt->fetch();

if (!$project) {
    echo json_encode(["success" => false, "message" => "Project not found"]);
    exit;
}

// Fetch members
$memberStmt = $pdo->prepare("SELECT enrollment_no, name, email FROM project_members WHERE project_id = ?");
$memberStmt->execute([$projectId]);
$members = $memberStmt->fetchAll();

// Fetch task status summary
$statusCounts = [
    'TODO' => 0,
    'In Progress' => 0,
    'Testing' => 0,
    'Done' => 0
];

$statusStmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM project_tasks WHERE project_id = ? GROUP BY status");
$statusStmt->execute([$projectId]);
while ($row = $statusStmt->fetch()) {
    $statusCounts[$row['status']] = (int) $row['count'];
}

// Final response
echo json_encode([
    "success" => true,
    "project" => $project,
    "members" => $members,
    "status_summary" => $statusCounts
]);
