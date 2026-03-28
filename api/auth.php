<?php
require_once __DIR__ . '/db.php';

session_start();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Check if admin is currently logged in
    echo json_encode(['authenticated' => !empty($_SESSION['admin'])]);
    exit;
}

if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    // Logout
    if (isset($body['action']) && $body['action'] === 'logout') {
        $_SESSION = [];
        session_destroy();
        json_response(['success' => true]);
    }

    // Login
    $password = $body['password'] ?? '';
    if ($password === '') {
        json_response(['error' => 'Password required'], 400);
    }

    if (password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin'] = true;
        json_response(['success' => true]);
    } else {
        json_response(['error' => 'Invalid password'], 401);
    }
}

json_response(['error' => 'Method not allowed'], 405);
