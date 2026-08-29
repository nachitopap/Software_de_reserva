<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';

AuthController::requireAuth(ROLE_CLIENTE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl = new ReservaController();
    $result = $ctrl->actualizarEstado((int)($_POST['reserva_id'] ?? 0), 'cancelada');
    setFlashAlert($result['success'] ? 'success' : 'error', $result['message']);
}

header('Location: ' . appPath('public/cliente/dashboard.php'));
exit;
