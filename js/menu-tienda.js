
document.addEventListener("DOMContentLoaded", function () {

    const esServidorLocal =
        window.location.hostname === "localhost" ||
        window.location.hostname === "127.0.0.1";

    if (!esServidorLocal) {
        return;
    }

    const menu = document.querySelector(".menu ul");

    if (menu && !document.getElementById("enlace-tienda")) {

        const elemento = document.createElement("li");
        const enlace = document.createElement("a");

        elemento.id = "enlace-tienda";
        enlace.href = "tienda.php";
        enlace.textContent = "Tienda";

        elemento.appendChild(enlace);
        menu.appendChild(elemento);
    }

});
