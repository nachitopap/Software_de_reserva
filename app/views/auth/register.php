<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro – Software de Reservas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="auth-page">
<div class="auth-container">
    <h1>🗓 Software de Reservas</h1>
    <h2>Crear Cuenta</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php" novalidate>
        <label for="rut">RUT</label>
        <input type="text" id="rut" name="rut" required placeholder="12345678K">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required placeholder="Juan">

        <label for="sobrenombre">Sobrenombre</label>
        <input type="text" id="sobrenombre" name="sobrenombre" placeholder="Juanito">

        <label for="apellidop">Apellido paterno</label>
        <input type="text" id="apellidop" name="apellidop" required placeholder="Pérez">

        <label for="apellidom">Apellido materno</label>
        <input type="text" id="apellidom" name="apellidom" required placeholder="González">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="usuario@email.com">

        <label for="password">Contraseña <small>(mín. 8 caracteres)</small></label>
        <input type="password" id="password" name="password" required placeholder="••••••••">

        <label for="rol">Tipo de cuenta</label>
        <select id="rol" name="rol" required>
            <option value="">-- Selecciona --</option>
            <option value="cliente">Cliente</option>
            <option value="proveedor">Proveedor de servicios</option>
        </select>

        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
    </form>

    <p class="auth-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
</div>
<script src="js/app.js"></script>
</body>
</html>
