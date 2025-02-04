$(document).ready(function () {
    // Variable global para almacenar el tipo de movimiento seleccionado
    let selectedType = null;

    // Evento para seleccionar el tipo de movimiento
    $(".movement-type").on("click", function () {
        // Remover la clase activa de todos los botones
        $(".movement-type").removeClass("btn-success btn-danger active")
            .addClass("btn-outline-success btn-outline-danger");

        // Obtener el tipo seleccionado
        selectedType = $(this).data("type");

        // Aplicar color correcto
        if (selectedType === "carga") {
            $(this).removeClass("btn-outline-success").addClass("btn-success active");
        } else if (selectedType === "retiro") {
            $(this).removeClass("btn-outline-danger").addClass("btn-danger active");
        }
    });

    // Envío del formulario de registro
    $("#registerForm").submit(function (event) {
        event.preventDefault();

        const userId = localStorage.getItem("user_id");
        const token = localStorage.getItem("token");
        const codigoUsuario = $("#codigo_usuario").val();
        const amount = $("#amount").val();

        if (!userId || !token || !codigoUsuario || !selectedType || !amount) {
            alert("Error: Datos incompletos.");
            return;
        }

        // Obtener la fecha y hora actual en formato YYYY-MM-DD HH:MM:SS
        const now = new Date();
        const timestamp = now.toISOString().slice(0, 19).replace("T", " ");

        // Datos a enviar
        const formData = {
            user_id: userId,
            codigo_usuario: codigoUsuario,
            token: token,
            movement_type: selectedType,
            amount: amount,
            timestamp: timestamp
        };

        // Enviar datos al endpoint
        $.ajax({
            url: BASE_URL + "/routes/record.php?action=register",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Registro exitoso.");

                    // 🔹 Restablecer valores del formulario después del registro
                    resetRegisterForm();

                    $("#registerModal").modal("hide"); // Cerrar modal
                    loadRecords(); // Recargar la lista de registros
                } else {
                    alert("Error al registrar: " + response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
                alert("Error en la solicitud. Ver consola.");
            },
        });
    });

    /**
     * 🔹 Restablecer valores del formulario después de registrar
     */
    function resetRegisterForm() {
        $("#codigo_usuario").val(""); // Limpiar campo de código de usuario
        $("#amount").val(""); // Limpiar campo de monto
        selectedType = null; // Reiniciar selección del tipo de movimiento

        // Restablecer la selección visual de los botones de tipo de movimiento
        $(".movement-type").removeClass("btn-success btn-danger active")
            .addClass("btn-outline-success btn-outline-danger");
    }


    // Función para actualizar la tabla de registros
    function loadRecords() {

        const userId = localStorage.getItem("user_id");

        $.ajax({
            url: `${BASE_URL}/routes/record.php?action=list&user_id=${userId}`,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#daily-records-table").empty();
                    response.records.forEach(record => {
                        $("#daily-records-table").append(`
                            <tr>
                                <td>${record.codigo_usuario}</td>
                                <td>${record.tipo}</td>
                                <td>$${parseFloat(record.monto).toLocaleString()}</td>
                                <td>${record.fecha}</td>
                            </tr>
                        `);
                    });
                } else {
                    console.error("No hay registros:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    // Cargar registros al iniciar la página
    loadRecords();
});
