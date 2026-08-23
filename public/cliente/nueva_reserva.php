<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';
require_once __DIR__ . '/../../app/controllers/ServicioController.php';

AuthController::requireAuth(ROLE_CLIENTE);

$error    = '';
$servicios = (new ServicioController())->listarActivos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl   = new ReservaController();
    $result = $ctrl->crear(
        (int)$_SESSION['user_id'],
        (int)($_POST['servicio_id'] ?? 0),
        trim($_POST['fecha_reserva'] ?? ''),
        trim($_POST['notas'] ?? '')
    );
    if ($result['success']) {
        header('Location: ' . APP_URL . '/public/cliente/dashboard.php');
        exit;
    }
    $error = $result['message'];
}

include __DIR__ . '/../../app/views/cliente/nueva_reserva.php';
