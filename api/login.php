<?php
// /uwu_pms/api/login.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../config/db.php';
require_once '../includes/jwt_helper.php';

$data = json_decode(file_get_contents("php://input"));
if (!$data || !$data->email || !$data->password) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$email = $data->email;
$password = $data->password;

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $token = createJWT([
        "id" => $user['id'],
        "email" => $user['email'],
        "role" => $user['role']
    ]);

    echo json_encode([
        "success" => true,
        "token" => $token,
        "user" => [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "role" => $user['role']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}

?>
