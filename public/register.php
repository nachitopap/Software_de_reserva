<?php
require_once __DIR__ . '/../config/funciones.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth   = new AuthController();
    $result = $auth->register([
        'rut'         => trim($_POST['rut'] ?? ''),
        'nombre'      => trim($_POST['nombre'] ?? ''),
        'sobrenombre' => trim($_POST['sobrenombre'] ?? ''),
        'apellidop'   => trim($_POST['apellidop'] ?? ''),
        'apellidom'   => trim($_POST['apellidom'] ?? ''),
        'email'       => trim($_POST['email'] ?? ''),
        'password'    => $_POST['password'] ?? '',
        'rol'         => $_POST['rol'] ?? '',
    ]);
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['message'];
    }
}

include __DIR__ . '/../app/views/auth/register.php';
