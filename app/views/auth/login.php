<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión – Software de Reservas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="auth-page">
<div class="auth-container">
    <h1>🗓 Software de Reservas</h1>
    <h2>Iniciar Sesión</h2>

    <form method="POST" action="login.php" novalidate>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="usuario@email.com">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required placeholder="••••••••">

        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
    </form>

</div>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/app.js"></script>
</body>
</html>
