
<?php

// Comprobar que el usuario sea administrador.
require_once __DIR__ . "/auth.php";

// Conectar con la base de datos.
require_once __DIR__ . "/conexion.php";

// Crear un token de seguridad.
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$error = "";

// Recuperar los datos del formulario.
$codigo = "";
$nombre = "";
$descripcion = "";
$precio = "";

// Procesar el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Comprobar el token de seguridad.
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {

        $error = "Solicitud no válida. Intenta nuevamente.";

    } else {

        // Obtener los datos enviados.
        $codigo = strtoupper(trim($_POST["codigo"] ?? ""));
        $nombre = trim($_POST["nombre"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");
        $precio = trim($_POST["precio"] ?? "");

        // Comprobar que los campos no estén vacíos.
        if (
            $codigo === "" ||
            $nombre === "" ||
            $descripcion === "" ||
            $precio === ""
        ) {

            $error = "Todos los campos son obligatorios.";

        } elseif (
            mb_strlen($codigo, "UTF-8") > 30 ||
            mb_strlen($nombre, "UTF-8") > 100 ||
            mb_strlen($descripcion, "UTF-8") > 255
        ) {

            $error = "Uno de los campos supera la longitud permitida.";

        } elseif (
            !preg_match('/^\d{1,8}(\.\d{1,2})?$/', $precio)
        ) {

            $error = "Ingresa un precio válido con máximo dos decimales.";

        } else {

            // Preparar la consulta para registrar el producto.
            $consulta = $conexion->prepare(
                "INSERT INTO productos_ada5
                (codigo, nombre, descripcion, precio, activo)
                VALUES (?, ?, ?, ?, 1)"
            );

            $consulta->bind_param(
                "ssss",
                $codigo,
                $nombre,
                $descripcion,
                $precio
            );

            try {

                $consulta->execute();

                // Guardar mensaje de confirmación.
                $_SESSION["mensaje_admin"] =
                    "Producto registrado correctamente.";

                // Regresar al panel administrativo.
                header("Location: admin_productos.php");
                exit;

            } catch (mysqli_sql_exception $e) {

                if ((int) $e->getCode() === 1062) {

                    $error = "Ya existe un producto con ese código.";

                } else {

                    $error = "Ocurrió un error al registrar el producto.";

                }
            }

            $consulta->close();
        }
    }
}

$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Explorando la Web | Registrar producto</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<header>
    <h1>Explorando la Web</h1>
    <p>Registro de productos</p>
</header>
<nav class="menu" aria-label="Menú principal">
    <ul>
        <li><a href="index.html">Inicio</a></li>
        <li><a href="historia.html">Historia</a></li>
        <li><a href="galeria.html">Galería</a></li>
        <li><a href="catalogo.html">Tecnologías</a></li>
        <li><a href="linea-tiempo.html">Línea del tiempo</a></li>
        <li><a href="contacto.html">Contacto</a></li>
        <li><a href="tienda.php">Tienda</a></li>
        <li><a href="admin_productos.php">Administración</a></li>
    </ul>
</nav>
<main class="tienda-main">

    <h2>Registrar nuevo producto</h2>

    <?php if ($error !== ""): ?>

        <p class="mensaje-carrito">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>

    <?php endif; ?>

    <form method="POST" action="crear_producto.php">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION["csrf_token"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
        >

        <label for="codigo">Código del producto:</label>

        <input
            type="text"
            id="codigo"
            name="codigo"
            maxlength="30"
            value="<?= htmlspecialchars($codigo, ENT_QUOTES, "UTF-8") ?>"
            required
        >

        <label for="nombre">Nombre del producto:</label>

        <input
            type="text"
            id="nombre"
            name="nombre"
            maxlength="100"
            value="<?= htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") ?>"
            required
        >

        <label for="descripcion">Descripción:</label>

        <textarea
            id="descripcion"
            name="descripcion"
            maxlength="255"
            rows="4"
            required
        ><?= htmlspecialchars($descripcion, ENT_QUOTES, "UTF-8") ?></textarea>

        <label for="precio">Precio:</label>

        <input
            type="number"
            id="precio"
            name="precio"
            min="0"
            max="99999999.99"
            step="0.01"
            value="<?= htmlspecialchars($precio, ENT_QUOTES, "UTF-8") ?>"
            required
        >

        <button type="submit">
            Registrar producto
        </button>

    </form>

    <p>
        <a class="enlace-carrito" href="admin_productos.php">
            Volver al panel administrativo
        </a>
    </p>

</main>

<footer>
    <p>Proyecto académico - ExplorandoWEB</p>
</footer>

</body>
</html>
