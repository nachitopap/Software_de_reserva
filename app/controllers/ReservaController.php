<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

class ReservaController {

    public function listarPorCliente(int $clienteId): array {
        $db   = getDB();
        $stmt = $db->prepare(
            'SELECT r.id, s.nombre AS servicio, r.fecha_reserva, r.estado, r.notas,
                    u.nombre AS proveedor_nombre, u.apellido AS proveedor_apellido
             FROM reservas r
             JOIN servicios s ON r.servicio_id = s.id
             JOIN usuarios  u ON s.proveedor_id = u.id
             WHERE r.cliente_id = ?
             ORDER BY r.fecha_reserva DESC'
        );
        $stmt->bind_param('i', $clienteId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return $rows;
    }

    public function listarPorProveedor(int $proveedorId): array {
        $db   = getDB();
        $stmt = $db->prepare(
            'SELECT r.id, s.nombre AS servicio, r.fecha_reserva, r.estado, r.notas,
                    u.nombre AS cliente_nombre, u.apellido AS cliente_apellido
             FROM reservas r
             JOIN servicios s ON r.servicio_id = s.id
             JOIN usuarios  u ON r.cliente_id  = u.id
             WHERE s.proveedor_id = ?
             ORDER BY r.fecha_reserva DESC'
        );
        $stmt->bind_param('i', $proveedorId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return $rows;
    }

    public function crear(int $clienteId, int $servicioId, string $fechaReserva, string $notas = ''): array {
        $db   = getDB();
        $stmt = $db->prepare(
            'INSERT INTO reservas (cliente_id, servicio_id, fecha_reserva, notas) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('iiss', $clienteId, $servicioId, $fechaReserva, $notas);
        $ok = $stmt->execute();
        $stmt->close();
        $db->close();
        return $ok
            ? ['success' => true,  'message' => 'Reserva creada con éxito.']
            : ['success' => false, 'message' => 'Error al crear la reserva.'];
    }

    public function actualizarEstado(int $reservaId, string $estado): array {
        $permitidos = ['pendiente', 'confirmada', 'cancelada', 'completada'];
        if (!in_array($estado, $permitidos, true)) {
            return ['success' => false, 'message' => 'Estado no válido.'];
        }
        $db   = getDB();
        $stmt = $db->prepare('UPDATE reservas SET estado = ? WHERE id = ?');
        $stmt->bind_param('si', $estado, $reservaId);
        $ok = $stmt->execute();
        $stmt->close();
        $db->close();
        return $ok
            ? ['success' => true,  'message' => 'Estado actualizado.']
            : ['success' => false, 'message' => 'Error al actualizar.'];
    }

    public function listarTodas(): array {
        $db   = getDB();
        $stmt = $db->prepare(
            'SELECT r.id, s.nombre AS servicio, r.fecha_reserva, r.estado,
                    c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
                    p.nombre AS proveedor_nombre, p.apellido AS proveedor_apellido
             FROM reservas r
             JOIN servicios s ON r.servicio_id  = s.id
             JOIN usuarios  c ON r.cliente_id   = c.id
             JOIN usuarios  p ON s.proveedor_id = p.id
             ORDER BY r.fecha_reserva DESC'
        );
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return $rows;
    }
}
