<?php
// Configuración general de la aplicación
define('APP_NAME', 'Software de Reservas');
define('APP_URL', 'http://localhost/Software_de_reserva');
define('APP_VERSION', '1.0.0');

// Roles disponibles
define('ROLE_CLIENTE',   'cliente');
define('ROLE_PROVEEDOR', 'proveedor');
define('ROLE_ADMIN',     'admin');

// Inicio de sesión seguro
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
