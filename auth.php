
<?php

// Iniciar sesión.
session_start();

// Comprobar si el usuario inició sesión.
if (!isset($_SESSION["usuario_id"])) {

    // Redirigir al inicio de sesión.
    header("Location: login.php");
    exit;
}

// Comprobar que el usuario sea administrador.
if (($_SESSION["usuario_rol"] ?? "") !== "admin") {

    // Bloquear el acceso.
    http_response_code(403);
    exit("No tienes permiso para acceder a esta página.");
}

?>
