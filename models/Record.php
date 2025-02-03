<?php

class Record {
    private $id;
    private $usuario_id;
    private $tipo;
    private $monto;
    private $descripcion;
    private $fecha;
    private $estado;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->usuario_id = $data['usuario_id'];
        $this->tipo = $data['tipo'];
        $this->monto = $data['monto'];
        $this->descripcion = $data['descripcion'] ?? null;
        $this->fecha = $data['fecha'];
        $this->estado = $data['estado'] ?? 'pendiente';
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getEstado() {
        return $this->estado;
    }

    // Setters
    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }
}
