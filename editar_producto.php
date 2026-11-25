
<?php

// Comprobar que el usuario sea administrador.
require_once __DIR__ . "/auth.php";

// Conectar con la base de datos.
require_once __DIR__ . "/conexion.php";

// Crear un token de seguridad.
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Obtener y validar el ID del producto.
$id = filter_var(
    $_GET["id"] ?? $_POST["id"] ?? "",
    FILTER_VALIDATE_INT
);

if ($id === false || $id <= 0) {
    http_response_code(400);
    exit("Identificador de producto inválido.");
}

// Consultar el producto seleccionado.
$consulta = $conexion->prepare(
    "SELECT id, codigo, nombre, descripcion, precio, activo
     FROM productos_ada5
     WHERE id = ?"
);

$consulta->bind_param("i", $id);
$consulta->execute();

$resultado = $consulta->get_result();
$producto = $resultado->fetch_assoc();

$consulta->close();

// Comprobar que el producto exista.
if (!$producto) {
    http_response_code(404);
    $conexion->close();
    exit("El producto no existe.");
}

// Inicializar los campos con los datos actuales.
$codigo = $producto["codigo"];
$nombre = $producto["nombre"];
$descripcion = $producto["descripcion"];
$precio = $producto["precio"];

$error = "";

// Procesar el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Comprobar el token de seguridad.
    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {

        $error = "Solicitud no válida. Intenta nuevamente.";

    } else {

        // Obtener los nuevos datos.
        $codigo = strtoupper(trim($_POST["codigo"] ?? ""));
        $nombre = trim($_POST["nombre"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");
        $precio = trim($_POST["precio"] ?? "");

        // Validar los campos.
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

            // Actualizar el registro en la base de datos.
            $actualizar = $conexion->prepare(
                "UPDATE productos_ada5
                 SET codigo = ?, nombre = ?, descripcion = ?, precio = ?
                 WHERE id = ?"
            );

            $actualizar->bind_param(
                "ssssi",
                $codigo,
                $nombre,
                $descripcion,
                $precio,
                $id
            );

            try {

                $actualizar->execute();

                $_SESSION["mensaje_admin"] =
                    "Producto actualizado correctamente.";

                $actualizar->close();
                $conexion->close();

                header("Location: admin_productos.php");
                exit;

            } catch (mysqli_sql_exception $e) {

                if ((int) $e->getCode() === 1062) {

                    $error = "Ya existe otro producto con ese código.";

                } else {

                    $error = "Ocurrió un error al actualizar el producto.";

                }
            }

            $actualizar->close();
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

    <title>Explorando la Web | Editar producto</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<header>
    <h1>Explorando la Web</h1>
    <p>Edición de productos</p>
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

    <h2>Modificar producto</h2>

    <p>
        Producto seleccionado:
        <strong><?= (int) $producto["id"] ?></strong>
    </p>

    <p>
        Estado:
        <strong>
            <?= (int) $producto["activo"] === 1 ? "Activo" : "Inactivo" ?>
        </strong>
    </p>

    <?php if ($error !== ""): ?>

        <p class="mensaje-carrito">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>

    <?php endif; ?>

    <form method="POST" action="editar_producto.php?id=<?= (int) $id ?>">

        <input
            type="hidden"
            name="id"
            value="<?= (int) $id ?>"
        >

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
            Guardar cambios
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
