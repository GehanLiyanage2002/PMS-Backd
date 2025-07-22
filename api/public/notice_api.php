<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// include raw PDO from db.php
require 'db.php';
require 'Notice.php';

// wrapper Database class without touching db.php
class Database {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    public function connect() {
        return $this->conn;
    }
}

$db = (new Database($pdo))->connect();
$notice = new Notice($db);

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $input['action'] ?? '';

    if ($action === 'add' && !empty($input['title']) && !empty($input['description'])) {
        $newId = $notice->add($input['title'], $input['description']);
        echo json_encode(['success' => $newId > 0, 'id' => $newId]);
    } elseif ($action === 'update' && !empty($input['id'])) {
        $count = $notice->update($input['id'], $input['title'], $input['description']);
        echo json_encode(['success' => $count > 0]);
    } elseif ($action === 'delete' && !empty($input['id'])) {
        $count = $notice->delete($input['id']);
        echo json_encode(['success' => $count > 0]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action or missing data']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $notice->getAll();
    echo json_encode($data);
} else {
    echo json_encode(['success' => false, 'error' => 'Unsupported method']);
}
