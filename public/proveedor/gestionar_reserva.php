<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';

AuthController::requireAuth(ROLE_PROVEEDOR);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl = new ReservaController();
    $ctrl->actualizarEstado((int)($_POST['reserva_id'] ?? 0), $_POST['estado'] ?? '');
}

header('Location: ' . appPath('public/proveedor/dashboard.php'));
exit;
