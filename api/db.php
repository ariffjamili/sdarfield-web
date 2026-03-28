<?php
// ---------------------------------------------------------------
// Database configuration
// Fill in your cPanel MySQL credentials before deploying
// ---------------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'sdara_sdarfield');   // e.g. sdara_sdarfield
define('DB_USER', 'sdara_sdarfield');     // e.g. sdara_dbuser
define('DB_PASS', 'Sikamat70400');

// ---------------------------------------------------------------
// Admin password (bcrypt hash)
// Generate with: php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);"
// Replace the string below with the output of that command.
// ---------------------------------------------------------------
define('ADMIN_PASSWORD_HASH', '$2y$12$6Oz7WaITnDjVcZCAIIVYnehSmSUg2VirDYlwu71VplMSMgNzzviLy');

// ---------------------------------------------------------------
// Returns a PDO connection. Called by API files.
// ---------------------------------------------------------------
function get_db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

// ---------------------------------------------------------------
// Helper: send a JSON response and exit
// ---------------------------------------------------------------
function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ---------------------------------------------------------------
// Helper: require an active admin session or abort with 401
// ---------------------------------------------------------------
function require_admin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin'])) {
        json_response(['error' => 'Unauthorized'], 401);
    }
}
