<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);
$pdo = getPDO();
$departments = $pdo->query('SELECT id, name, description, created_at FROM departments ORDER BY name')->fetchAll();
?>
<section class="section">
    <div class="section-header">
        <h1>Departamentos Académicos</h1>
        <a href="#" class="button" data-modal-open="#modalDepartment"><i class="fa-solid fa-plus"></i> Novo Departamento</a>
    </div>
    <div class="card">
        <?php if (!$departments): ?>
            <p class="table-empty">Ainda não existem departamentos registados.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $department): ?>
                        <tr>
                            <td><?= htmlspecialchars($department['name']) ?></td>
                            <td><?= htmlspecialchars($department['description']) ?></td>
                            <td><?= date('d/m/Y', strtotime($department['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div class="modal" id="modalDepartment">
    <div class="modal-content">
        <div class="section-header">
            <h3>Novo Departamento</h3>
            <button class="button button-outline" data-modal-close>Fechar</button>
        </div>
        <form method="post" action="actions/department_create.php" class="form-grid">
            <div class="input-field" style="grid-column: span 2;">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-field" style="grid-column: span 2;">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="button">Guardar</button>
        </form>
    </div>
</div>
