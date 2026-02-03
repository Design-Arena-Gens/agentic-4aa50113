<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin', 'faculty']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=grades');
    exit;
}

$enrollmentId = (int)($_POST['enrollment_id'] ?? 0);
$grade = isset($_POST['grade']) ? (float)$_POST['grade'] : null;
$obs = trim($_POST['obs'] ?? '');

if (!$enrollmentId || $grade === null) {
    header('Location: ../index.php?page=grades');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('INSERT INTO grades (enrollment_id, grade, obs) VALUES (:enrollment_id, :grade, :obs)
    ON DUPLICATE KEY UPDATE grade = VALUES(grade), obs = VALUES(obs), updated_at = CURRENT_TIMESTAMP');
$stmt->execute([
    'enrollment_id' => $enrollmentId,
    'grade' => $grade,
    'obs' => $obs,
]);

header('Location: ../index.php?page=grades');
exit;
