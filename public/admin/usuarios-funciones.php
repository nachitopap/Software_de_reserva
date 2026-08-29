<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../config/config.php';

function adminUsuariosDefaultFormData(): array {
    return [
        'id' => 0,
        'rut' => '',
        'nombre' => '',
        'sobrenombre' => '',
        'apellidop' => '',
        'apellidom' => '',
        'email' => '',
        'rol' => ROLE_CLIENTE,
    ];
}

function adminUsuariosAllowedRoles(): array {
    return [ROLE_CLIENTE, ROLE_PROVEEDOR, ROLE_ADMIN];
}

function adminUsuariosList(): array {
    $db = getDB();
    $result = $db->query(
        'SELECT id, rut, nombre, sobrenombre, apellidop, apellidom, email, rol, activo, created_at
         FROM usuarios
         ORDER BY id DESC'
    );
    $usuarios = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $db->close();

    $totalUsuarios = count($usuarios);
    foreach ($usuarios as $index => &$usuario) {
        $usuario['display_id'] = $totalUsuarios - $index;
        $usuario['rut'] = decryptSensitiveValue((string)$usuario['rut']);
        $usuario['nombre_completo'] = buildPersonDisplayName($usuario);
    }
    unset($usuario);

    return $usuarios;
}

function adminUsuariosCreate(array $data): array {
    $prepared = adminUsuariosPrepareData($data);
    if (!$prepared['success']) {
        return $prepared;
    }

    $db = getDB();
    $stmt = $db->prepare(
        'INSERT INTO usuarios (rut, rut_hash, nombre, sobrenombre, apellidop, apellidom, email, password, rol)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'sssssssss',
        $prepared['data']['rut_encrypted'],
        $prepared['data']['rut_hash'],
        $prepared['data']['nombre'],
        $prepared['data']['sobrenombre'],
        $prepared['data']['apellidop'],
        $prepared['data']['apellidom'],
        $prepared['data']['email'],
        $prepared['data']['password_hash'],
        $prepared['data']['rol']
    );

    $success = $stmt->execute();
    $stmt->close();
    $db->close();

    return $success
        ? ['success' => true, 'message' => 'Usuario creado correctamente.']
        : ['success' => false, 'message' => 'No se pudo crear el usuario.'];
}

function adminUsuariosUpdate(int $userId, array $data): array {
    if ($userId <= 0) {
        return ['success' => false, 'message' => 'ID de usuario no válido.'];
    }

    $prepared = adminUsuariosPrepareData($data, $userId, true);
    if (!$prepared['success']) {
        return $prepared;
    }

    $db = getDB();

    if ($prepared['data']['password_hash'] !== '') {
        $stmt = $db->prepare(
            'UPDATE usuarios
             SET rut = ?, rut_hash = ?, nombre = ?, sobrenombre = ?, apellidop = ?, apellidom = ?, email = ?, password = ?, rol = ?
             WHERE id = ?'
        );
        $stmt->bind_param(
            'sssssssssi',
            $prepared['data']['rut_encrypted'],
            $prepared['data']['rut_hash'],
            $prepared['data']['nombre'],
            $prepared['data']['sobrenombre'],
            $prepared['data']['apellidop'],
            $prepared['data']['apellidom'],
            $prepared['data']['email'],
            $prepared['data']['password_hash'],
            $prepared['data']['rol'],
            $userId
        );
    } else {
        $stmt = $db->prepare(
            'UPDATE usuarios
             SET rut = ?, rut_hash = ?, nombre = ?, sobrenombre = ?, apellidop = ?, apellidom = ?, email = ?, rol = ?
             WHERE id = ?'
        );
        $stmt->bind_param(
            'ssssssssi',
            $prepared['data']['rut_encrypted'],
            $prepared['data']['rut_hash'],
            $prepared['data']['nombre'],
            $prepared['data']['sobrenombre'],
            $prepared['data']['apellidop'],
            $prepared['data']['apellidom'],
            $prepared['data']['email'],
            $prepared['data']['rol'],
            $userId
        );
    }

    $success = $stmt->execute();
    $stmt->close();
    $db->close();

    return $success
        ? ['success' => true, 'message' => 'Usuario actualizado correctamente.']
        : ['success' => false, 'message' => 'No se pudo actualizar el usuario.'];
}

function adminUsuariosToggleStatus(int $actorUserId, int $targetUserId): array {
    if ($targetUserId <= 0) {
        return ['success' => false, 'message' => 'ID de usuario no válido.'];
    }

    if ($actorUserId === $targetUserId) {
        return ['success' => false, 'message' => 'No puedes cambiar tu propio estado.'];
    }

    $db = getDB();
    $stmt = $db->prepare('UPDATE usuarios SET activo = NOT activo WHERE id = ?');
    $stmt->bind_param('i', $targetUserId);
    $success = $stmt->execute();
    $stmt->close();
    $db->close();

    return $success
        ? ['success' => true, 'message' => 'Estado del usuario actualizado.']
        : ['success' => false, 'message' => 'No se pudo actualizar el estado del usuario.'];
}

function adminUsuariosPrepareData(array $data, int $currentUserId = 0, bool $isUpdate = false): array {
    $formData = [
        'id' => $currentUserId,
        'rut' => trim((string)($data['rut'] ?? '')),
        'nombre' => trim((string)($data['nombre'] ?? '')),
        'sobrenombre' => trim((string)($data['sobrenombre'] ?? '')),
        'apellidop' => trim((string)($data['apellidop'] ?? '')),
        'apellidom' => trim((string)($data['apellidom'] ?? '')),
        'email' => trim((string)($data['email'] ?? '')),
        'rol' => (string)($data['rol'] ?? ROLE_CLIENTE),
    ];

    $required = ['rut', 'nombre', 'apellidop', 'apellidom', 'email', 'rol'];
    foreach ($required as $field) {
        if ($formData[$field] === '') {
            return ['success' => false, 'message' => "El campo $field es obligatorio.", 'formData' => $formData];
        }
    }

    if (!in_array($formData['rol'], adminUsuariosAllowedRoles(), true)) {
        return ['success' => false, 'message' => 'Rol no permitido.', 'formData' => $formData];
    }

    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Email no válido.', 'formData' => $formData];
    }

    $password = (string)($data['password'] ?? '');
    if (!$isUpdate || $password !== '') {
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres.', 'formData' => $formData];
        }
    }

    $rutNormalizado = normalizeRutValue($formData['rut']);
    if ($rutNormalizado === '') {
        return ['success' => false, 'message' => 'RUT no válido.', 'formData' => $formData];
    }

    $rutHash = hash('sha256', $rutNormalizado, true);
    $db = getDB();

    $check = $db->prepare('SELECT id FROM usuarios WHERE email = ? AND id <> ? LIMIT 1');
    $check->bind_param('si', $formData['email'], $currentUserId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        $db->close();
        return ['success' => false, 'message' => 'El email ya está registrado.', 'formData' => $formData];
    }
    $check->close();

    $check = $db->prepare('SELECT id FROM usuarios WHERE rut_hash = ? AND id <> ? LIMIT 1');
    $check->bind_param('si', $rutHash, $currentUserId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        $db->close();
        return ['success' => false, 'message' => 'El RUT ya está registrado.', 'formData' => $formData];
    }
    $check->close();
    $db->close();

    return [
        'success' => true,
        'data' => [
            'rut_encrypted' => encryptSensitiveValue($formData['rut']),
            'rut_hash' => $rutHash,
            'nombre' => $formData['nombre'],
            'sobrenombre' => $formData['sobrenombre'],
            'apellidop' => $formData['apellidop'],
            'apellidom' => $formData['apellidom'],
            'email' => $formData['email'],
            'password_hash' => $password !== '' ? password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]) : '',
            'rol' => $formData['rol'],
        ],
    ];
}
