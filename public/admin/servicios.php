<?php
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/servicios-funciones.php';

AuthController::requireAuth(ROLE_ADMIN);

$pageAlerts = [];
$activeModal = '';
$createFormData = adminServiciosDefaultFormData();
$editFormData = adminServiciosDefaultFormData();
$statusFormData = ['id' => 0, 'nombre' => '', 'activo' => 1];

if ($flashAlert = consumeFlashAlert()) {
    $pageAlerts[] = $flashAlert;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create-service') {
        $result = adminServiciosCrear([
            'proveedor_id' => $_POST['proveedor_id'] ?? 0,
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'precio' => $_POST['precio'] ?? '',
            'duracion_min' => $_POST['duracion_min'] ?? 0,
        ]);

        if ($result['success']) {
            setFlashAlert('success', $result['message']);
            header('Location: servicios.php');
            exit;
        }

        $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
        $activeModal = 'create-service-modal';
        $createFormData = $result['formData'] ?? adminServiciosDefaultFormData();
    } elseif ($action === 'update-service') {
        $serviceId = (int)($_POST['servicio_id'] ?? 0);
        $result = adminServiciosActualizar($serviceId, [
            'proveedor_id' => $_POST['proveedor_id'] ?? 0,
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'precio' => $_POST['precio'] ?? '',
            'duracion_min' => $_POST['duracion_min'] ?? 0,
        ]);

        if ($result['success']) {
            setFlashAlert('success', $result['message']);
            header('Location: servicios.php');
            exit;
        }

        $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
        $activeModal = 'edit-service-modal';
        $editFormData = $result['formData'] ?? adminServiciosDefaultFormData();
        $editFormData['id'] = $serviceId;
    } elseif ($action === 'toggle-service-status') {
        $result = adminServiciosCambiarEstado((int)($_POST['servicio_id'] ?? 0));

        if ($result['success']) {
            setFlashAlert('success', $result['message']);
            header('Location: servicios.php');
            exit;
        }

        $pageAlerts[] = ['type' => 'error', 'message' => $result['message']];
        $activeModal = 'status-service-modal';
        $statusFormData = [
            'id' => (int)($_POST['servicio_id'] ?? 0),
            'nombre' => trim((string)($_POST['servicio_nombre'] ?? '')),
            'activo' => (int)($_POST['servicio_activo'] ?? 1),
        ];
    }
}

$proveedores = adminServiciosListarProveedores();
$servicios = adminServiciosListar();

include __DIR__ . '/../../app/views/admin/servicios.php';