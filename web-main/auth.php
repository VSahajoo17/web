<?php
session_start();
require 'db.php';

function redirectToLogin(string $status, string $form = 'login'): void
{
    header('Location: login.html?status=' . urlencode($status) . '&form=' . urlencode($form));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToLogin('invalid_input');
}

$action = $_POST['action'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!is_string($action) || !is_string($email) || !is_string($password)) {
    redirectToLogin('invalid_input');
}

$email = trim($email);
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    redirectToLogin('invalid_input', $action === 'register' ? 'register' : 'login');
}

if ($action === 'register') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phone = $_POST['phone_num'] ?? '';

    if (
        !is_string($firstname) ||
        !is_string($lastname) ||
        !is_string($phone) ||
        trim($firstname) === '' ||
        trim($lastname) === '' ||
        trim($phone) === '' ||
        strlen($password) < 8
    ) {
        redirectToLogin('invalid_input', 'register');
    }

    $statement = $pdo->prepare(
        'INSERT INTO login_info (firstname, lastname, password, email, phone_num)
         VALUES (:firstname, :lastname, :password, :email, :phone_num)'
    );

    try {
        $statement->execute([
            ':firstname' => trim($firstname),
            ':lastname' => trim($lastname),
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':email' => $email,
            ':phone_num' => trim($phone),
        ]);
    } catch (PDOException $error) {
        if ($error->getCode() === '23000') {
            redirectToLogin('email_exists', 'register');
        }

        error_log($error->getMessage());
        redirectToLogin('server_error', 'register');
    }

    redirectToLogin('registered');
}

if ($action === 'login') {
    $statement = $pdo->prepare(
        'SELECT userid, firstname, password FROM login_info WHERE email = :email'
    );
    $statement->execute([':email' => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        redirectToLogin('login_failed');
    }

    session_regenerate_id(true);
    $_SESSION['userid'] = $user['userid'];
    $_SESSION['firstname'] = $user['firstname'];
    header('Location: catalogue2.php');
    exit;
}

redirectToLogin('invalid_input');
