<?php
// Configuración general de la aplicación
define('APP_NAME', 'Software de Reservas');
define('APP_URL', 'http://localhost/Software_de_reserva');
define('APP_VERSION', '1.0.0');

// Roles disponibles
define('ROLE_CLIENTE',   'cliente');
define('ROLE_PROVEEDOR', 'proveedor');
define('ROLE_ADMIN',     'admin');

// Inicio de sesión seguro
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function appDashboardUrlForRole(string $role = ''): string {
    switch ($role) {
        case ROLE_ADMIN:
            return APP_URL . '/public/admin/dashboard.php';
        case ROLE_PROVEEDOR:
            return APP_URL . '/public/proveedor/dashboard.php';
        default:
            return APP_URL . '/public/cliente/dashboard.php';
    }
}

function enforcePublicAccessPolicy(): void {
    if (PHP_SAPI === 'cli') {
        return;
    }

    $currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $guestAllowedPages = ['index.php', 'login.php', 'register.php', 'logout.php'];

    if (empty($_SESSION['user_id'])) {
        if (!in_array($currentScript, $guestAllowedPages, true)) {
            header('Location: ' . APP_URL . '/public/login.php');
            exit;
        }

        return;
    }

    if (in_array($currentScript, ['index.php', 'login.php', 'register.php'], true)) {
        header('Location: ' . appDashboardUrlForRole($_SESSION['user_role'] ?? ''));
        exit;
    }
}

enforcePublicAccessPolicy();
