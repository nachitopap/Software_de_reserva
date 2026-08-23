<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth   = new AuthController();
    $result = $auth->login(
        trim($_POST['email'] ?? ''),
        $_POST['password'] ?? ''
    );
    if ($result['success']) {
        switch ($result['role']) {
            case ROLE_ADMIN:
                header('Location: ' . APP_URL . '/public/admin/dashboard.php');
                break;
            case ROLE_PROVEEDOR:
                header('Location: ' . APP_URL . '/public/proveedor/dashboard.php');
                break;
            default:
                header('Location: ' . APP_URL . '/public/cliente/dashboard.php');
        }
        exit;
    }
    $error = $result['message'];
}

include __DIR__ . '/../app/views/auth/login.php';
