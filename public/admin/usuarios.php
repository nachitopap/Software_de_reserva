<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/usuarios-funciones.php';

AuthController::requireAuth(ROLE_ADMIN);

$message = '';
$pageAlerts = [];
$activeModal = '';
$createFormData = adminUsuariosDefaultFormData();
$editFormData = adminUsuariosDefaultFormData();
$statusFormData = ['id' => 0, 'nombre_completo' => '', 'activo' => 1];

if ($flashAlert = consumeFlashAlert()) {
    $pageAlerts[] = $flashAlert;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'toggle-status') {
        $result = adminUsuariosToggleStatus((int)$_SESSION['user_id'], (int)($_POST['usuario_id'] ?? 0));
        if ($result['success']) {
            setFlashAlert('success', $result['message']);
            header('Location: usuarios.php');
            exit;
        }

        $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
        $activeModal = 'status-user-modal';
        $statusFormData = [
            'id' => (int)($_POST['usuario_id'] ?? 0),
            'nombre_completo' => trim((string)($_POST['usuario_nombre'] ?? '')),
            'activo' => (int)($_POST['usuario_activo'] ?? 1),
        ];
    } elseif ($action === 'create-user') {
        $result = adminUsuariosCreate([
            'rut' => $_POST['rut'] ?? '',
            'nombre' => $_POST['nombre'] ?? '',
            'sobrenombre' => $_POST['sobrenombre'] ?? '',
            'apellidop' => $_POST['apellidop'] ?? '',
            'apellidom' => $_POST['apellidom'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'rol' => $_POST['rol'] ?? ROLE_CLIENTE,
        ]);

        if ($result['success']) {
            setFlashAlert('success', $result['message']);
            header('Location: usuarios.php');
            exit;
        }

        $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
        $activeModal = 'create-user-modal';
        $createFormData = $result['formData'] ?? adminUsuariosDefaultFormData();
    } elseif ($action === 'update-user') {
        $editUserId = (int)($_POST['usuario_id'] ?? 0);
        $result = adminUsuariosUpdate($editUserId, [
            'rut' => $_POST['rut'] ?? '',
            'nombre' => $_POST['nombre'] ?? '',
            'sobrenombre' => $_POST['sobrenombre'] ?? '',
            'apellidop' => $_POST['apellidop'] ?? '',
            'apellidom' => $_POST['apellidom'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'rol' => $_POST['rol'] ?? ROLE_CLIENTE,
        ]);

        if ($result['success']) {
            setFlashAlert('success', $result['message']);
            header('Location: usuarios.php');
            exit;
        }

        $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
        $activeModal = 'edit-user-modal';
        $editFormData = $result['formData'] ?? adminUsuariosDefaultFormData();
        $editFormData['id'] = $editUserId;
    }
}

$usuarios = adminUsuariosList();

include __DIR__ . '/../../app/views/admin/usuarios.php';
