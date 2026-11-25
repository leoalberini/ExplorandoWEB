
<?php

// Comprobar que el usuario sea administrador.
require_once __DIR__ . "/auth.php";

// Conectar con la base de datos.
require_once __DIR__ . "/conexion.php";

// Aceptar únicamente solicitudes POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_productos.php");
    exit;
}

// Obtener y validar el token de seguridad.
$token = $_POST["csrf_token"] ?? "";

if (
    !is_string($token) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    $_SESSION["mensaje_admin"] = "Solicitud no válida.";
    header("Location: admin_productos.php");
    exit;
}

// Obtener y validar el identificador del producto.
$productoId = filter_var(
    $_POST["producto_id"] ?? "",
    FILTER_VALIDATE_INT
);

if ($productoId === false || $productoId <= 0) {

    $_SESSION["mensaje_admin"] = "Identificador de producto inválido.";

} else {

    // Desactivar el producto sin eliminarlo de la base de datos.
    $consulta = $conexion->prepare(
        "UPDATE productos_ada5
         SET activo = 0
         WHERE id = ? AND activo = 1"
    );

    $consulta->bind_param("i", $productoId);

    try {

        $consulta->execute();

        if ($consulta->affected_rows === 1) {

            $_SESSION["mensaje_admin"] =
                "Producto desactivado correctamente.";

        } else {

            $_SESSION["mensaje_admin"] =
                "El producto no existe o ya estaba desactivado.";

        }

    } catch (mysqli_sql_exception $e) {

        $_SESSION["mensaje_admin"] =
            "Ocurrió un error al desactivar el producto.";

    }

    $consulta->close();
}

// Cerrar conexión.
$conexion->close();

// Regresar al panel administrativo.
header("Location: admin_productos.php");
exit;

?>
