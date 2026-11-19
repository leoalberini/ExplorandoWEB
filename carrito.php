
<?php
session_start();
require_once __DIR__ . "/conexion.php";

$carrito = $_SESSION["carrito"] ?? [];
$productos = [];
$total = 0;

foreach ($carrito as $id => $cantidad) {
    $consulta = $conexion->prepare(
        "SELECT id, nombre, precio FROM productos_ada5
         WHERE id = ? AND activo = 1"
    );

    $consulta->bind_param("i", $id);
    $consulta->execute();

    $resultado = $consulta->get_result();
    $producto = $resultado->fetch_assoc();

    if ($producto) {
        $producto["cantidad"] = (int) $cantidad;
        $producto["subtotal"] =
            (float) $producto["precio"] * (int) $cantidad;

        $productos[] = $producto;
        $total += $producto["subtotal"];
    }

    $consulta->close();
}

$mensaje = $_SESSION["mensaje_carrito"] ?? "";
unset($_SESSION["mensaje_carrito"]);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorando la Web | Mi carrito</title>
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
        <li><a href="tienda.php">Tienda</a></li>
    </ul>
</nav>

<main class="tienda-main">

    <h2>Mi carrito de compras</h2>

    <p>
        Usuario:
        <strong>
            <?= htmlspecialchars(
                $_SESSION["nombre_usuario"] ?? "Invitado",
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </strong>
    </p>

    <?php if ($mensaje !== ""): ?>
        <p class="mensaje-carrito">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <?php if (count($productos) > 0): ?>

        <form id="actualizar" method="POST" action="carrito_accion.php">
            <input type="hidden" name="accion" value="actualizar">
        </form>

        <table class="tabla-carrito">

            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($productos as $producto): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $producto["nombre"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            $<?= number_format($producto["precio"], 2) ?>
                        </td>

                        <td>
                            <input
                                form="actualizar"
                                type="number"
                                name="cantidades[<?= $producto["id"] ?>]"
                                value="<?= $producto["cantidad"] ?>"
                                min="1"
                                max="999"
                            >
                        </td>

                        <td>
                            $<?= number_format($producto["subtotal"], 2) ?>
                        </td>

                        <td>
                            <form method="POST" action="carrito_accion.php">
                                <input type="hidden" name="accion" value="eliminar">

                                <input
                                    type="hidden"
                                    name="producto_id"
                                    value="<?= $producto["id"] ?>"
                                >

                                <button type="submit" class="boton-eliminar">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

        <p class="total-carrito">
            Total: $<?= number_format($total, 2) ?>
        </p>

        <div class="acciones-carrito">

            <button type="submit" form="actualizar">
                Actualizar cantidades
            </button>

            <form method="POST" action="carrito_accion.php">
                <input type="hidden" name="accion" value="vaciar">

                <button type="submit" class="boton-eliminar">
                    Vaciar carrito
                </button>
            </form>

        </div>

    <?php else: ?>

        <p>Tu carrito está vacío.</p>

    <?php endif; ?>

    <p>
        <a class="enlace-carrito" href="tienda.php">
            ← Seguir comprando
        </a>
    </p>

</main>

<footer>
    <p>Proyecto académico - Desarrollo de Aplicaciones Web</p>
</footer>

<?php $conexion->close(); ?>

</body>
</html>
