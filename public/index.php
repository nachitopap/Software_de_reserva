<?php
require_once __DIR__ . '/../config/config.php';

if (!empty($_SESSION['user_id'])) {
    switch ($_SESSION['user_role']) {
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

header('Location: ' . APP_URL . '/public/login.php');
exit;
