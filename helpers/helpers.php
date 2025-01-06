<?php

// Generar una URL absoluta
function base_url($path = '') {
    return BASE_URL . $path;
}

// Formatear números como moneda
function format_currency($number) {
    return '$' . number_format($number, 2);
}

// Generar mensajes dinámicos
function flash_message($type, $message) {
    $class = $type === 'success' ? 'alert-success' : 'alert-danger';
    return "<div class='alert $class'>$message</div>";
}
