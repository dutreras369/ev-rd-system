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

                    // 🔹 Restablecer valores del formulario después del registro
                    resetRegisterForm();

                    // $("#registerModal").modal("hide"); // Cerrar modal
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


    let currentPage = 1;
    const recordsPerPage = 5; // Número de registros por página
    
    function loadRecords(page = 1) {
        const userId = localStorage.getItem("user_id");
        const token = localStorage.getItem("token");
    
        $.ajax({
            url: BASE_URL + "/routes/record.php?action=list",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: userId,
                token: token,
                page: page,
                limit: 5
            }),
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
    
                    // Actualizar botones de paginación
                    updatePagination(response.current_page, response.total_pages);
                } else {
                    console.error("No hay registros:", response.error);
                }
            },
            error: function(xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }   
    
    function updatePagination(currentPage, totalPages) {
        $("#pagination").empty();
    
        // Botón anterior
        if (currentPage > 1) {
            $("#pagination").append(`<button class="page-btn" data-page="${currentPage - 1}">Anterior</button>`);
        }
    
        // Números de página
        for (let i = 1; i <= totalPages; i++) {
            let activeClass = i === currentPage ? "active" : "";
            $("#pagination").append(`<button class="page-btn ${activeClass}" data-page="${i}">${i}</button>`);
        }
    
        // Botón siguiente
        if (currentPage < totalPages) {
            $("#pagination").append(`<button class="page-btn" data-page="${currentPage + 1}">Siguiente</button>`);
        }
    
        $(".page-btn").click(function () {
            let page = $(this).data("page");
            loadRecords(page);
        });
    }

    // Cargar registros al iniciar la página
    loadRecords();
});
