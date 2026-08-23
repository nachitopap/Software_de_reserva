-- ============================================================
-- Base de datos: software_reserva
-- ============================================================

CREATE DATABASE IF NOT EXISTS software_reserva
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE software_reserva;

-- ------------------------------------------------------------
-- Tabla: usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    apellido    VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    rol         ENUM('cliente', 'proveedor', 'admin') NOT NULL DEFAULT 'cliente',
    activo      TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: servicios  (publicados por proveedores)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS servicios (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proveedor_id    INT UNSIGNED NOT NULL,
    nombre          VARCHAR(200) NOT NULL,
    descripcion     TEXT,
    precio          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    duracion_min    INT NOT NULL DEFAULT 60 COMMENT 'Duración en minutos',
    activo          TINYINT(1) NOT NULL DEFAULT 1,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_servicio_proveedor FOREIGN KEY (proveedor_id)
        REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: reservas
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reservas (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id      INT UNSIGNED NOT NULL,
    servicio_id     INT UNSIGNED NOT NULL,
    fecha_reserva   DATETIME NOT NULL,
    estado          ENUM('pendiente', 'confirmada', 'cancelada', 'completada') NOT NULL DEFAULT 'pendiente',
    notas           TEXT,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reserva_cliente  FOREIGN KEY (cliente_id)
        REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_reserva_servicio FOREIGN KEY (servicio_id)
        REFERENCES servicios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Usuario admin por defecto  (password: Admin1234!)
-- ------------------------------------------------------------
INSERT INTO usuarios (nombre, apellido, email, password, rol)
VALUES (
    'Admin',
    'Sistema',
    'admin@reservas.com',
    '$2y$12$eImiTXuWVxfM37uY4JANjQ==',  -- reemplazar con hash real
    'admin'
);
