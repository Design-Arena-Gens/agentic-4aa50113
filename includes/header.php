<?php
require_once __DIR__ . '/../includes/auth.php';
$config = require __DIR__ . '/../config/config.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['app']['name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-yH6j8m7kVDCq+GqOR/mrIW6DCeA44y7NysGEKMluSleqU9jwELyhl725LLJoPLD114F8CbnMD4PlzyBbsRUmg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="public/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-7NfQxutZPvAd9m3z5Wv7zWx0A0RduCMsZ/HdlIQ+9k4=" crossorigin="anonymous"></script>
    <script src="public/js/app.js" defer></script>
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <span class="brand-logo"><i class="fa-solid fa-graduation-cap"></i></span>
            <div>
                <strong><?= htmlspecialchars($config['app']['name']) ?></strong>
                <small>Gestão Académica</small>
            </div>
        </div>
        <?php if ($user): ?>
            <div class="user-info">
                <span><?= htmlspecialchars($user['full_name']) ?> (<?= htmlspecialchars(ucfirst($user['role'])) ?>)</span>
                <form method="post" action="logout.php">
                    <button type="submit" class="button button-outline">Terminar Sessão</button>
                </form>
            </div>
        <?php endif; ?>
    </header>
    <div class="layout">
        <?php if ($user): ?>
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="index.php" class="nav-link"><i class="fa-solid fa-gauge"></i> Painel Principal</a></li>
                    <?php if (user_has_role('admin')): ?>
                        <li><a href="index.php?page=users" class="nav-link"><i class="fa-solid fa-users"></i> Utilizadores</a></li>
                        <li><a href="index.php?page=departments" class="nav-link"><i class="fa-solid fa-building"></i> Departamentos</a></li>
                        <li><a href="index.php?page=programs" class="nav-link"><i class="fa-solid fa-diagram-project"></i> Cursos Técnicos</a></li>
                        <li><a href="index.php?page=courses" class="nav-link"><i class="fa-solid fa-book"></i> Disciplinas</a></li>
                        <li><a href="index.php?page=sections" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Turmas</a></li>
                    <?php endif; ?>
                    <?php if (user_has_role('admin', 'faculty')): ?>
                        <li><a href="index.php?page=enrollments" class="nav-link"><i class="fa-solid fa-user-check"></i> Inscrições</a></li>
                        <li><a href="index.php?page=grades" class="nav-link"><i class="fa-solid fa-star"></i> Lançamento de Notas</a></li>
                    <?php endif; ?>
                    <?php if (user_has_role('student')): ?>
                        <li><a href="index.php?page=student" class="nav-link"><i class="fa-solid fa-graduation-cap"></i> Meu Desempenho</a></li>
                    <?php endif; ?>
                    <li><a href="index.php?page=announcements" class="nav-link"><i class="fa-solid fa-bullhorn"></i> Comunicados</a></li>
                </ul>
            </nav>
        </aside>
        <?php endif; ?>
        <main class="content">
