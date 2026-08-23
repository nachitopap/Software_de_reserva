<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

class ServicioController {

    public function listarActivos(): array {
        $db   = getDB();
        $stmt = $db->prepare(
            'SELECT s.id, s.nombre, s.descripcion, s.precio, s.duracion_min,
                    u.nombre AS proveedor_nombre, u.apellido AS proveedor_apellido
             FROM servicios s
             JOIN usuarios u ON s.proveedor_id = u.id
             WHERE s.activo = 1
             ORDER BY s.nombre ASC'
        );
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return $rows;
    }

    public function listarPorProveedor(int $proveedorId): array {
        $db   = getDB();
        $stmt = $db->prepare(
            'SELECT id, nombre, descripcion, precio, duracion_min, activo
             FROM servicios WHERE proveedor_id = ? ORDER BY nombre ASC'
        );
        $stmt->bind_param('i', $proveedorId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return $rows;
    }

    public function crear(int $proveedorId, array $data): array {
        $required = ['nombre', 'precio', 'duracion_min'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => "El campo $field es obligatorio."];
            }
        }
        $db   = getDB();
        $stmt = $db->prepare(
            'INSERT INTO servicios (proveedor_id, nombre, descripcion, precio, duracion_min)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('issdi', $proveedorId, $data['nombre'], $data['descripcion'] ?? '', $data['precio'], $data['duracion_min']);
        $ok = $stmt->execute();
        $stmt->close();
        $db->close();
        return $ok
            ? ['success' => true,  'message' => 'Servicio creado.']
            : ['success' => false, 'message' => 'Error al crear el servicio.'];
    }
}
