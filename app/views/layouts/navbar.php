<?php
require_once __DIR__ . '/../../../config/funciones.php';
$role = $_SESSION['user_role'] ?? '';
$name = $_SESSION['user_name'] ?? 'Usuario';

$dashboardUrl = appDashboardUrlForRole($role);
?>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= htmlspecialchars($dashboardUrl) ?>">
            <i class="bi bi-calendar3" aria-hidden="true"></i>
            <span>Software de Reservas</span>
        </a>
    </div>
    <div class="navbar-user">
        <span class="navbar-user-name">
            <i class="bi bi-person-circle" aria-hidden="true"></i>
            <span><?= htmlspecialchars($name) ?></span>
        </span>
        <span class="badge badge-<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></span>
        <a href="<?= htmlspecialchars(appPath('public/logout.php')) ?>" class="btn btn-sm btn-secondary">
            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
            <span>Salir</span>
        </a>
    </div>
</nav>
