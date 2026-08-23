<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva – Software de Reservas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="app-layout">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<main class="app-content">
    <h2>Nueva Reserva</h2>

    <form method="POST" action="nueva_reserva.php" novalidate>
        <label for="servicio_id">Servicio</label>
        <select id="servicio_id" name="servicio_id" required>
            <option value="">-- Selecciona un servicio --</option>
            <?php foreach ($servicios as $s): ?>
            <option value="<?= (int)$s['id'] ?>">
                <?= htmlspecialchars($s['nombre']) ?> –
                <?= htmlspecialchars($s['proveedor_nombre_completo']) ?> –
                $<?= number_format($s['precio'], 2) ?>
            </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_reserva">Fecha y hora</label>
        <input type="datetime-local" id="fecha_reserva" name="fecha_reserva" required>

        <label for="notas">Notas adicionales</label>
        <textarea id="notas" name="notas" rows="3" placeholder="Opcional..."></textarea>

        <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>
</div>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>
<script src="../js/app.js"></script>
</body>
</html>
