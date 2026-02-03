<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin', 'faculty']);
$pdo = getPDO();
$user = current_user();

$query = 'SELECT e.id, u.full_name AS student_name, c.code, c.name AS course_name, s.name AS section_name, g.grade, g.obs
    FROM enrollments e
    INNER JOIN users u ON u.id = e.student_id
    INNER JOIN class_sections s ON s.id = e.section_id
    INNER JOIN courses c ON c.id = s.course_id
    LEFT JOIN grades g ON g.enrollment_id = e.id';
if ($user['role'] === 'faculty') {
    $query .= ' WHERE s.faculty_id = :faculty_id';
}
$query .= ' ORDER BY c.code, u.full_name';
$stmt = $pdo->prepare($query);
if ($user['role'] === 'faculty') {
    $stmt->bindValue(':faculty_id', $user['id']);
}
$stmt->execute();
$records = $stmt->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Lançamento de Notas</h1>
    </div>
    <div class="card">
        <?php if (!$records): ?>
            <p class="table-empty">Sem inscrições para atribuição de notas.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Estudante</th>
                        <th>Disciplina / Turma</th>
                        <th>Nota</th>
                        <th>Observações</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['student_name']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($record['code']) ?></strong>
                                <div><?= htmlspecialchars($record['course_name']) ?></div>
                                <small class="badge">Turma <?= htmlspecialchars($record['section_name']) ?></small>
                            </td>
                            <td><?= $record['grade'] !== null ? htmlspecialchars(number_format($record['grade'], 1)) : '-' ?></td>
                            <td><?= htmlspecialchars($record['obs'] ?? '-') ?></td>
                            <td>
                                <a href="#" class="button" data-modal-open="#modalGrade<?= $record['id'] ?>">Lançar / Editar</a>
                                <div class="modal" id="modalGrade<?= $record['id'] ?>">
                                    <div class="modal-content">
                                        <div class="section-header">
                                            <h3>Lançar Nota</h3>
                                            <button class="button button-outline" data-modal-close>Fechar</button>
                                        </div>
                                        <form method="post" action="actions/grade_update.php" class="form-grid">
                                            <input type="hidden" name="enrollment_id" value="<?= $record['id'] ?>">
                                            <div class="input-field">
                                                <label for="grade<?= $record['id'] ?>">Nota Final</label>
                                                <input type="number" id="grade<?= $record['id'] ?>" name="grade" step="0.1" min="0" max="20" value="<?= $record['grade'] !== null ? htmlspecialchars($record['grade']) : '' ?>" required>
                                            </div>
                                            <div class="input-field" style="grid-column: span 2;">
                                                <label for="obs<?= $record['id'] ?>">Observações</label>
                                                <textarea id="obs<?= $record['id'] ?>" name="obs" rows="3"><?= htmlspecialchars($record['obs'] ?? '') ?></textarea>
                                            </div>
                                            <button type="submit" class="button">Guardar</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
