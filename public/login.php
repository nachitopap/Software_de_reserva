<?php
require_once __DIR__ . '/../config/funciones.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$pageAlerts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth   = new AuthController();
    $result = $auth->login(
        trim($_POST['email'] ?? ''),
        $_POST['password'] ?? ''
    );
    if ($result['success']) {
        header('Location: ' . appDashboardUrlForRole($result['role']));
        exit;
    }
    $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
}

include __DIR__ . '/../app/views/auth/login.php';
