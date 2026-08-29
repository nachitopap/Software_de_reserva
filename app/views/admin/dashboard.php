<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin – Software de Reservas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="app-layout">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<main class="app-content">
    <h2>Panel de Administración</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= (int)$stats['total_usuarios'] ?></span>
            <span class="stat-label">Usuarios</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= (int)$stats['total_reservas'] ?></span>
            <span class="stat-label">Reservas</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= (int)$stats['total_servicios'] ?></span>
            <span class="stat-label">Servicios</span>
        </div>
    </div>

    <h3>Todas las Reservas</h3>
    <?php if (empty($reservas)): ?>
        <p class="empty-msg">No hay reservas registradas.</p>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Cliente</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($reservas as $r): ?>
            <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= htmlspecialchars($r['servicio']) ?></td>
                <td><?= htmlspecialchars($r['cliente_nombre_completo']) ?></td>
                <td><?= htmlspecialchars($r['proveedor_nombre_completo']) ?></td>
                <td><?= htmlspecialchars($r['fecha_reserva']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($r['estado']) ?>"><?= htmlspecialchars($r['estado']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <hr>
    <a href="usuarios.php" class="btn btn-secondary">Gestionar Usuarios</a>
    <a href="servicios.php" class="btn btn-secondary">Gestionar Servicios</a>
</main>
</div>
<script src="../js/app.js"></script>
</body>
</html>
