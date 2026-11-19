
<?php

// Iniciar sesión.
session_start();

// Conectar con la base de datos.
require_once __DIR__ . "/conexion.php";

// Crear el carrito si todavía no existe.
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

// Comprobar que los datos lleguen mediante POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tienda.php");
    exit;
}

// Obtener la acción solicitada.
$accion = $_POST["accion"] ?? "";


// ======================================
// AGREGAR PRODUCTOS
// ======================================

if ($accion === "agregar") {

    $productoId = filter_var(
        $_POST["producto_id"] ?? "",
        FILTER_VALIDATE_INT
    );

    if ($productoId === false || $productoId <= 0) {

        $_SESSION["mensaje_carrito"] = "Producto inválido.";

    } else {

        // Comprobar que el producto exista y esté activo.
        $consulta = $conexion->prepare(
            "SELECT id FROM productos_ada5
             WHERE id = ? AND activo = 1"
        );

        $consulta->bind_param("i", $productoId);
        $consulta->execute();
        $consulta->store_result();

        $productoExiste = $consulta->num_rows > 0;

        $consulta->close();

        if ($productoExiste) {

            // Si ya existe, aumentar su cantidad.
            $cantidadActual =
                (int) ($_SESSION["carrito"][$productoId] ?? 0);

            $_SESSION["carrito"][$productoId] =
                $cantidadActual + 1;

            $_SESSION["mensaje_carrito"] =
                "Producto agregado correctamente.";

        } else {

            $_SESSION["mensaje_carrito"] =
                "El producto no está disponible.";

        }
    }


// ======================================
// MODIFICAR CANTIDADES
// ======================================

} elseif ($accion === "actualizar") {

    $cantidades = $_POST["cantidades"] ?? [];

    if (is_array($cantidades)) {

        foreach ($cantidades as $id => $cantidad) {

            $id = filter_var(
                $id,
                FILTER_VALIDATE_INT
            );

            $cantidad = filter_var(
                $cantidad,
                FILTER_VALIDATE_INT
            );

            if (
                $id === false ||
                !isset($_SESSION["carrito"][$id])
            ) {
                continue;
            }

            // Una cantidad menor a 1 retira el producto.
            if ($cantidad === false || $cantidad < 1) {

                unset($_SESSION["carrito"][$id]);

            } else {

                $_SESSION["carrito"][$id] = $cantidad;

            }
        }
    }

    $_SESSION["mensaje_carrito"] =
        "Carrito actualizado correctamente.";


// ======================================
// ELIMINAR UN PRODUCTO
// ======================================

} elseif ($accion === "eliminar") {

    $productoId = filter_var(
        $_POST["producto_id"] ?? "",
        FILTER_VALIDATE_INT
    );

    if (
        $productoId !== false &&
        isset($_SESSION["carrito"][$productoId])
    ) {

        unset($_SESSION["carrito"][$productoId]);

        $_SESSION["mensaje_carrito"] =
            "Producto eliminado del carrito.";

    }


// ======================================
// VACIAR TODO EL CARRITO
// ======================================

} elseif ($accion === "vaciar") {

    $_SESSION["carrito"] = [];

    $_SESSION["mensaje_carrito"] =
        "El carrito se ha vaciado correctamente.";

} else {

    $_SESSION["mensaje_carrito"] =
        "Acción no reconocida.";
}


// Cerrar conexión.
$conexion->close();

// Regresar al carrito.
header("Location: carrito.php");
exit;

?>
