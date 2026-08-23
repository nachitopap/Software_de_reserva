<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Cliente – Software de Reservas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="app-layout">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<main class="app-content">
    <h2>Mis Reservas</h2>

    <a href="nueva_reserva.php" class="btn btn-primary">+ Nueva Reserva</a>

    <?php if (empty($reservas)): ?>
        <p class="empty-msg">No tienes reservas aún.</p>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($reservas as $r): ?>
            <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= htmlspecialchars($r['servicio']) ?></td>
                <td><?= htmlspecialchars($r['proveedor_nombre_completo']) ?></td>
                <td><?= htmlspecialchars($r['fecha_reserva']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($r['estado']) ?>"><?= htmlspecialchars($r['estado']) ?></span></td>
                <td>
                    <?php if ($r['estado'] === 'pendiente'): ?>
                    <form method="POST" action="cancelar_reserva.php" style="display:inline">
                        <input type="hidden" name="reserva_id" value="<?= (int)$r['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</main>
</div>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>
<script src="../js/app.js"></script>
</body>
</html>
