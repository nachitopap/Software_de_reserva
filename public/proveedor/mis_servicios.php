<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ServicioController.php';

AuthController::requireAuth(ROLE_PROVEEDOR);

$ctrl     = new ServicioController();
$error    = '';
$message  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $ctrl->crear((int)$_SESSION['user_id'], [
        'nombre'      => trim($_POST['nombre']      ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'precio'      => $_POST['precio']           ?? 0,
        'duracion_min'=> (int)($_POST['duracion_min'] ?? 60),
    ]);
    if ($result['success']) {
        $message = $result['message'];
    } else {
        $error = $result['message'];
    }
}

$servicios = $ctrl->listarPorProveedor((int)$_SESSION['user_id']);

include __DIR__ . '/../../app/views/proveedor/mis_servicios.php';
