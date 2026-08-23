<?php
$role = $_SESSION['user_role'] ?? '';
$currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');

$menuItems = [];
$roleLabel = 'Cliente';

switch ($role) {
    case ROLE_ADMIN:
        $roleLabel = 'Administrador';
        $menuItems = [
            [
                'label' => 'Resumen general',
                'description' => 'Ver reservas, servicios y totales.',
                'icon' => 'bi-speedometer2',
                'href' => appPath('public/admin/dashboard.php')
            ],
            [
                'label' => 'Gestionar usuarios',
                'description' => 'Activar, desactivar y revisar cuentas.',
                'icon' => 'bi-people-fill',
                'href' => appPath('public/admin/usuarios.php')
            ],
            [
                'label' => 'Gestionar servicios',
                'description' => 'Activar, desactivar y revisar servicios.',
                'icon' => 'bi-gear-fill',
                'href' => appPath('public/admin/servicios.php')
            ],
            
        ];
        break;
    case ROLE_PROVEEDOR:
        $roleLabel = 'Proveedor';
        $menuItems = [
            [
                'label' => 'Agenda de reservas',
                'description' => 'Confirmar, cancelar o completar turnos.',
                'icon' => 'bi-calendar-check',
                'href' => appPath('public/proveedor/dashboard.php')
            ],
            [
                'label' => 'Catalogo de servicios',
                'description' => 'Crear y administrar tus servicios.',
                'icon' => 'bi-scissors',
                'href' => appPath('public/proveedor/mis_servicios.php')
            ],
        ];
        break;
    default:
        $menuItems = [
            [
                'label' => 'Mis reservas',
                'description' => 'Consultar el estado de tus turnos.',
                'icon' => 'bi-journal-check',
                'href' => appPath('public/cliente/dashboard.php')
            ],
            [
                'label' => 'Reservar un servicio',
                'description' => 'Elegir proveedor, fecha y horario.',
                'icon' => 'bi-stars',
                'href' => appPath('public/cliente/nueva_reserva.php')
            ],
        ];
        break;
}
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div>
            <span class="sidebar-kicker">Navegacion</span>
            <span class="sidebar-title">Menu principal</span>
        </div>
        <span class="badge badge-<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($roleLabel) ?></span>
    </div>

    <nav class="sidebar-nav" aria-label="Menu lateral">
        <?php foreach ($menuItems as $item): ?>
            <?php $isActive = basename($item['href']) === $currentScript; ?>
            <a href="<?= htmlspecialchars($item['href']) ?>" class="sidebar-link<?= $isActive ? ' is-active' : '' ?>">
                <i class="bi <?= htmlspecialchars($item['icon']) ?> sidebar-icon" aria-hidden="true"></i>
                <span class="sidebar-copy">
                    <span class="sidebar-label"><?= htmlspecialchars($item['label']) ?></span>
                    <span class="sidebar-description"><?= htmlspecialchars($item['description']) ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
