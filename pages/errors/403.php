<?php
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Acesso Negado</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body class="error-page">
    <div class="error-card">
        <h1>403</h1>
        <p>Você não tem permissão para acessar esta página.</p>
        <a href="index.php" class="button">Voltar ao painel</a>
    </div>
</body>
</html>
