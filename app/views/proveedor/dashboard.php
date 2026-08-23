<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Proveedor – Software de Reservas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="app-layout">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<main class="app-content">
    <h2>Reservas de mis Servicios</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($reservas)): ?>
        <p class="empty-msg">No hay reservas para tus servicios todavía.</p>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Cliente</th>
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
                <td><?= htmlspecialchars($r['cliente_nombre_completo']) ?></td>
                <td><?= htmlspecialchars($r['fecha_reserva']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($r['estado']) ?>"><?= htmlspecialchars($r['estado']) ?></span></td>
                <td>
                    <?php if ($r['estado'] === 'pendiente'): ?>
                    <form method="POST" action="gestionar_reserva.php" style="display:inline">
                        <input type="hidden" name="reserva_id" value="<?= (int)$r['id'] ?>">
                        <button name="estado" value="confirmada" class="btn btn-success btn-sm">Confirmar</button>
                        <button name="estado" value="cancelada"  class="btn btn-danger  btn-sm">Cancelar</button>
                    </form>
                    <?php elseif ($r['estado'] === 'confirmada'): ?>
                    <form method="POST" action="gestionar_reserva.php" style="display:inline">
                        <input type="hidden" name="reserva_id" value="<?= (int)$r['id'] ?>">
                        <button name="estado" value="completada" class="btn btn-info btn-sm">Completar</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <hr>
    <h3>Mis Servicios</h3>
    <a href="mis_servicios.php" class="btn btn-secondary">Administrar Servicios</a>
</main>
</div>
<script src="../js/app.js"></script>
</body>
</html>
