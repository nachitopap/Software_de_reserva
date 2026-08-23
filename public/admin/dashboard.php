<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/ReservaController.php';

AuthController::requireAuth(ROLE_ADMIN);

$db = getDB();

// Estadísticas básicas
$stats = [];
foreach ([
    'total_usuarios'  => 'SELECT COUNT(*) FROM usuarios',
    'total_reservas'  => 'SELECT COUNT(*) FROM reservas',
    'total_servicios' => 'SELECT COUNT(*) FROM servicios',
] as $key => $sql) {
    $res = $db->query($sql);
    $stats[$key] = $res ? (int)$res->fetch_row()[0] : 0;
}
$db->close();

$reservas = (new ReservaController())->listarTodas();

include __DIR__ . '/../../app/views/admin/dashboard.php';
