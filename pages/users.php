<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);
$pdo = getPDO();

$users = $pdo->query('SELECT id, full_name, email, role, status, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$roles = [
    'admin' => 'Administrador',
    'faculty' => 'Docente',
    'student' => 'Estudante',
    'staff' => 'Secretaria'
];
?>
<section class="section">
    <div class="section-header">
        <h1>Gestão de Utilizadores</h1>
        <div class="section-actions">
            <a href="#" class="button" data-modal-open="#modalUser"><i class="fa-solid fa-user-plus"></i> Novo Utilizador</a>
        </div>
    </div>
    <div class="card">
        <?php if (!$users): ?>
            <p class="table-empty">Ainda não existem utilizadores registados.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th>Estado</th>
                        <th>Data de Criação</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><span class="badge"><?= htmlspecialchars($roles[$user['role']] ?? $user['role']) ?></span></td>
                            <td>
                                <span class="status-badge <?= $user['status'] === 'active' ? 'active' : 'pending' ?>">
                                    <?= $user['status'] === 'active' ? 'Ativo' : 'Suspenso' ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <div class="table-actions">
                                    <form method="post" action="actions/user_toggle.php">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="button button-outline">
                                            <?= $user['status'] === 'active' ? 'Suspender' : 'Ativar' ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div class="modal" id="modalUser">
    <div class="modal-content">
        <div class="section-header">
            <h3>Novo Utilizador</h3>
            <button class="button button-outline" data-modal-close>Fechar</button>
        </div>
        <form method="post" action="actions/user_create.php" class="form-grid">
            <div class="input-field">
                <label for="full_name">Nome Completo</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="input-field">
                <label for="email">Email Institucional</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-field">
                <label for="role">Perfil</label>
                <select id="role" name="role" required>
                    <?php foreach ($roles as $value => $label): ?>
                        <option value="<?= $value ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field">
                <label for="password">Palavra-passe Temporária</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Criar Utilizador</button>
        </form>
    </div>
</div>
