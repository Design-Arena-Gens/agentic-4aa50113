<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config/database.php';

$error = null;

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Credenciais inválidas. Verifique e tente novamente.';
    } else {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Credenciais inválidas. Verifique e tente novamente.';
        } else {
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Académica - Login</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-7NfQxutZPvAd9m3z5Wv7zWx0A0RduCMsZ/HdlIQ+9k4=" crossorigin="anonymous"></script>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <h1>Portal Académico</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="form-grid">
            <div class="input-field">
                <label for="email">E-mail Institucional</label>
                <input type="email" id="email" name="email" required placeholder="nome@iic-aeg.ac.mz">
            </div>
            <div class="input-field">
                <label for="password">Palavra-passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Entrar</button>
        </form>
    </div>
</div>
</body>
</html>
