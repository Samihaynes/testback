-- ===========================================
-- MECALINK DATABASE SCHEMA
-- Base de datos completa para la plataforma MecaLink
-- ===========================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS mecalink;
USE mecalink;

-- ===========================================
-- TABLA: Usuarios
-- ===========================================
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'mecanico', 'admin', 'taller') DEFAULT 'usuario',
    nombre VARCHAR(100), -- Nombre completo del usuario
    apellido VARCHAR(100), -- Apellido del usuario
    telefono VARCHAR(20), -- Teléfono del usuario
    pais VARCHAR(50), -- País del usuario
    ciudad VARCHAR(50), -- Ciudad del usuario
    especialidad VARCHAR(100), -- Especialidad del mecánico/taller
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_ultimo_acceso TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- ===========================================
-- TABLA: Vehículos
-- ===========================================
CREATE TABLE IF NOT EXISTS vehiculos (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    vin VARCHAR(17) NOT NULL UNIQUE,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    ano INT,
    motor VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- TABLA: Consultas
-- ===========================================
CREATE TABLE IF NOT EXISTS consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_vehiculo INT,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    estado ENUM('pendiente', 'abierta', 'cerrada', 'resuelta') DEFAULT 'pendiente',
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    attachments JSON NULL COMMENT 'File paths for uploaded attachments (photos/videos)',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id_vehiculo) ON DELETE SET NULL
);

-- ===========================================
-- TABLA: Respuestas
-- ===========================================
CREATE TABLE IF NOT EXISTS respuestas (
    id_respuesta INT AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT NOT NULL,
    id_usuario INT NOT NULL,
    descripcion_respuesta TEXT NOT NULL,
    es_solucion BOOLEAN DEFAULT FALSE,
    fecha_respuesta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    attachments JSON NULL COMMENT 'File paths for uploaded attachments (photos/PDFs/videos)',
    FOREIGN KEY (id_consulta) REFERENCES consultas(id_consulta) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ===========================================
-- TABLA: Votos
-- ===========================================
CREATE TABLE IF NOT EXISTS votos (
    id_voto INT AUTO_INCREMENT PRIMARY KEY,
    id_respuesta INT NOT NULL,
    id_usuario INT NOT NULL,
    tipo_voto ENUM('up', 'down') NOT NULL,
    fecha_voto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_voto (id_respuesta, id_usuario),
    FOREIGN KEY (id_respuesta) REFERENCES respuestas(id_respuesta) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ===========================================
-- TABLA: Artículos
-- ===========================================
CREATE TABLE IF NOT EXISTS articulos (
    id_articulo INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT NOT NULL,
    titulo_articulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    contenido TEXT,
    categoria_articulo VARCHAR(50) NOT NULL,
    estado ENUM('pendiente', 'publicada', 'rechazada') DEFAULT 'pendiente',
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ===========================================
-- TABLA: Comentarios de Artículos
-- ===========================================
CREATE TABLE IF NOT EXISTS comentarios_articulo (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_articulo INT NOT NULL,
    id_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ===========================================
-- ÍNDICES PARA OPTIMIZACIÓN
-- ===========================================
CREATE INDEX idx_consultas_usuario ON consultas(id_usuario);
CREATE INDEX idx_consultas_estado ON consultas(estado);
CREATE INDEX idx_consultas_fecha ON consultas(fecha_publicacion);
CREATE INDEX idx_respuestas_consulta ON respuestas(id_consulta);
CREATE INDEX idx_votos_respuesta ON votos(id_respuesta);
CREATE INDEX idx_articulos_admin ON articulos(id_admin);
CREATE INDEX idx_comentarios_articulo ON comentarios_articulo(id_articulo);

-- ===========================================
-- DATOS DE EJEMPLO 
-- ===========================================

-- Usuario Admin
INSERT INTO usuarios (nombre_usuario, email, password_hash, rol, nombre) VALUES
('admin', 'admin@mecalink.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrador');

-- Usuario Mecánico
INSERT INTO usuarios (nombre_usuario, email, password_hash, rol, nombre, especialidad) VALUES
('mecanico1', 'mecanico@mecalink.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mecanico', 'Taller Central', 'Motor y Electricidad');

-- Usuario Normal
INSERT INTO usuarios (nombre_usuario, email, password_hash, rol, nombre, apellido) VALUES
('usuario1', 'usuario@mecalink.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 'Juan', 'Pérez');

-- Vehículo de ejemplo
INSERT INTO vehiculos (vin, marca, modelo, ano, motor) VALUES
('1HGCM82633A123456', 'Honda', 'Civic', 2020, '1.5L Turbo'),
('JH4KA8260MC000000', 'Acura', 'Legend', 1995, '3.2L V6');

-- Consulta de ejemplo
INSERT INTO consultas (id_usuario, id_vehiculo, titulo, descripcion, categoria) VALUES
(3, 1, 'Problema con el motor', 'El coche hace un ruido extraño al acelerar. ¿Qué puede ser?', 'Motor');

-- Respuesta de ejemplo
INSERT INTO respuestas (id_consulta, id_usuario, descripcion_respuesta) VALUES
(1, 2, 'Parece un problema con las bujías. Te recomiendo revisarlas primero.');

-- Artículo de ejemplo
INSERT INTO articulos (id_admin, titulo_articulo, descripcion, contenido, categoria_articulo, estado) VALUES
(1, 'Mantenimiento Básico del Motor', 'Guía completa para el mantenimiento básico del motor de tu vehículo', 'El mantenimiento regular del motor es crucial para la longevidad del vehículo...', 'Motor', 'publicada');

-- ===========================================
-- PROCEDIMIENTOS ALMACENADOS ÚTILES
-- ===========================================

-- Procedimiento para obtener estadísticas del foro
DELIMITER //
CREATE PROCEDURE GetForumStats()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM usuarios) as total_usuarios,
        (SELECT COUNT(*) FROM consultas) as total_consultas,
        (SELECT COUNT(*) FROM respuestas) as total_respuestas,
        (SELECT COUNT(*) FROM articulos) as total_articulos;
END //
DELIMITER ;

-- Procedimiento para marcar respuesta como solución
DELIMITER //
CREATE PROCEDURE MarcarSolucion(IN respuesta_id INT)
BEGIN
    -- Primero quitar marca de solución de otras respuestas de la misma consulta
    UPDATE respuestas
    SET es_solucion = FALSE
    WHERE id_consulta = (SELECT id_consulta FROM respuestas WHERE id_respuesta = respuesta_id);

    -- Marcar la respuesta específica como solución
    UPDATE respuestas
    SET es_solucion = TRUE
    WHERE id_respuesta = respuesta_id;

    -- Cerrar la consulta
    UPDATE consultas
    SET estado = 'resuelta'
    WHERE id_consulta = (SELECT id_consulta FROM respuestas WHERE id_respuesta = respuesta_id);
END //
DELIMITER ;

-- ===========================================
-- VISTAS ÚTILES
-- ===========================================

-- Vista para consultas con detalles completos
CREATE VIEW vista_consultas_completas AS
SELECT
    c.id_consulta,
    c.titulo,
    c.descripcion,
    c.categoria,
    c.estado,
    c.fecha_publicacion,
    u.nombre_usuario,
    u.email,
    v.marca,
    v.modelo,
    v.ano,
    v.motor,
    v.vin,
    (SELECT COUNT(*) FROM respuestas r WHERE r.id_consulta = c.id_consulta) as num_respuestas,
    (SELECT COUNT(*) FROM votos WHERE id_respuesta IN (SELECT id_respuesta FROM respuestas WHERE id_consulta = c.id_consulta)) as num_votos
FROM consultas c
LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
LEFT JOIN vehiculos v ON c.id_vehiculo = v.id_vehiculo;

-- Vista para respuestas con votos
CREATE VIEW vista_respuestas_completas AS
SELECT
    r.id_respuesta,
    r.descripcion_respuesta,
    r.es_solucion,
    r.fecha_respuesta,
    u.nombre_usuario,
    u.email,
    c.titulo as titulo_consulta,
    COALESCE(SUM(CASE WHEN v.tipo_voto = 'up' THEN 1 ELSE 0 END), 0) as votos_up,
    COALESCE(SUM(CASE WHEN v.tipo_voto = 'down' THEN 1 ELSE 0 END), 0) as votos_down
FROM respuestas r
LEFT JOIN usuarios u ON r.id_usuario = u.id_usuario
LEFT JOIN consultas c ON r.id_consulta = c.id_consulta
LEFT JOIN votos v ON r.id_respuesta = v.id_respuesta
GROUP BY r.id_respuesta, r.descripcion_respuesta, r.es_solucion, r.fecha_respuesta, u.nombre_usuario, u.email, c.titulo;
