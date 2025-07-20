<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = 'UwuPms2025@#SecureJWTKey!98ZxCVbnm'; // Strong secret key

function createJWT($data) {
    global $secretKey;
    $payload = [
        "iss" => "uwu_pms",
        "aud" => "uwu_user",
        "iat" => time(),
        "exp" => time() + (60 * 60 * 24),
        "data" => $data
    ];
    return JWT::encode($payload, $secretKey, 'HS256');
}

function decodeJWT($token) {
    global $secretKey;
    return JWT::decode($token, new Key($secretKey, 'HS256'));
}
?>
