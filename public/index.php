<?php
session_start();

// Verificar si el usuario ya inició sesión
if (isset($_SESSION['user_id'])) {
    // Redirigir al dashboard según el rol
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard/admin.php');
    } else {
        header('Location: dashboard/user.php');
    }
    exit();
}

// Si no hay sesión, redirigir al login
header('Location: login.php');
exit();
