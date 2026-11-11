
<?php

// Conectar con la base de datos.
require_once "conexion.php";

// Consultar los mensajes registrados.
$sql = "SELECT * FROM contactos ORDER BY fecha_registro DESC";

$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes - ExplorandoWEB</title>
</head>

<body>

    <h1>Mensajes de contacto</h1>

    <p>Registros recibidos desde ExplorandoWEB.</p>

    <?php if ($resultado && $resultado->num_rows > 0): ?>

        <table border="1" cellpadding="8">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Edad</th>
                    <th>Tema</th>
                    <th>Calificación</th>
                    <th>Comentarios</th>
                    <th>Fecha</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($fila = $resultado->fetch_assoc()): ?>

                    <tr>
                        <td><?php echo (int)$fila["id"]; ?></td>
                        <td><?php echo htmlspecialchars($fila["nombre"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["correo"]); ?></td>
                        <td><?php echo (int)$fila["edad"]; ?></td>
                        <td><?php echo htmlspecialchars($fila["tema"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["calificacion"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["mensaje"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["fecha_registro"]); ?></td>
                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p>Todavía no hay mensajes registrados.</p>

    <?php endif; ?>

    <br>

    <a href="index.html">Volver al inicio</a>

</body>
</html>

<?php

$conexion->close();

?>
