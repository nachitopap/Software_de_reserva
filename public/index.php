<?php
require_once __DIR__ . '/../config/funciones.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: ' . appDashboardUrlForRole($_SESSION['user_role'] ?? ''));
    exit;
}

header('Location: ' . appPath('public/login.php'));
exit;
