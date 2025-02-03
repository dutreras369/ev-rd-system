$(document).ready(function () {
    let selectedType = "carga"; // Tipo de movimiento predeterminado

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

    // Manejo del envío del formulario
    $("#registerForm").submit(function (event) {
        event.preventDefault(); // Evitar recarga de página

        const userId = localStorage.getItem("user_id");
        const token = localStorage.getItem("token");
        const codigoUsuario = prompt("Ingrese el código del usuario que realiza el movimiento:");

        if (!userId || !token || !codigoUsuario) {
            alert("Error: Datos incompletos.");
            return;
        }

        // Obtener fecha y hora actual en formato YYYY-MM-DD HH:MM:SS
        const now = new Date();
        const timestamp = now.toISOString().slice(0, 19).replace("T", " ");

        // Datos a enviar
        const formData = {
            user_id: userId,
            codigo_usuario: codigoUsuario,
            token: token,
            movement_type: selectedType,
            amount: $("#amount").val(),
            timestamp: timestamp
        };

        // Enviar solicitud AJAX
        $.ajax({
            url: BASE_URL + "/routes/record.php?action=register",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Registro exitoso.");
                    $("#registerModal").modal("hide");
                    loadRecords();
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

    // Función para actualizar la tabla de registros
    function loadRecords() {
        $.ajax({
            url: BASE_URL + "/routes/record.php?action=list&user_id=" + localStorage.getItem("user_id"),
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let tableBody = $("#daily-records-table");
                    tableBody.empty();

                    response.records.forEach(record => {
                        tableBody.append(`
                            <tr>
                                <td>${record.codigo_usuario}</td>
                                <td>${record.tipo}</td>
                                <td>$${record.monto}</td>
                                <td>${record.fecha}</td>
                            </tr>
                        `);
                    });
                } else {
                    console.error("Error al obtener registros:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            },
        });
    }

    // Cargar registros al iniciar la página
    loadRecords();
});
