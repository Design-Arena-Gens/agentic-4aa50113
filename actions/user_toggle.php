<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=users');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$pdo = getPDO();

$stmt = $pdo->prepare('SELECT status FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($user) {
    $newStatus = $user['status'] === 'active' ? 'suspended' : 'active';
    $update = $pdo->prepare('UPDATE users SET status = ? WHERE id = ?');
    $update->execute([$newStatus, $id]);
}

header('Location: ../index.php?page=users');
exit;
