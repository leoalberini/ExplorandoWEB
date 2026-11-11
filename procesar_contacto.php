
<?php

// Conectar con la base de datos.
require_once "conexion.php";

// Comprobar que el formulario se envió mediante POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Acceso no permitido.");
}

// Recibir los datos del formulario.
$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$edad = filter_var($_POST["edad"] ?? "", FILTER_VALIDATE_INT);
$tema = $_POST["tema"] ?? "";
$calificacion = $_POST["calificacion"] ?? "";
$mensaje = trim($_POST["mensaje"] ?? "");
$aceptar = $_POST["aceptar"] ?? "";

// Validar el nombre.
if (strlen($nombre) < 3 || strlen($nombre) > 100) {
    exit("Error: El nombre debe tener entre 3 y 100 caracteres.");
}

// Validar el correo.
if (!filter_var($correo, FILTER_VALIDATE_EMAIL) ||
    strlen($correo) > 150) {
    exit("Error: Introduce un correo electrónico válido.");
}

// Validar la edad.
if ($edad === false || $edad < 1 || $edad > 120) {
    exit("Error: La edad debe estar entre 1 y 120 años.");
}

// Validar el tema.
$temasPermitidos = [
    "html",
    "css",
    "javascript",
    "historia"
];

if (!in_array($tema, $temasPermitidos, true)) {
    exit("Error: Selecciona un tema válido.");
}

// Validar la calificación.
$calificacionesPermitidas = [
    "excelente",
    "bueno",
    "regular"
];

if (!in_array($calificacion, $calificacionesPermitidas, true)) {
    exit("Error: Selecciona una calificación válida.");
}

// Validar los comentarios.
if (strlen($mensaje) < 10) {
    exit("Error: El comentario debe tener al menos 10 caracteres.");
}

// Validar la confirmación.
if ($aceptar !== "si") {
    exit("Error: Debes aceptar la confirmación.");
}

// Preparar la consulta para guardar los datos.
$sql = "INSERT INTO contactos
        (nombre, correo, edad, tema, calificacion, mensaje)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

// Asociar los valores.
$stmt->bind_param(
    "ssisss",
    $nombre,
    $correo,
    $edad,
    $tema,
    $calificacion,
    $mensaje
);

// Ejecutar la consulta.
if ($stmt->execute()) {

    echo "<h1>¡Formulario enviado correctamente!</h1>";
    echo "<p>Gracias por participar en ExplorandoWEB.</p>";
    echo "<p>Tus respuestas se guardaron correctamente.</p>";
    echo '<a href="index.html">Volver al inicio</a>';

} else {

    echo "<h1>Error al guardar los datos.</h1>";
    echo "<p>Inténtalo nuevamente.</p>";

}

// Cerrar la consulta y la conexión.
$stmt->close();
$conexion->close();

?>
