<?php

class User {
    public $id;
    public $nombre;
    public $username;
    public $email;
    public $contrasena; // Nueva propiedad para la contraseña
    public $rol;
    public $hora_inicio;
    public $hora_fin;
    public $estado;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->contrasena = $data['contrasena'] ?? null; // Inicializar contraseña (puede ser null para seguridad)
        $this->rol = $data['rol'] ?? 'user';
        $this->hora_inicio = $data['hora_inicio'] ?? null;
        $this->hora_fin = $data['hora_fin'] ?? null;
        $this->estado = $data['estado'] ?? 'activo';
    }
}
