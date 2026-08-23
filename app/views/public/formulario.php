<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro – Software de Reservas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="public-page">
<main class="public-shell">
    <section class="public-card">
        <div class="public-header">
            <h1>Formulario de Registro</h1>
            <p>Completa tus datos para continuar al calendario de reservas.</p>
        </div>

        <form method="POST" action="formulario.php" novalidate>
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Juan" value="<?= htmlspecialchars($guestFormData['nombre']) ?>">

            <label for="apellidos">Apellidos</label>
            <input type="text" id="apellidos" name="apellidos" required placeholder="Pérez González" value="<?= htmlspecialchars($guestFormData['apellidos']) ?>">

            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" required placeholder="+56 9 1234 5678" value="<?= htmlspecialchars($guestFormData['telefono']) ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="cliente@email.com" value="<?= htmlspecialchars($guestFormData['email']) ?>">

            <div class="public-actions">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="bi bi-arrow-right-circle" aria-hidden="true"></i>
                    <span>Continuar al calendario</span>
                </button>
            </div>
        </form>

        <p class="auth-link">¿Eres administrador o proveedor? <a href="login.php">Ingresar con login</a></p>
    </section>
</main>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/app.js"></script>
</body>
</html>
