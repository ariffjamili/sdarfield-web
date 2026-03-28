<?php
require_once __DIR__ . '/db.php';

session_start();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db     = get_db();

if ($method === 'GET') {
    $showAll = !empty($_GET['all']) && !empty($_SESSION['admin']);

    if ($showAll) {
        // Admin: return all comments, unapproved first, then newest
        $stmt = $db->query(
            'SELECT id, name, comment, is_approved, created_at
             FROM comments
             ORDER BY is_approved ASC, created_at DESC'
        );
    } else {
        // Public: return only approved comments, newest first
        $stmt = $db->prepare(
            'SELECT id, name, comment, created_at
             FROM comments
             WHERE is_approved = 1
             ORDER BY created_at DESC'
        );
        $stmt->execute();
    }

    $rows = $stmt->fetchAll();
    // Convert is_approved int to bool for JS
    foreach ($rows as &$row) {
        if (isset($row['is_approved'])) {
            $row['is_approved'] = (bool) $row['is_approved'];
        }
    }
    json_response($rows);
}

if ($method === 'POST') {
    $body    = json_decode(file_get_contents('php://input'), true) ?? [];
    $name    = trim($body['name'] ?? '');
    $comment = trim($body['comment'] ?? '');

    if ($name === '' || $comment === '') {
        json_response(['error' => 'Name and comment are required'], 400);
    }
    if (mb_strlen($name) > 255) {
        json_response(['error' => 'Name too long'], 400);
    }

    $stmt = $db->prepare(
        'INSERT INTO comments (name, comment, is_approved) VALUES (?, ?, 0)'
    );
    $stmt->execute([$name, $comment]);

    json_response(['success' => true, 'id' => (int) $db->lastInsertId()], 201);
}

if ($method === 'PATCH') {
    require_admin();

    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $id   = isset($body['id']) ? (int) $body['id'] : 0;

    if ($id <= 0) {
        json_response(['error' => 'Invalid id'], 400);
    }

    $stmt = $db->prepare('UPDATE comments SET is_approved = 1 WHERE id = ?');
    $stmt->execute([$id]);

    json_response(['success' => true]);
}

if ($method === 'DELETE') {
    require_admin();

    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $id   = isset($body['id']) ? (int) $body['id'] : 0;

    if ($id <= 0) {
        json_response(['error' => 'Invalid id'], 400);
    }

    $stmt = $db->prepare('DELETE FROM comments WHERE id = ?');
    $stmt->execute([$id]);

    json_response(['success' => true]);
}

json_response(['error' => 'Method not allowed'], 405);
