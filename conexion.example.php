
<?php

$servidor = "TU_SERVIDOR";
$usuario = "TU_USUARIO";
$password = "TU_CONTRASENA";
$base_datos = "TU_BASE_DE_DATOS";

$conexion = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos
);

if ($conexion->connect_error) {
    die("Error de conexión");
}

$conexion->set_charset("utf8mb4");

?>
