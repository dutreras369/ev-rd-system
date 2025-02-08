$(document).ready(function () {
    const userId = localStorage.getItem("user_id");
    const token = localStorage.getItem("token");

    let currentPage = 1;
    const recordsPerPage = 5;

    if (!userId || !token) {
        console.error("Usuario no autenticado.");
        return;
    }

    /** 📌 Cargar registros paginados */
    function loadRecords(page = 1) {
        $.ajax({
            url: `${BASE_URL}/record.php?action=list`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: userId,
                token: token,
                page: page,
                limit: recordsPerPage
            }),
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
                    updatePagination(response.totalPages, page);
                } else {
                    console.error("No hay registros:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    /** 📌 Actualizar paginación */
    function updatePagination(totalPages, currentPage) {
        let paginationHtml = "";
        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `<button class="btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} pagination-btn" data-page="${i}">${i}</button>`;
        }
        $("#pagination").html(paginationHtml);
    }

    /** 📌 Evento para cambiar de página */
    $(document).on("click", ".pagination-btn", function () {
        let page = $(this).data("page");
        currentPage = page;
        loadRecords(page);
    });

    /** 📌 Envío del formulario de registro */
    $("#registerForm").submit(function (event) {
        event.preventDefault();

        const codigoUsuario = $("#codigo_usuario").val();
        const amount = $("#amount").val();
        const movementType = $("#movement-type").val();

        if (!codigoUsuario || !movementType || !amount) {
            alert("Error: Datos incompletos.");
            return;
        }

        const now = new Date();
        const timestamp = now.toISOString().slice(0, 19).replace("T", " ");

        const formData = {
            user_id: userId,
            codigo_usuario: codigoUsuario,
            token: token,
            movement_type: movementType,
            amount: amount,
            timestamp: timestamp
        };

        $.ajax({
            url: `${BASE_URL}/record.php?action=register`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function (response) {
                if (response.success) {
                    alert("Registro exitoso.");
                    $("#registerModal").modal("hide");
                    loadRecords(currentPage);
                    resetRegisterForm();
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

    /** 📌 Restablecer formulario después del registro */
    function resetRegisterForm() {
        $("#registerForm")[0].reset();
        $("#movement-type").val("carga");
    }

    /** 📌 Cargar datos del día anterior */
    function loadTotalRecords() {
        $.ajax({
            url: `${BASE_URL}/record.php?action=total_records`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: userId,
                token: token
            }),
            success: function (response) {
                if (response.success) {
                    $("#daily-records").text(response.totalRecords);
                } else {
                    console.error("Error al obtener totales:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    /** 📌 Filtrar registros */
    $("#filterDetailsForm").submit(function (event) {
        event.preventDefault();

        const dateFrom = $("#filter-date-from").val();
        const dateTo = $("#filter-date-to").val();
        const type = $("#filter-type").val();
        const status = $("#filter-status").val();

        $.ajax({
            url: `${BASE_URL}/record.php?action=filter`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: userId,
                token: token,
                date_from: dateFrom,
                date_to: dateTo,
                status: status,
                page: currentPage,
                limit: recordsPerPage
            }),
            success: function (response) {
                if (response.success) {
                    $("#details-table-body").empty();
                    response.records.forEach(record => {
                        $("#details-table-body").append(`
                            <tr>
                                <td>${record.codigo_usuario}</td>
                                <td>${record.tipo}</td>
                                <td>$${parseFloat(record.monto).toLocaleString()}</td>
                                <td>${record.fecha}</td>
                                <td>${record.estado}</td>
                            </tr>
                        `);
                    });
                    updatePagination(response.totalPages, currentPage);
                } else {
                    console.error("Error en filtro:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    });

    /** 📌 Cargar registros y totales al iniciar */
    loadRecords();
    loadTotalRecords();
});
