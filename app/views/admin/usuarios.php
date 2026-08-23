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
<div class="app-layout">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<main class="app-content">
    <div class="section-header">
        <div>
            <h2>Gestión de Usuarios</h2>
        </div>
        <button type="button" class="btn btn-primary" data-modal-target="create-user-modal">
            <i class="bi bi-plus-circle" aria-hidden="true"></i>
            <span>Agregar nuevo</span>
        </button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>RUT</th>
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
                <td><?= (int)$u['display_id'] ?></td>
                <td><?= htmlspecialchars((string)$u['rut']) ?></td>
                <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($u['rol']) ?>"><?= htmlspecialchars($u['rol']) ?></span></td>
                <td>
                    <span class="badge <?= $u['activo'] ? 'badge-estado-activo' : 'badge-estado-inactivo' ?>">
                        <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($u['created_at']) ?></td>
                <td>
                    <div class="table-actions">
                    <button
                        type="button"
                        class="btn btn-sm btn-info"
                        data-modal-target="edit-user-modal"
                        data-user-id="<?= (int)$u['id'] ?>"
                        data-user-rut="<?= htmlspecialchars((string)$u['rut']) ?>"
                        data-user-nombre="<?= htmlspecialchars($u['nombre']) ?>"
                        data-user-sobrenombre="<?= htmlspecialchars((string)$u['sobrenombre']) ?>"
                        data-user-apellidop="<?= htmlspecialchars($u['apellidop']) ?>"
                        data-user-apellidom="<?= htmlspecialchars($u['apellidom']) ?>"
                        data-user-email="<?= htmlspecialchars($u['email']) ?>"
                        data-user-rol="<?= htmlspecialchars($u['rol']) ?>"
                    >
                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                        <span>Editar</span>
                    </button>
                    <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                    <button
                        type="button"
                        class="btn btn-sm <?= $u['activo'] ? 'btn-danger' : 'btn-success' ?>"
                        data-modal-target="status-user-modal"
                        data-user-id="<?= (int)$u['id'] ?>"
                        data-user-name="<?= htmlspecialchars($u['nombre_completo']) ?>"
                        data-user-active="<?= (int)$u['activo'] ?>"
                    >
                        <i class="bi <?= $u['activo'] ? 'bi-person-dash' : 'bi-person-check' ?>" aria-hidden="true"></i>
                        <span><?= $u['activo'] ? 'Inactivar' : 'Activar' ?></span>
                    </button>
                    <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</div>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>

<div class="modal-backdrop<?= $activeModal === 'create-user-modal' ? ' is-open' : '' ?>" id="create-user-modal" aria-hidden="<?= $activeModal === 'create-user-modal' ? 'false' : 'true' ?>">
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="create-user-modal-title">
        <div class="modal-header">
            <div>
                <h3 id="create-user-modal-title">Crear nuevo usuario</h3>
                <p class="modal-subtitle">El RUT se cifra al guardar y las acciones posteriores usan el ID del usuario.</p>
            </div>
            <button type="button" class="modal-close" data-modal-close="create-user-modal" aria-label="Cerrar modal">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <form method="POST" action="usuarios.php" novalidate>
            <input type="hidden" name="action" value="create-user">

            <label for="modal-rut">RUT</label>
            <input type="text" id="modal-rut" name="rut" required placeholder="12345678K" value="<?= htmlspecialchars($createFormData['rut']) ?>">

            <label for="modal-nombre">Nombre</label>
            <input type="text" id="modal-nombre" name="nombre" required placeholder="Juan" value="<?= htmlspecialchars($createFormData['nombre']) ?>">

            <label for="modal-sobrenombre">Sobrenombre</label>
            <input type="text" id="modal-sobrenombre" name="sobrenombre" placeholder="Juanito" value="<?= htmlspecialchars($createFormData['sobrenombre']) ?>">

            <label for="modal-apellidop">Apellido paterno</label>
            <input type="text" id="modal-apellidop" name="apellidop" required placeholder="Pérez" value="<?= htmlspecialchars($createFormData['apellidop']) ?>">

            <label for="modal-apellidom">Apellido materno</label>
            <input type="text" id="modal-apellidom" name="apellidom" required placeholder="González" value="<?= htmlspecialchars($createFormData['apellidom']) ?>">

            <label for="modal-email">Email</label>
            <input type="email" id="modal-email" name="email" required placeholder="usuario@email.com" value="<?= htmlspecialchars($createFormData['email']) ?>">

            <label for="modal-password">Contraseña</label>
            <input type="password" id="modal-password" name="password" required placeholder="Mínimo 8 caracteres">

            <label for="modal-rol">Rol</label>
            <select id="modal-rol" name="rol" required>
                <option value="cliente" <?= $createFormData['rol'] === ROLE_CLIENTE ? 'selected' : '' ?>>Cliente</option>
                <option value="proveedor" <?= $createFormData['rol'] === ROLE_PROVEEDOR ? 'selected' : '' ?>>Proveedor</option>
                <option value="admin" <?= $createFormData['rol'] === ROLE_ADMIN ? 'selected' : '' ?>>Administrador</option>
            </select>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close="create-user-modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus" aria-hidden="true"></i>
                    <span>Crear usuario</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop<?= $activeModal === 'edit-user-modal' ? ' is-open' : '' ?>" id="edit-user-modal" aria-hidden="<?= $activeModal === 'edit-user-modal' ? 'false' : 'true' ?>">
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="edit-user-modal-title">
        <div class="modal-header">
            <div>
                <h3 id="edit-user-modal-title">Editar usuario</h3>
                <p class="modal-subtitle">Actualiza los datos del usuario usando su ID interno como referencia.</p>
            </div>
            <button type="button" class="modal-close" data-modal-close="edit-user-modal" aria-label="Cerrar modal">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <form method="POST" action="usuarios.php" novalidate>
            <input type="hidden" name="action" value="update-user">
            <input type="hidden" id="edit-usuario-id" name="usuario_id" value="<?= (int)$editFormData['id'] ?>">

            <label for="edit-rut">RUT</label>
            <input type="text" id="edit-rut" name="rut" required placeholder="12345678K" value="<?= htmlspecialchars($editFormData['rut']) ?>">

            <label for="edit-nombre">Nombre</label>
            <input type="text" id="edit-nombre" name="nombre" required placeholder="Juan" value="<?= htmlspecialchars($editFormData['nombre']) ?>">

            <label for="edit-sobrenombre">Sobrenombre</label>
            <input type="text" id="edit-sobrenombre" name="sobrenombre" placeholder="Juanito" value="<?= htmlspecialchars($editFormData['sobrenombre']) ?>">

            <label for="edit-apellidop">Apellido paterno</label>
            <input type="text" id="edit-apellidop" name="apellidop" required placeholder="Pérez" value="<?= htmlspecialchars($editFormData['apellidop']) ?>">

            <label for="edit-apellidom">Apellido materno</label>
            <input type="text" id="edit-apellidom" name="apellidom" required placeholder="González" value="<?= htmlspecialchars($editFormData['apellidom']) ?>">

            <label for="edit-email">Email</label>
            <input type="email" id="edit-email" name="email" required placeholder="usuario@email.com" value="<?= htmlspecialchars($editFormData['email']) ?>">

            <label for="edit-password">Contraseña</label>
            <input type="password" id="edit-password" name="password" placeholder="Déjala vacía para no cambiarla">

            <label for="edit-rol">Rol</label>
            <select id="edit-rol" name="rol" required>
                <option value="cliente" <?= $editFormData['rol'] === ROLE_CLIENTE ? 'selected' : '' ?>>Cliente</option>
                <option value="proveedor" <?= $editFormData['rol'] === ROLE_PROVEEDOR ? 'selected' : '' ?>>Proveedor</option>
                <option value="admin" <?= $editFormData['rol'] === ROLE_ADMIN ? 'selected' : '' ?>>Administrador</option>
            </select>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close="edit-user-modal">Cancelar</button>
                <button type="submit" class="btn btn-info">
                    <i class="bi bi-save" aria-hidden="true"></i>
                    <span>Guardar cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop<?= $activeModal === 'status-user-modal' ? ' is-open' : '' ?>" id="status-user-modal" aria-hidden="<?= $activeModal === 'status-user-modal' ? 'false' : 'true' ?>">
    <div class="modal-dialog modal-dialog-sm" role="dialog" aria-modal="true" aria-labelledby="status-user-modal-title">
        <div class="modal-header">
            <div>
                <h3 id="status-user-modal-title"><?= (int)$statusFormData['activo'] === 1 ? 'Inactivar usuario' : 'Activar usuario' ?></h3>
                <p class="modal-subtitle">
                    <?= (int)$statusFormData['activo'] === 1 ? 'El usuario no se eliminará; solo quedará inactivo.' : 'El usuario volverá a poder ingresar al sistema.' ?>
                </p>
            </div>
            <button type="button" class="modal-close" data-modal-close="status-user-modal" aria-label="Cerrar modal">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <form method="POST" action="usuarios.php" novalidate>
            <input type="hidden" name="action" value="toggle-status">
            <input type="hidden" id="status-usuario-id" name="usuario_id" value="<?= (int)$statusFormData['id'] ?>">
            <input type="hidden" id="status-usuario-nombre" name="usuario_nombre" value="<?= htmlspecialchars($statusFormData['nombre_completo']) ?>">
            <input type="hidden" id="status-usuario-activo" name="usuario_activo" value="<?= (int)$statusFormData['activo'] ?>">

            <p class="modal-confirmation">
                <?= (int)$statusFormData['activo'] === 1 ? '¿Quieres dejar inactivo a' : '¿Quieres reactivar a' ?>
                <strong id="status-user-name-label"><?= htmlspecialchars($statusFormData['nombre_completo']) ?></strong>?
            </p>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close="status-user-modal">Cancelar</button>
                <button type="submit" class="btn <?= (int)$statusFormData['activo'] === 1 ? 'btn-danger' : 'btn-success' ?>">
                    <i class="bi <?= (int)$statusFormData['activo'] === 1 ? 'bi-person-dash' : 'bi-person-check' ?>" aria-hidden="true"></i>
                    <span><?= (int)$statusFormData['activo'] === 1 ? 'Confirmar inactivación' : 'Confirmar activación' ?></span>
                </button>
            </div>
        </form>
    </div>
</div>
<script src="../js/app.js"></script>
</body>
</html>
