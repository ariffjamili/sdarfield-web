<?php
require_once __DIR__ . '/db.php';

session_start();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db     = get_db();

if ($method === 'GET') {
    $stmt = $db->query(
        'SELECT id, title, content, image_url, created_at
         FROM posts
         ORDER BY created_at DESC'
    );
    json_response($stmt->fetchAll());
}

if ($method === 'POST') {
    require_admin();

    $body     = json_decode(file_get_contents('php://input'), true) ?? [];
    $title    = trim($body['title'] ?? '');
    $content  = trim($body['content'] ?? '');
    $imageUrl = trim($body['imageUrl'] ?? '');

    if ($title === '' || $content === '') {
        json_response(['error' => 'Title and content are required'], 400);
    }

    $stmt = $db->prepare(
        'INSERT INTO posts (title, content, image_url) VALUES (?, ?, ?)'
    );
    $stmt->execute([$title, $content, $imageUrl ?: null]);

    json_response(['success' => true, 'id' => (int) $db->lastInsertId()], 201);
}

if ($method === 'DELETE') {
    require_admin();

    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $id   = isset($body['id']) ? (int) $body['id'] : 0;

    if ($id <= 0) {
        json_response(['error' => 'Invalid id'], 400);
    }

    $stmt = $db->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$id]);

    json_response(['success' => true]);
}

json_response(['error' => 'Method not allowed'], 405);
