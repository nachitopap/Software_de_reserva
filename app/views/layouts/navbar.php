<?php
require_once __DIR__ . '/../../../config/funciones.php';
$role = $_SESSION['user_role'] ?? '';
$name = $_SESSION['user_name'] ?? 'Usuario';

$dashboardUrl = appDashboardUrlForRole($role);
?>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= htmlspecialchars($dashboardUrl) ?>">🗓 Software de Reservas</a>
    </div>
    <div class="navbar-user">
        <span>👤 <?= htmlspecialchars($name) ?></span>
        <span class="badge badge-<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></span>
        <a href="<?= htmlspecialchars(appPath('public/logout.php')) ?>" class="btn btn-sm btn-secondary">Salir</a>
    </div>
</nav>
