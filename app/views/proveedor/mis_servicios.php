<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Servicios – Software de Reservas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="app-layout">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<main class="app-content">
    <h2>Mis Servicios</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h3>Agregar Servicio</h3>
    <form method="POST" action="mis_servicios.php" novalidate>
        <label for="nombre">Nombre del servicio</label>
        <input type="text" id="nombre" name="nombre" required placeholder="Ej: Corte de cabello">

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="2" placeholder="Opcional..."></textarea>

        <label for="precio">Precio ($)</label>
        <input type="number" id="precio" name="precio" min="0" step="0.01" required placeholder="0.00">

        <label for="duracion_min">Duración (minutos)</label>
        <input type="number" id="duracion_min" name="duracion_min" min="1" required placeholder="60">

        <button type="submit" class="btn btn-primary">Guardar Servicio</button>
    </form>

    <hr>
    <h3>Listado de Servicios</h3>
    <?php if (empty($servicios)): ?>
        <p class="empty-msg">No has registrado servicios aún.</p>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr><th>Nombre</th><th>Precio</th><th>Duración</th><th>Estado</th></tr>
        </thead>
        <tbody>
        <?php foreach ($servicios as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['nombre']) ?></td>
                <td>$<?= number_format($s['precio'], 2) ?></td>
                <td><?= (int)$s['duracion_min'] ?> min</td>
                <td><?= $s['activo'] ? 'Activo' : 'Inactivo' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</main>
</div>
<script src="../js/app.js"></script>
</body>
</html>
