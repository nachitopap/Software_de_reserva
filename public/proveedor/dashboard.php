<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';

AuthController::requireAuth(ROLE_PROVEEDOR);

$ctrl    = new ReservaController();
$reservas = $ctrl->listarPorProveedor((int)$_SESSION['user_id']);
$message  = '';

include __DIR__ . '/../../app/views/proveedor/dashboard.php';
