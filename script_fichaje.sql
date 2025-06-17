-- Script SQL para el sistema de fichaje (MySQL)

-- Crear tabla empleados
CREATE TABLE empleados (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo_electronico VARCHAR(255) NOT NULL UNIQUE,
    puesto VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Crear tabla registro_asistencias
CREATE TABLE registro_asistencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    empleado_id BIGINT UNSIGNED NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_salida TIME DEFAULT NULL,
    total_horas DECIMAL(5,2) DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
);

-- Insertar empleados de ejemplo
INSERT INTO empleados (nombre, correo_electronico, puesto, created_at, updated_at) VALUES
('María García', 'maria.garcia@consultora.com', 'Desarrollador Backend', NOW(), NOW()),
('Juan Pérez', 'juan.perez@consultora.com', 'Desarrollador Frontend', NOW(), NOW()),
('Lucía Fernández', 'lucia.fernandez@consultora.com', 'Analista Funcional', NOW(), NOW()),
('Carlos López', 'carlos.lopez@consultora.com', 'Project Manager', NOW(), NOW()),
('Ana Torres', 'ana.torres@consultora.com', 'QA Tester', NOW(), NOW()),
('Miguel Sánchez', 'miguel.sanchez@consultora.com', 'DevOps Engineer', NOW(), NOW());

-- Insertar registros de asistencia de ejemplo
INSERT INTO registro_asistencias (empleado_id, fecha, hora_entrada, hora_salida, total_horas, created_at, updated_at) VALUES
(1, '2024-06-10', '08:00:00', '16:00:00', 8.00, NOW(), NOW()),
(1, '2024-06-11', '08:10:00', '16:05:00', 7.92, NOW(), NOW()),
(2, '2024-06-11', '09:00:00', NULL, NULL, NOW(), NOW()); 