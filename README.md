# Software de Reservas

Sistema web de reservas con tres roles: **Cliente**, **Proveedor** y **Admin**.

## Tecnologías
- **Backend:** PHP (sin framework)
- **Frontend:** HTML, CSS puro, JavaScript vanilla
- **Configuración:** JSON
- **Base de datos:** MySQL

## Estructura del proyecto

```
Software_de_reserva/
├── config/
│   ├── funciones.php       # Constantes globales y arranque de sesión
│   ├── config.php          # Conexión MySQL (ajustar credenciales)
│   └── app.json            # Configuración en JSON
├── database/
│   └── schema.sql          # Esquema y datos iniciales de la BD
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ReservaController.php
│   │   └── ServicioController.php
│   └── views/
│       ├── auth/           # login.php, register.php
│       ├── layouts/        # navbar.php
│       ├── cliente/        # dashboard, nueva_reserva
│       ├── proveedor/      # dashboard, mis_servicios
│       └── admin/          # dashboard, usuarios
└── public/                 # Punto de entrada accesible por el servidor web
    ├── index.php
    ├── login.php
    ├── register.php
    ├── logout.php
    ├── acceso_denegado.php
    ├── css/styles.css
    ├── js/app.js
    ├── cliente/
    ├── proveedor/
    └── admin/
```

## Instalación rápida

1. Importar `database/schema.sql` en MySQL.
2. Ajustar `config/config.php` con `DB_HOST`, `DB_USER`, `DB_PASS` y `DB_NAME`.
3. Apuntar el servidor web (Apache/Nginx) a la carpeta `public/`.
4. Navegar a `http://localhost/Software_de_reserva/public/`.

## Roles

| Rol        | Puede hacer                                         |
|------------|-----------------------------------------------------|
| Cliente    | Registrarse, ver y crear reservas, cancelar propias |
| Proveedor  | Publicar servicios, confirmar/completar reservas    |
| Admin      | Ver todas las reservas y gestionar usuarios         |
