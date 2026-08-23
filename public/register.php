<?php
require_once __DIR__ . '/../config/funciones.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth   = new AuthController();
    $result = $auth->register([
        'nombre'   => trim($_POST['nombre']   ?? ''),
        'apellido' => trim($_POST['apellido'] ?? ''),
        'email'    => trim($_POST['email']    ?? ''),
        'password' => $_POST['password']      ?? '',
        'rol'      => $_POST['rol']           ?? '',
    ]);
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['message'];
    }
}

include __DIR__ . '/../app/views/auth/register.php';
