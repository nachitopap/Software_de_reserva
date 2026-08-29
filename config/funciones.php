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

function normalizeRutValue(string $rut): string {
    $normalized = strtoupper(trim($rut));
    return preg_replace('/[^0-9K]/', '', $normalized) ?? '';
}

function buildPersonDisplayName(array $user): string {
    return trim(implode(' ', array_filter([
        trim((string)($user['nombre'] ?? '')),
        trim((string)($user['apellidop'] ?? '')),
        trim((string)($user['apellidom'] ?? '')),
    ])));
}

function setFlashAlert(string $type, string $message): void {
    $_SESSION['flash_alert'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function consumeFlashAlert(): ?array {
    if (empty($_SESSION['flash_alert']) || !is_array($_SESSION['flash_alert'])) {
        return null;
    }

    $alert = $_SESSION['flash_alert'];
    unset($_SESSION['flash_alert']);
    return $alert;
}

function appCipherKey(): string {
    return hash('sha256', APP_NAME . '|' . DB_HOST . '|' . DB_NAME . '|' . DB_USER, true);
}

function encryptSensitiveValue(string $value): string {
    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
    $iv = random_bytes($ivLength);
    $ciphertext = openssl_encrypt($value, 'aes-256-cbc', appCipherKey(), OPENSSL_RAW_DATA, $iv);

    if ($ciphertext === false) {
        throw new RuntimeException('No se pudo cifrar el valor sensible.');
    }

    return base64_encode($iv . $ciphertext);
}

function decryptSensitiveValue(string $value): string {
    $decoded = base64_decode($value, true);
    if ($decoded === false) {
        return $value;
    }

    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
    if (strlen($decoded) <= $ivLength) {
        return $value;
    }

    $iv = substr($decoded, 0, $ivLength);
    $ciphertext = substr($decoded, $ivLength);
    $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', appCipherKey(), OPENSSL_RAW_DATA, $iv);

    return $plaintext === false ? $value : $plaintext;
}

function enforcePublicAccessPolicy(): void {
    if (PHP_SAPI === 'cli') {
        return;
    }

    $currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $guestAllowedPages = ['index.php', 'login.php', 'logout.php', 'formulario.php', 'calendario.php', 'nueva_reserva.php', 'inicio.php'];

    if (empty($_SESSION['user_id'])) {
        if (!in_array($currentScript, $guestAllowedPages, true)) {
            header('Location: ' . appPath('public/login.php'));
            exit;
        }

        return;
    }

    if (in_array($currentScript, ['login.php', 'register.php', 'formulario.php', 'calendario.php'], true)) {
        header('Location: ' . appDashboardUrlForRole($_SESSION['user_role'] ?? ''));
        exit;
    }
}

enforcePublicAccessPolicy();
