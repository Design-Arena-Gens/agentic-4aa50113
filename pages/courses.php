<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);
$pdo = getPDO();
$courses = $pdo->query('SELECT c.id, c.code, c.name, c.credits, p.name AS program_name FROM courses c LEFT JOIN programs p ON p.id = c.program_id ORDER BY c.code')->fetchAll();
$programs = $pdo->query('SELECT id, name FROM programs ORDER BY name')->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Disciplinas</h1>
        <a href="#" class="button" data-modal-open="#modalCourse"><i class="fa-solid fa-plus"></i> Nova Disciplina</a>
    </div>
    <div class="card">
        <?php if (!$courses): ?>
            <p class="table-empty">Nenhuma disciplina cadastrada.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Créditos</th>
                        <th>Curso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['code']) ?></td>
                            <td><?= htmlspecialchars($course['name']) ?></td>
                            <td><?= htmlspecialchars($course['credits']) ?></td>
                            <td><?= htmlspecialchars($course['program_name'] ?? 'Não associado') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div class="modal" id="modalCourse">
    <div class="modal-content">
        <div class="section-header">
            <h3>Nova Disciplina</h3>
            <button class="button button-outline" data-modal-close>Fechar</button>
        </div>
        <form method="post" action="actions/course_create.php" class="form-grid">
            <div class="input-field">
                <label for="code">Código</label>
                <input type="text" id="code" name="code" required>
            </div>
            <div class="input-field">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-field">
                <label for="credits">Créditos</label>
                <input type="number" id="credits" name="credits" min="1" max="30" required>
            </div>
            <div class="input-field" style="grid-column: span 2;">
                <label for="program_id">Curso</label>
                <select id="program_id" name="program_id" required>
                    <?php foreach ($programs as $program): ?>
                        <option value="<?= $program['id'] ?>"><?= htmlspecialchars($program['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="button">Guardar</button>
        </form>
    </div>
</div>
