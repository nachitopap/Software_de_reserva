<?php
require_once __DIR__ . '/../config/config.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: ' . appDashboardUrlForRole($_SESSION['user_role'] ?? ''));
    exit;
}

header('Location: ' . APP_URL . '/public/login.php');
exit;
