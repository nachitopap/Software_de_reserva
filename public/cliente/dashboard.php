<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';

AuthController::requireAuth(ROLE_CLIENTE);

$ctrl    = new ReservaController();
$reservas = $ctrl->listarPorCliente((int)$_SESSION['user_id']);

include __DIR__ . '/../../app/views/cliente/dashboard.php';
