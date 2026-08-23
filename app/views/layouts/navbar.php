<?php
require_once __DIR__ . '/../../config/config.php';
$role = $_SESSION['user_role'] ?? '';
$name = $_SESSION['user_name'] ?? 'Usuario';

$dashboardUrl = APP_URL . '/public/';
if ($role === ROLE_ADMIN)     $dashboardUrl .= 'admin/dashboard.php';
elseif ($role === ROLE_PROVEEDOR) $dashboardUrl .= 'proveedor/dashboard.php';
else                          $dashboardUrl .= 'cliente/dashboard.php';
?>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= htmlspecialchars($dashboardUrl) ?>">🗓 Software de Reservas</a>
    </div>
    <div class="navbar-user">
        <span>👤 <?= htmlspecialchars($name) ?></span>
        <span class="badge badge-<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></span>
        <a href="<?= APP_URL ?>/public/logout.php" class="btn btn-sm btn-secondary">Salir</a>
    </div>
</nav>
