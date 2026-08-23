<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../config/config.php';

function adminServiciosDefaultFormData(): array {
    return [
        'id' => 0,
        'proveedor_id' => 0,
        'nombre' => '',
        'descripcion' => '',
        'precio' => '',
        'duracion_min' => 60,
    ];
}

function adminServiciosListarProveedores(): array {
    $db = getDB();
    $stmt = $db->prepare(
        'SELECT id, nombre, sobrenombre, apellidop, apellidom, activo
         FROM usuarios
         WHERE rol = ?
         ORDER BY nombre ASC, apellidop ASC, apellidom ASC'
    );
    $rol = ROLE_PROVEEDOR;
    $stmt->bind_param('s', $rol);
    $stmt->execute();
    $proveedores = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $db->close();

    foreach ($proveedores as &$proveedor) {
        $proveedor['nombre_completo'] = buildPersonDisplayName($proveedor);
    }
    unset($proveedor);

    return $proveedores;
}

function adminServiciosListar(): array {
    $db = getDB();
    $result = $db->query(
        'SELECT s.id, s.proveedor_id, s.nombre, s.descripcion, s.precio, s.duracion_min, s.activo, s.created_at,
                u.nombre AS proveedor_nombre, u.sobrenombre, u.apellidop, u.apellidom
         FROM servicios s
         JOIN usuarios u ON u.id = s.proveedor_id
         ORDER BY s.id DESC'
    );
    $servicios = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $db->close();

    $totalServicios = count($servicios);
    foreach ($servicios as $index => &$servicio) {
        $servicio['display_id'] = $totalServicios - $index;
        $servicio['proveedor_nombre_completo'] = buildPersonDisplayName([
            'nombre' => $servicio['proveedor_nombre'],
            'apellidop' => $servicio['apellidop'],
            'apellidom' => $servicio['apellidom'],
        ]);
    }
    unset($servicio);

    return $servicios;
}

function adminServiciosCrear(array $data): array {
    $prepared = adminServiciosPrepararDatos($data);
    if (!$prepared['success']) {
        return $prepared;
    }

    $db = getDB();
    $stmt = $db->prepare(
        'INSERT INTO servicios (proveedor_id, nombre, descripcion, precio, duracion_min)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'issdi',
        $prepared['data']['proveedor_id'],
        $prepared['data']['nombre'],
        $prepared['data']['descripcion'],
        $prepared['data']['precio'],
        $prepared['data']['duracion_min']
    );

    $success = $stmt->execute();
    $stmt->close();
    $db->close();

    return $success
        ? ['success' => true, 'message' => 'Servicio creado correctamente.']
        : ['success' => false, 'message' => 'No se pudo crear el servicio.'];
}

function adminServiciosActualizar(int $serviceId, array $data): array {
    if ($serviceId <= 0) {
        return ['success' => false, 'message' => 'ID de servicio no válido.'];
    }

    $prepared = adminServiciosPrepararDatos($data, $serviceId, true);
    if (!$prepared['success']) {
        return $prepared;
    }

    $db = getDB();
    $stmt = $db->prepare(
        'UPDATE servicios
         SET proveedor_id = ?, nombre = ?, descripcion = ?, precio = ?, duracion_min = ?
         WHERE id = ?'
    );
    $stmt->bind_param(
        'issdii',
        $prepared['data']['proveedor_id'],
        $prepared['data']['nombre'],
        $prepared['data']['descripcion'],
        $prepared['data']['precio'],
        $prepared['data']['duracion_min'],
        $serviceId
    );

    $success = $stmt->execute();
    $stmt->close();
    $db->close();

    return $success
        ? ['success' => true, 'message' => 'Servicio actualizado correctamente.']
        : ['success' => false, 'message' => 'No se pudo actualizar el servicio.'];
}

function adminServiciosCambiarEstado(int $serviceId): array {
    if ($serviceId <= 0) {
        return ['success' => false, 'message' => 'ID de servicio no válido.'];
    }

    $db = getDB();
    $stmt = $db->prepare('UPDATE servicios SET activo = NOT activo WHERE id = ?');
    $stmt->bind_param('i', $serviceId);
    $success = $stmt->execute();
    $stmt->close();
    $db->close();

    return $success
        ? ['success' => true, 'message' => 'Estado del servicio actualizado.']
        : ['success' => false, 'message' => 'No se pudo actualizar el estado del servicio.'];
}

function adminServiciosPrepararDatos(array $data, int $serviceId = 0, bool $isUpdate = false): array {
    $formData = [
        'id' => $serviceId,
        'proveedor_id' => (int)($data['proveedor_id'] ?? 0),
        'nombre' => trim((string)($data['nombre'] ?? '')),
        'descripcion' => trim((string)($data['descripcion'] ?? '')),
        'precio' => trim((string)($data['precio'] ?? '')),
        'duracion_min' => (int)($data['duracion_min'] ?? 0),
    ];

    if ($formData['proveedor_id'] <= 0) {
        return ['success' => false, 'message' => 'Debes seleccionar un proveedor.', 'formData' => $formData];
    }
    if ($formData['nombre'] === '') {
        return ['success' => false, 'message' => 'El nombre es obligatorio.', 'formData' => $formData];
    }
    if ($formData['precio'] === '' || !is_numeric($formData['precio']) || (float)$formData['precio'] < 0) {
        return ['success' => false, 'message' => 'El precio debe ser un numero válido.', 'formData' => $formData];
    }
    if ($formData['duracion_min'] <= 0) {
        return ['success' => false, 'message' => 'La duración debe ser mayor a 0.', 'formData' => $formData];
    }

    $db = getDB();
    $stmt = $db->prepare('SELECT id FROM usuarios WHERE id = ? AND rol = ? LIMIT 1');
    $rol = ROLE_PROVEEDOR;
    $stmt->bind_param('is', $formData['proveedor_id'], $rol);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        $db->close();
        return ['success' => false, 'message' => 'El proveedor seleccionado no es válido.', 'formData' => $formData];
    }
    $stmt->close();
    $db->close();

    return [
        'success' => true,
        'data' => [
            'proveedor_id' => $formData['proveedor_id'],
            'nombre' => $formData['nombre'],
            'descripcion' => $formData['descripcion'],
            'precio' => (float)$formData['precio'],
            'duracion_min' => $formData['duracion_min'],
        ],
    ];
}
