<?php
require_once __DIR__ . '/../config/funciones.php';

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

    $_SESSION['guest_booking_profile'] = $guestFormData;
    setFlashAlert('success', 'Formulario completado. Ahora elige un día en el calendario.');
    header('Location: ' . appPath('public/calendario.php'));
    exit;
}

include __DIR__ . '/../app/views/public/formulario.php';
