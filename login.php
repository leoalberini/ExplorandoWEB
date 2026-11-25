
<?php

// Iniciar sesión.
session_start();

// Si ya inició sesión, enviarlo al panel administrativo.
if (isset($_SESSION["usuario_id"])) {
    header("Location: admin_productos.php");
    exit;
}

// Conectar con la base de datos.
require_once __DIR__ . "/conexion.php";

$error = "";

// Comprobar el envío del formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = strtolower(trim($_POST["correo"] ?? ""));
    $password = $_POST["password"] ?? "";

    // Validar los datos recibidos.
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === "") {

        $error = "Ingresa un correo y una contraseña válidos.";

    } else {

        // Buscar al usuario activo.
        $consulta = $conexion->prepare(
            "SELECT id, nombre, correo, password_hash, rol
             FROM usuarios
             WHERE correo = ? AND activo = 1
             LIMIT 1"
        );

        $consulta->bind_param("s", $correo);
        $consulta->execute();

        $resultado = $consulta->get_result();
        $usuario = $resultado->fetch_assoc();

        // Verificar la contraseña.
        if ($usuario && password_verify($password, $usuario["password_hash"])) {

            // Renovar el identificador de sesión.
            session_regenerate_id(true);

            // Guardar los datos del usuario.
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre_usuario"] = $usuario["nombre"];
            $_SESSION["usuario_correo"] = $usuario["correo"];
            $_SESSION["usuario_rol"] = $usuario["rol"];

            // Ir al panel administrativo.
            header("Location: admin_productos.php");
            exit;

        } else {

            $error = "Correo o contraseña incorrectos.";

        }

        $consulta->close();
    }
}

$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorando la Web | Iniciar sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<header>
    <h1>Explorando la Web</h1>
    <p>Acceso al sistema</p>
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
<main>

    <h2>Iniciar sesión</h2>

    <?php if ($error !== ""): ?>

        <p class="mensaje-carrito">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>

    <?php endif; ?>

    <form method="POST" action="login.php">

        <label for="correo">Correo electrónico:</label>

        <input
            type="email"
            id="correo"
            name="correo"
            required
        >

        <label for="password">Contraseña:</label>

        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
        >

        <button type="submit">Ingresar</button>

    </form>

</main>

<footer>
    <p>Proyecto académico - ExplorandoWEB</p>
</footer>

</body>
</html>
