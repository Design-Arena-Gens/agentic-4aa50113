<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=departments');
    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');

if (!$name || !$description) {
    header('Location: ../index.php?page=departments');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('INSERT INTO departments (name, description) VALUES (?, ?)');
$stmt->execute([$name, $description]);

header('Location: ../index.php?page=departments');
exit;
