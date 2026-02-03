<?php
require_once __DIR__ . '/../includes/auth.php';
$pdo = getPDO();
$user = current_user();

$stmt = $pdo->prepare('SELECT id, title, message, audience, created_at FROM announcements WHERE audience = :role OR audience = "todos" ORDER BY created_at DESC');
$stmt->execute(['role' => $user['role']]);
$announcements = $stmt->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Comunicados Oficiais</h1>
        <?php if (user_has_role('admin', 'faculty')): ?>
            <a href="#" class="button" data-modal-open="#modalAnnouncement"><i class="fa-solid fa-plus"></i> Novo Comunicado</a>
        <?php endif; ?>
    </div>
    <div class="card-grid">
        <?php if (!$announcements): ?>
            <p class="table-empty">Sem comunicados para o seu perfil.</p>
        <?php else: ?>
            <?php foreach ($announcements as $announcement): ?>
                <article class="card">
                    <h3><?= htmlspecialchars($announcement['title']) ?></h3>
                    <span class="badge">Audiência: <?= htmlspecialchars(ucfirst($announcement['audience'])) ?></span>
                    <p><?= nl2br(htmlspecialchars($announcement['message'])) ?></p>
                    <small><?= date('d/m/Y H:i', strtotime($announcement['created_at'])) ?></small>
                </article>
            <?php endforeach; ?>
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
                    <option value="student">Estudantes</option>
                    <option value="faculty">Docentes</option>
                </select>
            </div>
            <button type="submit" class="button">Publicar Comunicado</button>
        </form>
    </div>
</div>
<?php endif; ?>
