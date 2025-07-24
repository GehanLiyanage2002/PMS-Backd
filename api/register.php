<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


require_once '../config/db.php';

$data = json_decode(file_get_contents("php://input"));

if (
    empty($data->name) ||
    empty($data->email) ||
    empty($data->password) ||
    empty($data->role)
) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

$name = htmlspecialchars(strip_tags($data->name));
$email = htmlspecialchars(strip_tags($data->email));
$password = $data->password;
$role = $data->role;

if (!in_array($role, ['member', 'manager'])) {
    echo json_encode(["success" => false, "message" => "Invalid user role."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
    (!str_ends_with($email, '@std.uwu.ac.lk') && !str_ends_with($email, '@uwu.ac.lk'))) {
    echo json_encode(["success" => false, "message" => "Email must be a valid UWU email."]);
    exit;
}

if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    echo json_encode(["success" => false, "message" => "Weak password. Must contain at least 8 characters, 1 uppercase letter, and 1 digit."]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword, $role]);
    echo json_encode(["success" => true, "message" => "Registration successful."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Email already exists."]);
}
?>
