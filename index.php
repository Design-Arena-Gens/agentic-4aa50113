<?php
require_once __DIR__ . '/includes/auth.php';
require_login();

$page = $_GET['page'] ?? 'dashboard';
$allowed = [
    'dashboard', 'users', 'departments', 'programs', 'courses', 'sections', 'enrollments', 'grades', 'student', 'announcements'
];

if (!in_array($page, $allowed, true)) {
    $page = 'dashboard';
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/pages/' . $page . '.php';
include __DIR__ . '/includes/footer.php';
