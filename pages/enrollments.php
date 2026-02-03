<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin', 'faculty']);
$pdo = getPDO();

$user = current_user();
$sectionsQuery = 'SELECT s.id, s.name, c.code, c.name AS course_name FROM class_sections s INNER JOIN courses c ON c.id = s.course_id';
if ($user['role'] === 'faculty') {
    $sectionsQuery .= ' WHERE s.faculty_id = :faculty_id';
}
$sectionsQuery .= ' ORDER BY s.name';
$sectionsStmt = $pdo->prepare($sectionsQuery);
if ($user['role'] === 'faculty') {
    $sectionsStmt->bindValue(':faculty_id', $user['id']);
}
$sectionsStmt->execute();
$sections = $sectionsStmt->fetchAll();

$students = $pdo->prepare("SELECT id, full_name FROM users WHERE role = 'student' AND status = 'active' ORDER BY full_name");
$students->execute();
$students = $students->fetchAll();

$enrollmentsQuery = 'SELECT e.id, u.full_name AS student_name, s.name AS section_name, c.code, c.name AS course_name, e.status, e.created_at
    FROM enrollments e
    INNER JOIN users u ON u.id = e.student_id
    INNER JOIN class_sections s ON s.id = e.section_id
    INNER JOIN courses c ON c.id = s.course_id';
if ($user['role'] === 'faculty') {
    $enrollmentsQuery .= ' WHERE s.faculty_id = :faculty_id';
}
$enrollmentsQuery .= ' ORDER BY e.created_at DESC';
$enrollmentsStmt = $pdo->prepare($enrollmentsQuery);
if ($user['role'] === 'faculty') {
    $enrollmentsStmt->bindValue(':faculty_id', $user['id']);
}
$enrollmentsStmt->execute();
$enrollments = $enrollmentsStmt->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Inscrições em Disciplinas</h1>
        <a href="#" class="button" data-modal-open="#modalEnrollment"><i class="fa-solid fa-user-plus"></i> Nova Inscrição</a>
    </div>
    <div class="card">
        <?php if (!$enrollments): ?>
            <p class="table-empty">Nenhuma inscrição registada.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Estudante</th>
                        <th>Disciplina / Turma</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td><?= htmlspecialchars($enrollment['student_name']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($enrollment['code']) ?></strong>
                                <div><?= htmlspecialchars($enrollment['course_name']) ?></div>
                                <small class="badge">Turma <?= htmlspecialchars($enrollment['section_name']) ?></small>
                            </td>
                            <td>
                                <span class="status-badge <?= $enrollment['status'] === 'active' ? 'active' : 'pending' ?>">
                                    <?= $enrollment['status'] === 'active' ? 'Ativa' : 'Pendente' ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($enrollment['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div class="modal" id="modalEnrollment">
    <div class="modal-content">
        <div class="section-header">
            <h3>Nova Inscrição</h3>
            <button class="button button-outline" data-modal-close>Fechar</button>
        </div>
        <form method="post" action="actions/enrollment_create.php" class="form-grid">
            <div class="input-field" style="grid-column: span 2;">
                <label for="student_id">Estudante</label>
                <select id="student_id" name="student_id" required>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field" style="grid-column: span 2;">
                <label for="section_id">Turma</label>
                <select id="section_id" name="section_id" required>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= $section['id'] ?>"><?= htmlspecialchars($section['name'] . ' - ' . $section['code'] . ' ' . $section['course_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Ativa</option>
                    <option value="pending">Pendente</option>
                </select>
            </div>
            <button type="submit" class="button">Guardar</button>
        </form>
    </div>
</div>
