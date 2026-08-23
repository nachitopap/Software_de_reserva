<?php
require_once __DIR__ . '/../config/funciones.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$pageAlerts = [];
$guestFormData = [
    'nombre' => '',
    'apellidos' => '',
    'telefono' => '',
    'email' => '',
];

if ($flashAlert = consumeFlashAlert()) {
    $pageAlerts[] = $flashAlert;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guestFormData = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'apellidos' => trim($_POST['apellidos'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
    ];

    foreach ($guestFormData as $field => $value) {
        if ($value === '') {
            $pageAlerts[] = ['type' => 'error', 'message' => 'Debes completar todos los campos del formulario.'];
            include __DIR__ . '/../app/views/public/formulario.php';
            return;
        }
    }

    if (!filter_var($guestFormData['email'], FILTER_VALIDATE_EMAIL)) {
        $pageAlerts[] = ['type' => 'error', 'message' => 'Debes ingresar un email válido.'];
        include __DIR__ . '/../app/views/public/formulario.php';
        return;
    }

    $apellidos = preg_split('/\s+/', $guestFormData['apellidos'], 2);
    $registro = (new AuthController())->register([
        'rut' => (string)random_int(10000000, 99999999),
        'nombre' => $guestFormData['nombre'],
        'apellidop' => $apellidos[0],
        'apellidom' => $apellidos[1] ?? 'Sin especificar',
        'telefono' => $guestFormData['telefono'],
        'email' => $guestFormData['email'],
        'password' => bin2hex(random_bytes(16)),
        'rol' => ROLE_CLIENTE,
    ]);

    if (!$registro['success']) {
        $pageAlerts[] = ['type' => 'error', 'message' => $registro['message']];
        include __DIR__ . '/../app/views/public/formulario.php';
        return;
    }

    $_SESSION['user_id'] = $registro['id'];
    $_SESSION['user_name'] = trim($guestFormData['nombre'] . ' ' . $guestFormData['apellidos']);
    $_SESSION['user_role'] = ROLE_CLIENTE;
    unset($_SESSION['guest_booking_profile']);
    setFlashAlert('success', 'Registro exitoso. Bienvenido a Software de Reservas.');
    header('Location: ' . appPath('index.php'));
    exit;
}

include __DIR__ . '/../app/views/public/formulario.php';
