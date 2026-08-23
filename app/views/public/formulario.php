<?php
require_once __DIR__ . '/../../../config/funciones.php';

$guestFormData = $guestFormData ?? [
    'nombre' => '',
    'apellidos' => '',
    'telefono' => '',
    'email' => '',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro – Software de Reservas</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(appPath('public/css/styles.css')) ?>">
</head>
<body class="public-page">
<main class="public-shell">
    <section class="public-card">
        <div class="public-header">
            <h1>Formulario de Registro</h1>
            <p>Completa tus datos para continuar al calendario de reservas.</p>
        </div>

        <form method="POST" action="<?= htmlspecialchars(appPath('public/formulario.php')) ?>" novalidate>
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
                    <span>Continuar</span>
                </button>
            </div>
        </form>

    </section>
</main>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= htmlspecialchars(appPath('public/js/app.js')) ?>"></script>
</body>
</html>
