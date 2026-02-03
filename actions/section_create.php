<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=sections');
    exit;
}

$name = trim($_POST['name'] ?? '');
$courseId = (int)($_POST['course_id'] ?? 0);
$facultyId = (int)($_POST['faculty_id'] ?? 0);
$schedule = trim($_POST['schedule'] ?? '');

if (!$name || !$courseId || !$facultyId || !$schedule) {
    header('Location: ../index.php?page=sections');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('INSERT INTO class_sections (name, course_id, faculty_id, schedule) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $courseId, $facultyId, $schedule]);

header('Location: ../index.php?page=sections');
exit;
