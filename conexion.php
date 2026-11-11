
<?php

// Datos de conexión a la base de datos.

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "explorandoweb";

// Crear conexión.

$conexion = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos
);

// Comprobar conexión.

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar caracteres UTF-8.

$conexion->set_charset("utf8mb4");

?>
