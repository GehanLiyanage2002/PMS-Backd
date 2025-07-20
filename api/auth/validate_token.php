<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../../includes/jwt_helper.php';

$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    echo json_encode(["success" => false, "message" => "Authorization header missing."]);
    exit;
}

$token = str_replace("Bearer ", "", $headers['Authorization']);

try {
    $decoded = decodeJWT($token);
    echo json_encode(["success" => true, "data" => $decoded->data]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Invalid or expired token."]);
}
?>
