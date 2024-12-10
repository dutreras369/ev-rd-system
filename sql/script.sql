CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL, -- Nombre completo del usuario
    username VARCHAR(50) NOT NULL UNIQUE, -- Nombre de usuario único
    email VARCHAR(100) UNIQUE, -- Correo electrónico único
    contrasena VARCHAR(255) NOT NULL, -- Contraseña encriptada
    rol ENUM('user', 'admin') DEFAULT 'user', -- Rol del usuario
    hora_inicio TIME NULL, -- Hora de inicio permitida para usuarios
    hora_fin TIME NULL, -- Hora de fin permitida para usuarios
    estado ENUM('activo', 'inactivo') DEFAULT 'activo', -- Estado del usuario
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP -- Fecha de creación
);

CREATE TABLE registros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('ingreso', 'egreso') NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    descripcion VARCHAR(255) DEFAULT NULL, -- Descripción opcional del registro
    fecha DATETIME NOT NULL,
    estado ENUM('pendiente', 'correcto', 'incorrecto') DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    accion VARCHAR(255) NOT NULL,
    usuario_id INT NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL, -- Dirección IP del usuario
    user_agent TEXT DEFAULT NULL, -- Información del navegador/dispositivo
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE configuraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descripcion VARCHAR(255) DEFAULT NULL
);

CREATE TABLE sesiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE, -- Token de sesión
    ip_address VARCHAR(45) DEFAULT NULL,
    inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    expiracion DATETIME DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
