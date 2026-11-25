
<?php

// Comprobar que el usuario sea administrador.
require_once __DIR__ . "/auth.php";

// Conectar con la base de datos.
require_once __DIR__ . "/conexion.php";

// Crear un token para proteger los formularios.
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Consultar todos los productos, activos e inactivos.
$sql = "SELECT id, codigo, nombre, descripcion, precio, activo
        FROM productos_ada5
        ORDER BY id DESC";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("No se pudieron consultar los productos.");
}

// Recuperar mensajes.
$mensaje = $_SESSION["mensaje_admin"] ?? "";
unset($_SESSION["mensaje_admin"]);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorando la Web | Administración</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<header>
    <h1>Explorando la Web</h1>
    <p>Panel de administración</p>
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

    <h2>Administración de productos</h2>

    <p>
        Administrador:
        <strong>
            <?= htmlspecialchars(
                $_SESSION["nombre_usuario"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </strong>
    </p>

    <p>
        Desde este apartado puedes consultar y administrar
        los productos registrados en la tienda.
    </p>

    <?php if ($mensaje !== ""): ?>

        <p class="mensaje-carrito">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
        </p>

    <?php endif; ?>

    <p>
        <a class="enlace-carrito" href="crear_producto.php">
            Registrar nuevo producto
        </a>
    </p>

    <h3>Productos registrados</h3>

    <table class="tabla-carrito">

        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($producto = $resultado->fetch_assoc()): ?>

                <tr>
                    <td>
                        <?= (int) $producto["id"] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $producto["codigo"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $producto["nombre"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        $<?= number_format(
                            (float) $producto["precio"],
                            2
                        ) ?>
                    </td>

                    <td>
                        <?php if ((int) $producto["activo"] === 1): ?>
                            Activo
                        <?php else: ?>
                            Inactivo
                        <?php endif; ?>
                    </td>

                    <td>

                        <a href="editar_producto.php?id=<?= (int) $producto["id"] ?>">
                            Editar
                        </a>

                        <?php if ((int) $producto["activo"] === 1): ?>

                            <form method="POST" action="eliminar_producto.php">

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= htmlspecialchars(
                                        $_SESSION["csrf_token"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="producto_id"
                                    value="<?= (int) $producto["id"] ?>"
                                >

                                <button type="submit" class="boton-eliminar">
                                    Desactivar
                                </button>

                            </form>

                        <?php else: ?>

                            <span>Producto desactivado</span>

                        <?php endif; ?>

                    </td>
                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

    <p>
        <a class="enlace-carrito" href="tienda.php">
            Volver a la tienda
        </a>
    </p>
<form method="POST" action="logout.php">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(
            $_SESSION["csrf_token"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>"
    >

    <button type="submit" class="boton-eliminar">
        Cerrar sesión
    </button>

</form>
</main>

<footer>
    <p>Proyecto académico - ExplorandoWEB</p>
</footer>

<?php
$resultado->free();
$conexion->close();
?>

</body>
</html>
