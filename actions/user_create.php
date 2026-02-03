<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=users');
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$role = $_POST['role'] ?? '';
$password = $_POST['password'] ?? '';

if (!$fullName || !$email || !$role || !$password) {
    header('Location: ../index.php?page=users');
    exit;
}

$pdo = getPDO();

try {
    $stmt = $pdo->prepare('INSERT INTO users (full_name, email, role, password_hash, status) VALUES (:full_name, :email, :role, :password_hash, :status)');
    $stmt->execute([
        'full_name' => $fullName,
        'email' => $email,
        'role' => $role,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'status' => 'active',
    ]);
} catch (PDOException $e) {
    // Log error in real scenario
}

header('Location: ../index.php?page=users');
exit;
