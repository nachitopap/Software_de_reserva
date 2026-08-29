<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';
require_once __DIR__ . '/../../app/controllers/ServicioController.php';

$pageAlerts = [];
$servicios = (new ServicioController())->listarActivos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['user_id'])) {
        setFlashAlert('error', 'Debes iniciar sesión para confirmar la reserva.');
        header('Location: ' . appPath('public/login.php'));
        exit;
    }

    $ctrl   = new ReservaController();
    $result = $ctrl->crear(
        (int)$_SESSION['user_id'],
        (int)($_POST['servicio_id'] ?? 0),
        trim($_POST['fecha_reserva'] ?? ''),
        trim($_POST['notas'] ?? '')
    );
    if ($result['success']) {
        setFlashAlert('success', $result['message']);
        header('Location: ' . appPath('public/cliente/dashboard.php'));
        exit;
    }
    $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
}

include __DIR__ . '/../../app/views/cliente/nueva_reserva.php';
