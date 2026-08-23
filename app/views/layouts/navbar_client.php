<?php
require_once __DIR__ . '/../../../config/funciones.php';
$role = $_SESSION['user_role'] ?? '';
$name = $_SESSION['user_name'] ?? 'Usuario';
$isLoggedIn = !empty($_SESSION['user_id']);

$dashboardUrl = $isLoggedIn ? appDashboardUrlForRole($role) : appPath('index.php');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= htmlspecialchars($dashboardUrl) ?>">
            <i class="bi bi-calendar3" aria-hidden="true"></i>
            <span>Software de Reservas</span>
        </a>
    </div>
    <div class="navbar-user">
        <?php if ($isLoggedIn): ?>
            <span class="navbar-user-name">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
                <span><?= htmlspecialchars($name) ?></span>
            </span>
            <a href="<?= htmlspecialchars(appPath('public/logout.php')) ?>" class="btn btn-sm btn-secondary">
                <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                <span>Cerrar sesión</span>
            </a>
        <?php else: ?>
            <a href="<?= htmlspecialchars(appPath('public/login.php')) ?>" class="btn btn-sm btn-secondary">
                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                <span>Iniciar sesión</span>
            </a>
            <a href="<?= htmlspecialchars(appPath('app/views/public/formulario.php')) ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus" aria-hidden="true"></i>
                <span>Crear cuenta</span>
            </a>
        <?php endif; ?>
    </div>
</nav>
