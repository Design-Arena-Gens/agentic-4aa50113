<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin', 'faculty']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=enrollments');
    exit;
}

$studentId = (int)($_POST['student_id'] ?? 0);
$sectionId = (int)($_POST['section_id'] ?? 0);
$status = $_POST['status'] ?? 'pending';

if (!$studentId || !$sectionId) {
    header('Location: ../index.php?page=enrollments');
    exit;
}

$pdo = getPDO();

$stmt = $pdo->prepare('INSERT INTO enrollments (student_id, section_id, status) VALUES (?, ?, ?)');
try {
    $stmt->execute([$studentId, $sectionId, $status]);
} catch (PDOException $e) {
    // Em ambientes reais deveriamos registar o erro
}

header('Location: ../index.php?page=enrollments');
exit;
