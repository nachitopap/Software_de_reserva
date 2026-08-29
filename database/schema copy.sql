-- ============================================================
-- Base de datos: software_reserva_barberias
-- ============================================================

CREATE DATABASE IF NOT EXISTS srb
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE srb;

-- ------------------------------------------------------------
-- Tabla: proveedores
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS proveedores (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL UNIQUE,
    descripcion  TEXT,
    activo       TINYINT(1) NOT NULL DEFAULT 1,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: roles
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS roles (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(50) NOT NULL UNIQUE,
    descripcion  TEXT,
    proveedor_id INT NOT NULL,
    activo       TINYINT(1) NOT NULL DEFAULT 1,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_roles_proveedor FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    rut           VARBINARY(255) NOT NULL,
    rut_hash      BINARY(32) NOT NULL UNIQUE,
    nombre        VARCHAR(50) NOT NULL,
    sobrenombre   VARCHAR(50),
    apellidop     VARCHAR(50) NOT NULL,
    apellidom     VARCHAR(50) NOT NULL,
    telefono      VARCHAR(30),
    email         VARCHAR(150),
    password      VARCHAR(255),
    rol_id        INT NOT NULL,
    proveedor_id  INT NOT NULL,
    activo        TINYINT(1) NOT NULL DEFAULT 1,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario_rol FOREIGN KEY (rol_id)
        REFERENCES roles(id) ON DELETE RESTRICT,
    CONSTRAINT fk_usuario_proveedor FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: servicios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS servicios (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    proveedor_id  INT NOT NULL,
    nombre        VARCHAR(200) NOT NULL,
    descripcion   TEXT,
    precio        DECIMAL(12,0) NOT NULL DEFAULT 0,
    duracion_min  INT NOT NULL DEFAULT 60 COMMENT 'Duración en minutos',
    activo        TINYINT(1) NOT NULL DEFAULT 1,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_servicio_proveedor FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: horarios_servicios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS horarios_servicios (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    servicio_id   INT NOT NULL,
    proveedor_id  INT NOT NULL,
    usuario_id    INT NOT NULL,
    dia_semana    ENUM('lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo') NOT NULL,
    hora_inicio   TIME NOT NULL,
    hora_fin      TIME NOT NULL,
    activo        TINYINT(1) NOT NULL DEFAULT 1,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_horario_servicio FOREIGN KEY (servicio_id)
        REFERENCES servicios(id) ON DELETE CASCADE,
    CONSTRAINT fk_horario_proveedor FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id) ON DELETE CASCADE,
    CONSTRAINT fk_horario_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT chk_horario_rango_horas CHECK (hora_inicio < hora_fin),
    CONSTRAINT uq_horario_servicio_dia_hora UNIQUE (servicio_id, proveedor_id, usuario_id, dia_semana, hora_inicio, hora_fin)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: reservas
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reservas (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id     INT NOT NULL,
    servicio_id    INT NOT NULL,
    proveedor_id   INT NOT NULL,
    fecha_reserva  DATETIME NOT NULL,
    estado         ENUM('pendiente', 'confirmada', 'cancelada', 'completada') NOT NULL DEFAULT 'pendiente',
    hora_reserva   TIME NOT NULL,
    notas          TEXT,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reserva_cliente FOREIGN KEY (cliente_id)
        REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_reserva_servicio FOREIGN KEY (servicio_id)
        REFERENCES servicios(id) ON DELETE CASCADE,
    CONSTRAINT fk_reserva_proveedor FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ------------------------------------------------------------
-- Tabla: logs_sistema
-- Guarda acciones e intentos realizados en el sistema
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS logs_sistema (
    id                 BIGINT AUTO_INCREMENT PRIMARY KEY,
    usuario_id         INT NULL,  -- NULL si no estaba autenticado (ej: login fallido)
    proveedor_id       INT NULL,  -- opcional, útil para multi-tenant
    accion             VARCHAR(100) NOT NULL,  -- LOGIN, LOGIN_FAIL, CREAR_RESERVA, etc.
    descripcion        TEXT NULL,              -- detalle libre del evento
    exito              TINYINT(1) NOT NULL DEFAULT 1, -- 1=ok, 0=falló
    codigo_http        SMALLINT NULL,          -- opcional (200, 401, 500...)
    metodo_http        VARCHAR(10) NULL,       -- GET, POST, PUT, DELETE
    endpoint           VARCHAR(255) NULL,      -- /api/login, /api/reservas
    ip_origen          VARCHAR(45) NOT NULL,   -- IPv4/IPv6
    dispositivo        VARCHAR(100) NULL,      -- móvil, desktop, tablet (si lo detectas)
    sistema_operativo  VARCHAR(100) NULL,      -- Windows, Android, iOS...
    navegador          VARCHAR(100) NULL,      -- Chrome, Safari, etc.
    user_agent         VARCHAR(500) NULL,      -- UA completo
    fecha_hora         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_logs_usuario_fecha (usuario_id, fecha_hora),
    INDEX idx_logs_accion_fecha (accion, fecha_hora),
    INDEX idx_logs_ip_fecha (ip_origen, fecha_hora),
    INDEX idx_logs_exito_fecha (exito, fecha_hora),

    CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE SET NULL,
    CONSTRAINT fk_logs_proveedor FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id) ON DELETE SET NULL
) ENGINE=InnoDB;



-- ------------------------------------------------------------
-- Datos iniciales
-- ------------------------------------------------------------
INSERT INTO proveedores (id, nombre, descripcion, activo)
VALUES (1, 'Proveedor General', 'Proveedor por defecto del sistema', 1);

INSERT INTO roles (id, nombre, descripcion, proveedor_id, activo)
VALUES
    (1, 'admin', 'Administrador del sistema', 1, 1),
    (2, 'proveedor', 'Proveedor de servicios', 1, 1),
    (3, 'cliente', 'Cliente que reserva servicios', 1, 1);

INSERT INTO usuarios (rut, rut_hash, nombre, sobrenombre, apellidop, apellidom, telefono, email, password, rol_id, proveedor_id)
VALUES (
    '11111111K',
    UNHEX(SHA2('11111111K', 256)),
    'Admin',
    'Adminsito',
    'Sistema',
    'Principal',
    '111111111',
    'admin@reservas.com',
    '$2y$12$CRIx8io0KnmYdhCsyM0mJOTFBGhMTOLNxM3y9q/kjz78O/W3P3BAC',
    1,
    1
);

-- clave: Admin1234!