<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Servicios – Software de Reservas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="app-layout">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<main class="app-content">
    <div class="section-header">
        <div>
            <h2>Gestión de Servicios</h2>
            <p class="section-subtitle">Administra servicios con modales y acciones internas por ID real.</p>
        </div>
        <button type="button" class="btn btn-primary" data-modal-target="create-service-modal">
            <i class="bi bi-plus-circle" aria-hidden="true"></i>
            <span>Agregar nuevo</span>
        </button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Proveedor</th>
                <th>Precio</th>
                <th>Duración</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($servicios as $s): ?>
            <tr>
                <td><?= (int)$s['display_id'] ?></td>
                <td>
                    <strong><?= htmlspecialchars($s['nombre']) ?></strong>
                    <?php if (!empty($s['descripcion'])): ?>
                        <div class="table-muted"><?= htmlspecialchars($s['descripcion']) ?></div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($s['proveedor_nombre_completo']) ?></td>
                <td>$<?= number_format((float)$s['precio'], 2) ?></td>
                <td><?= (int)$s['duracion_min'] ?> min</td>
                <td>
                    <span class="badge <?= $s['activo'] ? 'badge-estado-activo' : 'badge-estado-inactivo' ?>">
                        <?= $s['activo'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($s['created_at']) ?></td>
                <td>
                    <div class="table-actions">
                        <button
                            type="button"
                            class="btn btn-sm btn-info"
                            data-modal-target="edit-service-modal"
                            data-service-id="<?= (int)$s['id'] ?>"
                            data-service-provider-id="<?= (int)$s['proveedor_id'] ?>"
                            data-service-name="<?= htmlspecialchars($s['nombre']) ?>"
                            data-service-description="<?= htmlspecialchars((string)$s['descripcion']) ?>"
                            data-service-price="<?= htmlspecialchars((string)$s['precio']) ?>"
                            data-service-duration="<?= (int)$s['duracion_min'] ?>"
                        >
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            <span>Editar</span>
                        </button>
                        <button
                            type="button"
                            class="btn btn-sm <?= $s['activo'] ? 'btn-danger' : 'btn-success' ?>"
                            data-modal-target="status-service-modal"
                            data-service-id="<?= (int)$s['id'] ?>"
                            data-service-name="<?= htmlspecialchars($s['nombre']) ?>"
                            data-service-active="<?= (int)$s['activo'] ?>"
                        >
                            <i class="bi <?= $s['activo'] ? 'bi-toggle-off' : 'bi-toggle-on' ?>" aria-hidden="true"></i>
                            <span><?= $s['activo'] ? 'Inactivar' : 'Activar' ?></span>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</div>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>

<div class="modal-backdrop<?= $activeModal === 'create-service-modal' ? ' is-open' : '' ?>" id="create-service-modal" aria-hidden="<?= $activeModal === 'create-service-modal' ? 'false' : 'true' ?>">
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="create-service-modal-title">
        <div class="modal-header">
            <div>
                <h3 id="create-service-modal-title">Crear nuevo servicio</h3>
                <p class="modal-subtitle">Asignalo a un proveedor y define sus datos principales.</p>
            </div>
            <button type="button" class="modal-close" data-modal-close="create-service-modal" aria-label="Cerrar modal">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <form method="POST" action="servicios.php" novalidate>
            <input type="hidden" name="action" value="create-service">

            <label for="create-proveedor-id">Proveedor</label>
            <select id="create-proveedor-id" name="proveedor_id" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($proveedores as $proveedor): ?>
                    <option value="<?= (int)$proveedor['id'] ?>" <?= (int)$createFormData['proveedor_id'] === (int)$proveedor['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($proveedor['nombre_completo']) ?><?= $proveedor['activo'] ? '' : ' (Inactivo)' ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="create-service-name">Nombre del servicio</label>
            <input type="text" id="create-service-name" name="nombre" required placeholder="Ej: Corte premium" value="<?= htmlspecialchars($createFormData['nombre']) ?>">

            <label for="create-service-description">Descripción</label>
            <textarea id="create-service-description" name="descripcion" rows="3" placeholder="Describe el servicio..."><?= htmlspecialchars($createFormData['descripcion']) ?></textarea>

            <label for="create-service-price">Precio</label>
            <input type="number" id="create-service-price" name="precio" min="0" step="0.01" required placeholder="0.00" value="<?= htmlspecialchars((string)$createFormData['precio']) ?>">

            <label for="create-service-duration">Duración (minutos)</label>
            <input type="number" id="create-service-duration" name="duracion_min" min="1" required placeholder="60" value="<?= htmlspecialchars((string)$createFormData['duracion_min']) ?>">

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close="create-service-modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle" aria-hidden="true"></i>
                    <span>Crear servicio</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop<?= $activeModal === 'edit-service-modal' ? ' is-open' : '' ?>" id="edit-service-modal" aria-hidden="<?= $activeModal === 'edit-service-modal' ? 'false' : 'true' ?>">
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="edit-service-modal-title">
        <div class="modal-header">
            <div>
                <h3 id="edit-service-modal-title">Editar servicio</h3>
                <p class="modal-subtitle">Actualiza el servicio manteniendo sus acciones internas por ID real.</p>
            </div>
            <button type="button" class="modal-close" data-modal-close="edit-service-modal" aria-label="Cerrar modal">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <form method="POST" action="servicios.php" novalidate>
            <input type="hidden" name="action" value="update-service">
            <input type="hidden" id="edit-servicio-id" name="servicio_id" value="<?= (int)$editFormData['id'] ?>">

            <label for="edit-proveedor-id">Proveedor</label>
            <select id="edit-proveedor-id" name="proveedor_id" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($proveedores as $proveedor): ?>
                    <option value="<?= (int)$proveedor['id'] ?>" <?= (int)$editFormData['proveedor_id'] === (int)$proveedor['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($proveedor['nombre_completo']) ?><?= $proveedor['activo'] ? '' : ' (Inactivo)' ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="edit-service-name">Nombre del servicio</label>
            <input type="text" id="edit-service-name" name="nombre" required placeholder="Ej: Corte premium" value="<?= htmlspecialchars($editFormData['nombre']) ?>">

            <label for="edit-service-description">Descripción</label>
            <textarea id="edit-service-description" name="descripcion" rows="3" placeholder="Describe el servicio..."><?= htmlspecialchars($editFormData['descripcion']) ?></textarea>

            <label for="edit-service-price">Precio</label>
            <input type="number" id="edit-service-price" name="precio" min="0" step="0.01" required placeholder="0.00" value="<?= htmlspecialchars((string)$editFormData['precio']) ?>">

            <label for="edit-service-duration">Duración (minutos)</label>
            <input type="number" id="edit-service-duration" name="duracion_min" min="1" required placeholder="60" value="<?= htmlspecialchars((string)$editFormData['duracion_min']) ?>">

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close="edit-service-modal">Cancelar</button>
                <button type="submit" class="btn btn-info">
                    <i class="bi bi-save" aria-hidden="true"></i>
                    <span>Guardar cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop<?= $activeModal === 'status-service-modal' ? ' is-open' : '' ?>" id="status-service-modal" aria-hidden="<?= $activeModal === 'status-service-modal' ? 'false' : 'true' ?>">
    <div class="modal-dialog modal-dialog-sm" role="dialog" aria-modal="true" aria-labelledby="status-service-modal-title">
        <div class="modal-header">
            <div>
                <h3 id="status-service-modal-title"><?= (int)$statusFormData['activo'] === 1 ? 'Inactivar servicio' : 'Activar servicio' ?></h3>
                <p class="modal-subtitle">
                    <?= (int)$statusFormData['activo'] === 1 ? 'El servicio dejará de mostrarse como activo para reservas.' : 'El servicio volverá a estar disponible.' ?>
                </p>
            </div>
            <button type="button" class="modal-close" data-modal-close="status-service-modal" aria-label="Cerrar modal">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <form method="POST" action="servicios.php" novalidate>
            <input type="hidden" name="action" value="toggle-service-status">
            <input type="hidden" id="status-servicio-id" name="servicio_id" value="<?= (int)$statusFormData['id'] ?>">
            <input type="hidden" id="status-servicio-nombre" name="servicio_nombre" value="<?= htmlspecialchars($statusFormData['nombre']) ?>">
            <input type="hidden" id="status-servicio-activo" name="servicio_activo" value="<?= (int)$statusFormData['activo'] ?>">

            <p class="modal-confirmation">
                <?= (int)$statusFormData['activo'] === 1 ? '¿Quieres dejar inactivo el servicio' : '¿Quieres reactivar el servicio' ?>
                <strong id="status-service-name-label"><?= htmlspecialchars($statusFormData['nombre']) ?></strong>?
            </p>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close="status-service-modal">Cancelar</button>
                <button type="submit" class="btn <?= (int)$statusFormData['activo'] === 1 ? 'btn-danger' : 'btn-success' ?>">
                    <i class="bi <?= (int)$statusFormData['activo'] === 1 ? 'bi-toggle-off' : 'bi-toggle-on' ?>" aria-hidden="true"></i>
                    <span><?= (int)$statusFormData['activo'] === 1 ? 'Confirmar inactivación' : 'Confirmar activación' ?></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="../js/app.js"></script>
</body>
</html>