<?php

// Registrar el autoload
spl_autoload_register(function ($class) {
    // Definir los directorios donde buscar las clases
    $directories = [
        __DIR__ . '/../controllers',
        __DIR__ . '/../models',
        __DIR__ . '/../services',
        __DIR__ . '/../helpers',
    ];

    // Buscar la clase en los directorios definidos
    foreach ($directories as $directory) {
        $file = $directory . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Manejar el caso en el que no se encuentre la clase
    throw new Exception("No se pudo cargar la clase: $class");
});
