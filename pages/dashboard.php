<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$user = current_user();
$pdo = getPDO();

$stats = [
    'students' => 0,
    'faculty' => 0,
    'courses' => 0,
    'sections' => 0,
];

if (user_has_role('admin')) {
    $stats['students'] = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    $stats['faculty'] = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'faculty'")->fetchColumn();
}
$stats['courses'] = (int)$pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn();
$stats['sections'] = (int)$pdo->query('SELECT COUNT(*) FROM class_sections')->fetchColumn();

$recentAnnouncements = $pdo->query('SELECT id, title, message, audience, created_at FROM announcements ORDER BY created_at DESC LIMIT 5')->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Bem-vindo, <?= htmlspecialchars($user['full_name']) ?>!</h1>
    </div>
    <p>Este é o painel integrado de gestão académica do Instituto Industrial e de Computação Armando Emílio Guebuza.</p>
</section>

<section class="section stats-row">
    <div class="stat-card">
        <span class="stat-label">Disciplinas Ativas</span>
        <span class="stat-value"><?= $stats['courses'] ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Turmas</span>
        <span class="stat-value"><?= $stats['sections'] ?></span>
    </div>
    <?php if (user_has_role('admin')): ?>
    <div class="stat-card">
        <span class="stat-label">Estudantes Matriculados</span>
        <span class="stat-value"><?= $stats['students'] ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Docentes</span>
        <span class="stat-value"><?= $stats['faculty'] ?></span>
    </div>
    <?php endif; ?>
</section>

<section class="section">
    <div class="section-header">
        <h2>Comunicados Recentes</h2>
        <?php if (user_has_role('admin', 'faculty')): ?>
            <a href="#" class="button" data-modal-open="#modalAnnouncement"><i class="fa-solid fa-plus"></i> Novo Comunicado</a>
        <?php endif; ?>
    </div>
    <div class="card">
        <?php if (!$recentAnnouncements): ?>
            <p class="table-empty">Nenhum comunicado publicado recentemente.</p>
        <?php else: ?>
            <ul class="timeline">
                <?php foreach ($recentAnnouncements as $announcement): ?>
                    <li>
                        <strong><?= htmlspecialchars($announcement['title']) ?></strong>
                        <div><?= nl2br(htmlspecialchars($announcement['message'])) ?></div>
                        <small class="badge">Audiência: <?= htmlspecialchars(ucfirst($announcement['audience'])) ?></small>
                        <br>
                        <small><?= date('d/m/Y H:i', strtotime($announcement['created_at'])) ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>

<?php if (user_has_role('admin', 'faculty')): ?>
<div class="modal" id="modalAnnouncement">
    <div class="modal-content">
        <div class="section-header">
            <h3>Novo Comunicado</h3>
            <button class="button button-outline" data-modal-close>Fechar</button>
        </div>
        <form method="post" action="actions/announcement_create.php" class="form-grid">
            <div class="input-field" style="grid-column: span 2;">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="input-field" style="grid-column: span 2;">
                <label for="message">Mensagem</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <div class="input-field">
                <label for="audience">Audiência</label>
                <select id="audience" name="audience" required>
                    <option value="todos">Todos</option>
                    <option value="faculty">Docentes</option>
                    <option value="student">Estudantes</option>
                </select>
            </div>
            <button type="submit" class="button">Publicar Comunicado</button>
        </form>
    </div>
</div>
<?php endif; ?>
