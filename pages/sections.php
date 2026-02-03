<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);
$pdo = getPDO();
$sections = $pdo->query('SELECT s.id, s.name, s.schedule, c.code, c.name AS course_name, u.full_name AS faculty_name FROM class_sections s LEFT JOIN courses c ON c.id = s.course_id LEFT JOIN users u ON u.id = s.faculty_id ORDER BY s.created_at DESC')->fetchAll();
$courses = $pdo->query('SELECT id, code, name FROM courses ORDER BY code')->fetchAll();
$faculty = $pdo->prepare("SELECT id, full_name FROM users WHERE role = 'faculty' AND status = 'active' ORDER BY full_name");
$faculty->execute();
$faculty = $faculty->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Turmas</h1>
        <a href="#" class="button" data-modal-open="#modalSection"><i class="fa-solid fa-plus"></i> Nova Turma</a>
    </div>
    <div class="card">
        <?php if (!$sections): ?>
            <p class="table-empty">Nenhuma turma criada.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Disciplina</th>
                        <th>Docente</th>
                        <th>Horário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sections as $section): ?>
                        <tr>
                            <td><?= htmlspecialchars($section['name']) ?></td>
                            <td><?= htmlspecialchars($section['code'] . ' - ' . $section['course_name']) ?></td>
                            <td><?= htmlspecialchars($section['faculty_name'] ?? 'Não atribuído') ?></td>
                            <td><?= htmlspecialchars($section['schedule']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div class="modal" id="modalSection">
    <div class="modal-content">
        <div class="section-header">
            <h3>Nova Turma</h3>
            <button class="button button-outline" data-modal-close>Fechar</button>
        </div>
        <form method="post" action="actions/section_create.php" class="form-grid">
            <div class="input-field">
                <label for="name">Turma</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-field">
                <label for="course_id">Disciplina</label>
                <select id="course_id" name="course_id" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['code'] . ' - ' . $course['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field">
                <label for="faculty_id">Docente</label>
                <select id="faculty_id" name="faculty_id" required>
                    <?php foreach ($faculty as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>"><?= htmlspecialchars($teacher['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field" style="grid-column: span 2;">
                <label for="schedule">Horário</label>
                <textarea id="schedule" name="schedule" rows="3" placeholder="Ex: Seg & Qua - 08h00 às 10h00 / Laboratório de Redes" required></textarea>
            </div>
            <button type="submit" class="button">Guardar</button>
        </form>
    </div>
</div>
