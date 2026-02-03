<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['student']);
$pdo = getPDO();
$user = current_user();

$enrollments = $pdo->prepare('SELECT e.id, c.code, c.name AS course_name, s.name AS section_name, s.schedule, g.grade, g.obs
    FROM enrollments e
    INNER JOIN class_sections s ON s.id = e.section_id
    INNER JOIN courses c ON c.id = s.course_id
    LEFT JOIN grades g ON g.enrollment_id = e.id
    WHERE e.student_id = :student_id');
$enrollments->execute(['student_id' => $user['id']]);
$enrollments = $enrollments->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>O meu percurso académico</h1>
    </div>
    <div class="card">
        <?php if (!$enrollments): ?>
            <p class="table-empty">Ainda não estás inscrito em nenhuma disciplina.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Turma</th>
                        <th>Horário</th>
                        <th>Nota</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($enrollment['code']) ?></strong>
                                <div><?= htmlspecialchars($enrollment['course_name']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($enrollment['section_name']) ?></td>
                            <td><?= htmlspecialchars($enrollment['schedule']) ?></td>
                            <td><?= $enrollment['grade'] !== null ? htmlspecialchars(number_format($enrollment['grade'], 1)) : '-' ?></td>
                            <td><?= htmlspecialchars($enrollment['obs'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
