<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios – Software de Reservas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<main class="container">
    <h2>Gestión de Usuarios</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($u['rol']) ?>"><?= htmlspecialchars($u['rol']) ?></span></td>
                <td><?= $u['activo'] ? 'Activo' : 'Inactivo' ?></td>
                <td><?= htmlspecialchars($u['created_at']) ?></td>
                <td>
                    <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                    <form method="POST" action="toggle_usuario.php" style="display:inline">
                        <input type="hidden" name="usuario_id" value="<?= (int)$u['id'] ?>">
                        <button type="submit" class="btn btn-sm <?= $u['activo'] ? 'btn-danger' : 'btn-success' ?>">
                            <?= $u['activo'] ? 'Desactivar' : 'Activar' ?>
                        </button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<script src="../js/app.js"></script>
</body>
</html>
