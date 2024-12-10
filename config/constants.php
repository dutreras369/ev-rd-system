<?php
// Obtener dinámicamente la URL base del proyecto
//define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/apps/ev-rd-system');

define('BASE_URL', '/apps/ev-rd-system');

// Rutas a directorios comunes
define('ASSETS_URL', BASE_URL . '/assets');
define('IMG_URL', ASSETS_URL . '/img');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');

// Información del sistema
define('SYSTEM_NAME', 'EV-RD-System');
define('SYSTEM_VERSION', '1.0.0');

