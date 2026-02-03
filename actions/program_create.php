<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=programs');
    exit;
}

$name = trim($_POST['name'] ?? '');
$level = trim($_POST['level'] ?? '');
$duration = (int)($_POST['duration_semesters'] ?? 0);
$departmentId = (int)($_POST['department_id'] ?? 0);

if (!$name || !$level || !$duration || !$departmentId) {
    header('Location: ../index.php?page=programs');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('INSERT INTO programs (name, level, duration_semesters, department_id) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $level, $duration, $departmentId]);

header('Location: ../index.php?page=programs');
exit;
