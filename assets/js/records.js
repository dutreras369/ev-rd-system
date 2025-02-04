$(document).ready(function () {
    let selectedType = "carga"; // Tipo predeterminado

    function getFormattedTimestamp() {
        const now = new Date();
        const day = String(now.getDate()).padStart(2, "0");
        const month = String(now.getMonth() + 1).padStart(2, "0"); // getMonth() devuelve 0-11
        const year = now.getFullYear();
        const hours = String(now.getHours()).padStart(2, "0");
        const minutes = String(now.getMinutes()).padStart(2, "0");
        const seconds = String(now.getSeconds()).padStart(2, "0");
    
        return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
    }

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

        if (!userId || !token || !codigoUsuario) {
            alert("Error: Datos incompletos.");
            return;
        }

        // Obtener la fecha y hora actual en formato DD-MM-YYYY HH:MM:SS
       const timestamp = getFormattedTimestamp();


        // Datos a enviar
        const formData = {
            user_id: userId,
            codigo_usuario: codigoUsuario,
            token: token,
            movement_type: selectedType,
            amount: $("#amount").val(),
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
                    $("#registerModal").modal("hide");
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

    // Función para actualizar la tabla de registros
    function loadRecords() {

        const userId = localStorage.getItem("user_id");
        
        $.ajax({
            url: `${BASE_URL}/routes/record.php?action=list&user_id=${userId}`,
            type: "GET",
            dataType: "json",
            success: function(response) {
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
            error: function(xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });        
    }

    // Cargar registros al iniciar la página
    loadRecords();
});
