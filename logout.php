
<?php

// Iniciar sesión.
session_start();

// Aceptar solamente solicitudes POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

// Comprobar el token de seguridad.
$token = $_POST["csrf_token"] ?? "";

if (
    !isset($_SESSION["csrf_token"]) ||
    !is_string($token) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    exit("Solicitud no válida.");
}

// Eliminar los datos de la sesión.
$_SESSION = [];

// Eliminar la cookie de sesión.
if (ini_get("session.use_cookies")) {

    $parametros = session_get_cookie_params();

    setcookie(session_name(), "", [
        "expires" => time() - 42000,
        "path" => $parametros["path"],
        "domain" => $parametros["domain"],
        "secure" => $parametros["secure"],
        "httponly" => $parametros["httponly"],
        "samesite" => $parametros["samesite"] ?? "Lax"
    ]);
}

// Destruir la sesión.
session_destroy();

// Regresar al inicio de sesión.
header("Location: login.php");
exit;

?>
