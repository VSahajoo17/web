<?php
require_once __DIR__ . '/db.php';

const REMEMBER_COOKIE = 'rr_saree_remember';
const REMEMBER_DURATION = 2592000;

function isHttpsRequest(): bool
{
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && strtolower($_SERVER['HTTPS']) !== 'off';
}

function rememberCookieOptions(int $expires): array
{
    return [
        'expires' => $expires,
        'path' => '/',
        'secure' => isHttpsRequest(),
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}

function clearRememberCookie(): void
{
    setcookie(REMEMBER_COOKIE, '', rememberCookieOptions(time() - 3600));
    unset($_COOKIE[REMEMBER_COOKIE]);
}

function deleteRememberToken(PDO $pdo, ?string $token): void
{
    if ($token === null || !preg_match('/\A[a-f0-9]{64}\z/', $token)) {
        return;
    }

    $statement = $pdo->prepare('DELETE FROM remember_tokens WHERE token_hash = :token_hash');
    $statement->execute([':token_hash' => hash('sha256', $token)]);
}

function issueRememberToken(PDO $pdo, int $userId): void
{
    deleteRememberToken($pdo, $_COOKIE[REMEMBER_COOKIE] ?? null);

    $token = bin2hex(random_bytes(32));
    $statement = $pdo->prepare(
        'INSERT INTO remember_tokens (userid, token_hash, expires_at)
         VALUES (:userid, :token_hash, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 30 DAY))'
    );
    $statement->execute([
        ':userid' => $userId,
        ':token_hash' => hash('sha256', $token),
    ]);

    $_COOKIE[REMEMBER_COOKIE] = $token;
    setcookie(REMEMBER_COOKIE, $token, rememberCookieOptions(time() + REMEMBER_DURATION));
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isHttpsRequest(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!isset($_SESSION['userid']) && isset($_COOKIE[REMEMBER_COOKIE])) {
    $rememberToken = $_COOKIE[REMEMBER_COOKIE];

    if (!is_string($rememberToken) || !preg_match('/\A[a-f0-9]{64}\z/', $rememberToken)) {
        clearRememberCookie();
    } else {
        $tokenHash = hash('sha256', $rememberToken);
        $statement = $pdo->prepare(
            'SELECT users.userid, users.firstname
             FROM remember_tokens tokens
             INNER JOIN login_info users ON users.userid = tokens.userid
             WHERE tokens.token_hash = :token_hash
               AND tokens.expires_at > UTC_TIMESTAMP()
             LIMIT 1'
        );
        $statement->execute([':token_hash' => $tokenHash]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            deleteRememberToken($pdo, $rememberToken);
            clearRememberCookie();
        } else {
            session_regenerate_id(true);
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['firstname'] = $user['firstname'];
            deleteRememberToken($pdo, $rememberToken);
            issueRememberToken($pdo, (int) $user['userid']);
        }
    }
}

if (
    isset($_SERVER['SCRIPT_FILENAME']) &&
    realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)
) {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');

    echo json_encode(['authenticated' => isset($_SESSION['userid'])]);
    exit;
}

function csrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}
