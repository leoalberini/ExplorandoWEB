
<?php

// Iniciar sesión.
session_start();

// Conectar con la base de datos.
require_once __DIR__ . "/conexion.php";

// Establecer un usuario inicial.
if (!isset($_SESSION["nombre_usuario"])) {
    $_SESSION["nombre_usuario"] = "Invitado";
}

// Guardar el nombre del usuario en la sesión.
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["guardar_nombre"])
) {
    $nombre = trim($_POST["nombre_usuario"] ?? "");

    if ($nombre !== "") {
        $_SESSION["nombre_usuario"] = $nombre;
    }
}

// Consultar los productos disponibles.
$sql = "SELECT id, codigo, nombre, descripcion, precio
        FROM productos_ada5
        WHERE activo = 1
        ORDER BY id ASC";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error al consultar productos: " . $conexion->error);
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorando la Web | Tienda</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<header>
    <h1>Explorando la Web</h1>
    <p>Conociendo el mundo de la tecnología web</p>
</header>

<nav class="menu" aria-label="Menú principal">
    <ul>
        <li><a href="index.html">Inicio</a></li>
        <li><a href="historia.html">Historia</a></li>
        <li><a href="galeria.html">Galería</a></li>
        <li><a href="catalogo.html">Tecnologías</a></li>
        <li><a href="linea-tiempo.html">Línea del tiempo</a></li>
        <li><a href="contacto.html">Contacto</a></li>
        <li><a href="tienda.php" aria-current="page">Tienda</a></li>
    </ul>
</nav>

<main class="tienda-main">

    <h2>Tienda de recursos tecnológicos</h2>

    <section class="usuario">
        <h3>
            ¡Bienvenido,
            <?= htmlspecialchars($_SESSION["nombre_usuario"], ENT_QUOTES, "UTF-8") ?>!
        </h3>

        <form method="POST" action="tienda.php">
            <label for="nombre_usuario">Nombre de usuario:</label>

            <input
                type="text"
                id="nombre_usuario"
                name="nombre_usuario"
                placeholder="Escribe tu nombre"
                required
            >

            <button type="submit" name="guardar_nombre">
                Guardar nombre
            </button>
        </form>
    </section>

    <h2>Productos disponibles</h2>

    <div class="productos">

        <?php while ($producto = $resultado->fetch_assoc()) { ?>

            <article class="producto">

                <h3>
                    <?= htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") ?>
                </h3>

                <p>
                    <?= htmlspecialchars($producto["descripcion"], ENT_QUOTES, "UTF-8") ?>
                </p>

                <p class="precio">
                    $<?= number_format((float)$producto["precio"], 2) ?>
                </p>

                <form method="POST" action="carrito_accion.php">
                    <input type="hidden" name="accion" value="agregar">

                    <input
                        type="hidden"
                        name="producto_id"
                        value="<?= (int)$producto["id"] ?>"
                    >

                    <button type="submit">Agregar al carrito</button>
                </form>

            </article>

        <?php } ?>

    </div>

    <a class="enlace-carrito" href="carrito.php">
        Ver mi carrito de compras
    </a>

</main>

<footer>
    <p>Proyecto académico - Desarrollo de Aplicaciones Web</p>
</footer>

</body>
</html>

<?php
$resultado->free();
$conexion->close();
?>
