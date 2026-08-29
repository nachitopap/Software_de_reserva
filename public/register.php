<?php
require_once __DIR__ . '/../config/funciones.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$pageAlerts = [];

if ($flashAlert = consumeFlashAlert()) {
	$pageAlerts[] = $flashAlert;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$result = (new AuthController())->register([
		'rut' => trim($_POST['rut'] ?? ''),
		'nombre' => trim($_POST['nombre'] ?? ''),
		'sobrenombre' => trim($_POST['sobrenombre'] ?? ''),
		'apellidop' => trim($_POST['apellidop'] ?? ''),
		'apellidom' => trim($_POST['apellidom'] ?? ''),
		'email' => trim($_POST['email'] ?? ''),
		'password' => $_POST['password'] ?? '',
		'rol' => trim($_POST['rol'] ?? ''),
	]);

	if ($result['success']) {
		setFlashAlert('success', $result['message']);
		header('Location: ' . appPath('public/login.php'));
		exit;
	}

	$pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
}

include __DIR__ . '/../app/views/public/register.php';
