<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

AuthController::requireAuth(ROLE_ADMIN);

$db      = getDB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid    = (int)($_POST['usuario_id'] ?? 0);
    // No permitir que el admin se desactive a sí mismo
    if ($uid && $uid !== (int)$_SESSION['user_id']) {
        $stmt = $db->prepare('UPDATE usuarios SET activo = NOT activo WHERE id = ?');
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $stmt->close();
        $message = 'Estado del usuario actualizado.';
    }
}

$result   = $db->query('SELECT id, nombre, apellido, email, rol, activo, created_at FROM usuarios ORDER BY created_at DESC');
$usuarios = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$db->close();

include __DIR__ . '/../../app/views/admin/usuarios.php';
