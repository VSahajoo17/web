<?php
require 'db.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(int $status, array $body): void
{
    http_response_code($status);
    echo json_encode($body);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['error' => 'method_not_allowed']);
}

$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';

if (!is_string($field) || !is_string($value)) {
    respond(400, ['error' => 'invalid_input']);
}

$value = trim($value);
if (
    ($field === 'email' && (!filter_var($value, FILTER_VALIDATE_EMAIL) || strlen($value) > 100)) ||
    ($field === 'phone' && ($value === '' || strlen($value) > 20)) ||
    !in_array($field, ['email', 'phone'], true)
) {
    respond(422, ['error' => 'invalid_input']);
}

$column = $field === 'email' ? 'email' : 'phone_num';

try {
    $statement = $pdo->prepare("SELECT 1 FROM login_info WHERE {$column} = :value LIMIT 1");
    $statement->execute([':value' => $value]);
    respond(200, ['available' => $statement->fetchColumn() === false]);
} catch (PDOException $error) {
    error_log($error->getMessage());
    respond(500, ['error' => 'server_error']);
}
