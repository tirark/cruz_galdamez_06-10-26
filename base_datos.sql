CREATE DATABASE IF NOT EXISTS universidad_2025
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE universidad_2025;

CREATE TABLE IF NOT EXISTS carreras (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS estudiantes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    telefono VARCHAR(20) NULL,
    fecha_nacimiento DATE NULL,
    carrera_id INT UNSIGNED NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_estudiantes_carreras FOREIGN KEY (carrera_id)
      REFERENCES carreras(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT IGNORE INTO carreras (nombre, descripcion) VALUES
('Ingeniería en Sistemas', 'Desarrollo de software y sistemas informáticos'),
('Administración de Empresas', 'Gestión y administración empresarial');
