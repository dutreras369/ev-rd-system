<?php

class Log {
    public $id;
    public $accion;
    public $usuario_id;
    public $ip_address;
    public $user_agent;
    public $fecha;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->accion = $data['accion'] ?? '';
        $this->usuario_id = $data['usuario_id'] ?? null;
        $this->ip_address = $data['ip_address'] ?? null;
        $this->user_agent = $data['user_agent'] ?? null;
        $this->fecha = $data['fecha'] ?? date('Y-m-d H:i:s');
    }
}
