<?php
require_once __DIR__ . '/persistent_auth.php';

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['csrf_token']) ||
    !is_string($_POST['csrf_token']) ||
    !hash_equals(csrfToken(), $_POST['csrf_token'])
) {
    http_response_code(400);
    exit('Invalid logout request.');
}

deleteRememberToken($pdo, $_COOKIE[REMEMBER_COOKIE] ?? null);
clearRememberCookie();

$_SESSION = [];
$sessionCookie = session_get_cookie_params();
setcookie(session_name(), '', [
    'expires' => time() - 3600,
    'path' => $sessionCookie['path'],
    'domain' => $sessionCookie['domain'],
    'secure' => $sessionCookie['secure'],
    'httponly' => $sessionCookie['httponly'],
    'samesite' => $sessionCookie['samesite'] ?? 'Lax',
]);
session_destroy();

header('Location: login.html');
exit;
