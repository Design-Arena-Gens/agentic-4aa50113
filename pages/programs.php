<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);
$pdo = getPDO();
$programs = $pdo->query('SELECT p.id, p.name, p.level, p.duration_semesters, d.name AS department_name FROM programs p LEFT JOIN departments d ON d.id = p.department_id ORDER BY p.name')->fetchAll();
$departments = $pdo->query('SELECT id, name FROM departments ORDER BY name')->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Cursos Técnicos</h1>
        <a href="#" class="button" data-modal-open="#modalProgram"><i class="fa-solid fa-plus"></i> Novo Curso</a>
    </div>
    <div class="card">
        <?php if (!$programs): ?>
            <p class="table-empty">Ainda não existem cursos cadastrados.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Nível</th>
                        <th>Duração</th>
                        <th>Departamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programs as $program): ?>
                        <tr>
                            <td><?= htmlspecialchars($program['name']) ?></td>
                            <td><?= htmlspecialchars($program['level']) ?></td>
                            <td><?= htmlspecialchars($program['duration_semesters']) ?> semestres</td>
                            <td><?= htmlspecialchars($program['department_name'] ?? 'Não definido') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div class="modal" id="modalProgram">
    <div class="modal-content">
        <div class="section-header">
            <h3>Novo Curso</h3>
            <button class="button button-outline" data-modal-close>Fechar</button>
        </div>
        <form method="post" action="actions/program_create.php" class="form-grid">
            <div class="input-field" style="grid-column: span 2;">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-field">
                <label for="level">Nível</label>
                <select id="level" name="level" required>
                    <option value="Técnico-Profissional">Técnico-Profissional</option>
                    <option value="Técnico Médio">Técnico Médio</option>
                    <option value="Superior">Superior</option>
                </select>
            </div>
            <div class="input-field">
                <label for="duration_semesters">Duração (semestres)</label>
                <input type="number" id="duration_semesters" name="duration_semesters" min="1" max="12" required>
            </div>
            <div class="input-field" style="grid-column: span 2;">
                <label for="department_id">Departamento</label>
                <select id="department_id" name="department_id" required>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="button">Guardar</button>
        </form>
    </div>
</div>
