<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';

AuthController::requireAuth(ROLE_CLIENTE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl = new ReservaController();
    $ctrl->actualizarEstado((int)($_POST['reserva_id'] ?? 0), 'cancelada');
}

header('Location: ' . appPath('public/cliente/dashboard.php'));
exit;
