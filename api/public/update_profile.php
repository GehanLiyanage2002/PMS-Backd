<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../../config/db.php'; // Adjust path as needed

// Accept only JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validation
if (
    empty($data['email']) || empty($data['name']) || empty($data['academicYear']) ||
    empty($data['bio'])
) {
    echo json_encode(["success" => false, "message" => "Required fields are missing."]);
    exit;
}

$email = htmlspecialchars(strip_tags($data['email']));
$name = htmlspecialchars(strip_tags($data['name']));
$year = htmlspecialchars(strip_tags($data['academicYear']));
$bio = htmlspecialchars(strip_tags($data['bio']));
$password = isset($data['password']) ? $data['password'] : null;

// Find user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found."]);
    exit;
}

// Update profile
if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET name=?, academic_year=?, bio=?, password=? WHERE email=?";
    $params = [$name, $year, $bio, $hashedPassword, $email];
} else {
    $sql = "UPDATE users SET name=?, academic_year=?, bio=? WHERE email=?";
    $params = [$name, $year, $bio, $email];
}

$update = $pdo->prepare($sql);
$success = $update->execute($params);

if ($success) {
    echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Update failed."]);
}
