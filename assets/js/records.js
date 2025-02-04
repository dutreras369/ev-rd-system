$(document).ready(function () {
    let selectedType = "carga"; // Tipo predeterminado

    // Manejar selección de tipo de movimiento
    $(".movement-type").click(function () {
        selectedType = $(this).data("type");
        $("#movement-type").val(selectedType);
        $(".movement-type").removeClass("active");
        $(this).addClass("active");
    });

    // Manejar selección rápida de monto
    $(".quick-amount").click(function () {
        $("#amount").val($(this).data("amount"));
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
        $(".movement-type").removeClass("active btn-success btn-danger").addClass("btn-outline-success btn-outline-danger");

        // Restaurar colores originales
        $(".movement-type[data-type='carga']").removeClass("btn-danger").addClass("btn-outline-success");
        $(".movement-type[data-type='retiro']").removeClass("btn-success").addClass("btn-outline-danger");
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
