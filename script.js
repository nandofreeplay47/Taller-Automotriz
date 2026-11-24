document.getElementById("form-contacto").addEventListener("submit", async function (e) {
    e.preventDefault(); // Evita que la página se recargue

    const form = e.target;
    const formData = new FormData(form);

    // Capturamos los valores para armar el mensaje de WhatsApp
    const nombre   = formData.get("nombre");
    const telefono = formData.get("telefono");
    const servicio = formData.get("servicio");
    const mensaje  = formData.get("mensaje");

    try {
        // 1. Envía los datos a PHP en segundo plano
        const respuesta = await fetch("registrar.php", {
            method: "POST",
            body: formData
        });

        const resultado = await respuesta.json();

        if (resultado.status === "success") {
            // 2. Si se guardó en MySQL, abre WhatsApp con los datos
            const numeroTaller = "51908802286"; // <-- Pon aquí tu número con código de país (51 para Perú)
            const textoWhatsApp = `Hola, soy *${nombre}* (${telefono}). Solicito el servicio de *${servicio}*. Detalle: ${mensaje}`;
            const urlWhatsApp = `https://wa.me/${numeroTaller}?text=${encodeURIComponent(textoWhatsApp)}`;
            
            window.open(urlWhatsApp, "_blank");
            form.reset(); // Limpia el formulario
        } else {
            alert("No se pudo guardar en la base de datos: " + resultado.message);
        }
    } catch (error) {
        console.error("Error en fetch:", error);
        alert("Ocurrió un error al conectar con el servidor.");
    }
});