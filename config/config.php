<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'rdsys');
define('DB_USER', 'c2111997_rdsys');
define('DB_PASS', '');

// Configuración de PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}