
/* VALIDACIÓN DEL FORMULARIO */

// Obtenemos el formulario y sus controles.

const formulario = document.querySelector("#formulario-contacto");
const nombre = document.querySelector("#nombre");
const correo = document.querySelector("#correo");
const edad = document.querySelector("#edad");
const tema = document.querySelector("#tema");
const mensaje = document.querySelector("#mensaje");
const aceptar = document.querySelector("#aceptar");
const resultado = document.querySelector("#resultado");

// Función para validar el correo.

function validarCorreo(valor) {
const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return expresion.test(valor);
}
// Detectamos el envío del formulario.

formulario.addEventListener("submit", function(event) {

    // Evitamos el envío mientras validamos.
    event.preventDefault();

    resultado.textContent = "";
    resultado.style.color = "red";

    // Validamos el nombre.
    if (nombre.value.trim().length < 3) {
        resultado.textContent =
            "El nombre debe tener al menos 3 caracteres.";
        nombre.focus();
        return;
    }

    // Validamos el correo.
    if (!validarCorreo(correo.value.trim()) ||
        !correo.checkValidity()) {

        resultado.textContent =
            "Introduce un correo electrónico válido.";
        correo.focus();
        return;
    }

    // Validamos la edad.
    if (edad.value === "" || !edad.checkValidity()) {
        resultado.textContent =
            "Introduce una edad válida entre 1 y 120 años.";
        edad.focus();
        return;
    }

    // Validamos el tema.
    if (tema.value === "") {
        resultado.textContent =
            "Selecciona un tema de interés.";
        tema.focus();
        return;
    }

    // Validamos la calificación.
    const calificacion = document.querySelector(
        'input[name="calificacion"]:checked'
    );

    if (!calificacion) {
        resultado.textContent =
            "Selecciona una calificación para el sitio.";
        return;
    }

    // Validamos los comentarios.
    if (mensaje.value.trim().length < 10) {
        resultado.textContent =
            "El comentario debe tener al menos 10 caracteres.";
        mensaje.focus();
        return;
    }

    // Validamos el checkbox.
    if (!aceptar.checked) {
        resultado.textContent =
            "Debes marcar la casilla de confirmación.";
        aceptar.focus();
        return;
    }

    // Comprobación general.
    if (!formulario.checkValidity()) {
        formulario.reportValidity();
        return;
    }

// Todos los datos son correctos.
// Enviamos la información al servidor PHP.
formulario.submit();
});

// Limpiamos el mensaje al restablecer el formulario.
formulario.addEventListener("reset", function() {
    resultado.textContent = "";
});