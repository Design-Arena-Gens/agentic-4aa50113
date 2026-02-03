<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=courses');
    exit;
}

$code = trim($_POST['code'] ?? '');
$name = trim($_POST['name'] ?? '');
$credits = (int)($_POST['credits'] ?? 0);
$programId = (int)($_POST['program_id'] ?? 0);

if (!$code || !$name || !$credits || !$programId) {
    header('Location: ../index.php?page=courses');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('INSERT INTO courses (code, name, credits, program_id) VALUES (?, ?, ?, ?)');
$stmt->execute([$code, $name, $credits, $programId]);

header('Location: ../index.php?page=courses');
exit;
