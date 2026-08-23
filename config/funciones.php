<?php
// Configuración general de la aplicación
define('APP_NAME', 'Software de Reservas');
define('APP_VERSION', '1.0.0');

$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
$projectRoot  = realpath(dirname(__DIR__));
$appBasePath  = '';

if ($documentRoot && $projectRoot && stripos($projectRoot, $documentRoot) === 0) {
    $appBasePath = str_replace('\\', '/', substr($projectRoot, strlen($documentRoot)));
}

$appBasePath = trim($appBasePath, '/');
define('APP_BASE_PATH', $appBasePath !== '' ? '/' . $appBasePath : '');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('APP_URL', $scheme . '://' . $host . APP_BASE_PATH);

// Roles disponibles
define('ROLE_CLIENTE',   'cliente');
define('ROLE_PROVEEDOR', 'proveedor');
define('ROLE_ADMIN',     'admin');

// Inicio de sesión seguro
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function appPath(string $path = ''): string {
    $normalizedPath = trim(str_replace('\\', '/', $path), '/');

    if ($normalizedPath === '') {
        return APP_BASE_PATH !== '' ? APP_BASE_PATH : '/';
    }

    return (APP_BASE_PATH !== '' ? APP_BASE_PATH : '') . '/' . $normalizedPath;
}

function appDashboardUrlForRole(string $role = ''): string {
    switch ($role) {
        case ROLE_ADMIN:
            return appPath('public/admin/dashboard.php');
        case ROLE_PROVEEDOR:
            return appPath('public/proveedor/dashboard.php');
        default:
            return appPath('public/cliente/dashboard.php');
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
            header('Location: ' . appPath('public/login.php'));
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
