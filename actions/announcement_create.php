<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin', 'faculty']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');
$audience = $_POST['audience'] ?? 'todos';

if (!$title || !$message) {
    header('Location: ../index.php?page=announcements');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('INSERT INTO announcements (title, message, audience) VALUES (?, ?, ?)');
$stmt->execute([$title, $message, $audience]);

header('Location: ../index.php?page=announcements');
exit;
