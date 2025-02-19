$(document).ready(function () {
    /** 🔹 Abrir modal de filtro general */
    $("#openFilterModal").click(function () {
        $("#viewDetailsModal").modal("show");
        loadFilteredRecords(); // Carga registros generales
    });

    /** 🔹 Evento para ver registros por usuario */
    $(".view-user-records").click(function () {
        let userId = $(this).data("user");
        $("#viewDetailsModal").modal("show");
        loadFilteredRecords(userId); // Carga registros de un usuario específico
    });

    /** 🔹 Aplicar filtros manualmente desde el formulario */
    $("#filterDetailsForm").submit(function (event) {
        event.preventDefault();
        loadFilteredRecords(); // Carga registros según filtros seleccionados
    });
});

/** 🔹 Cargar registros filtrados */
function loadFilteredRecords(userId = null, page = 1) {
    let dateFrom = $("#filter-date-from").val() || getDefaultStartDate();
    let dateTo = $("#filter-date-to").val() || getTodayDate();
    let type = $("#filter-type").val();
    let status = $("#filter-status").val();
    let selectedUser = userId ? userId : $("#filter-user").val();

    $.ajax({
        url: `${BASE_URL}/routes/filter.php?action=filter_records`,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            user_id: selectedUser,
            token: token,
            date_from: dateFrom,
            date_to: dateTo,
            type: type,
            status: status,
            page: page,
            limit: 10
        }),
        success: function (response) {
            if (response.success) {
                $("#details-table-body").empty();
                response.records.forEach((record, index) => {
                    $("#details-table-body").append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${record.usuario}</td>
                            <td>${record.fecha}</td>
                            <td>${record.tipo}</td>
                            <td>$${parseFloat(record.monto).toLocaleString()}</td>
                            <td>${record.estado}</td>
                        </tr>
                    `);
                });

                updatePagination(response.current_page, response.total_pages, userId);
            } else {
                console.error("⚠️ No se encontraron registros:", response.error);
            }
        },
        error: function (xhr) {
            console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
        }
    });
}

/** 🔹 Actualizar paginación */
function updatePagination(currentPage, totalPages, userId = null) {
    $("#pagination").empty();

    if (currentPage > 1) {
        $("#pagination").append(`<button class="page-btn" data-page="${currentPage - 1}" data-user="${userId}">Anterior</button>`);
    }

    for (let i = 1; i <= totalPages; i++) {
        let activeClass = i === currentPage ? "active" : "";
        $("#pagination").append(`<button class="page-btn ${activeClass}" data-page="${i}" data-user="${userId}">${i}</button>`);
    }

    if (currentPage < totalPages) {
        $("#pagination").append(`<button class="page-btn" data-page="${currentPage + 1}" data-user="${userId}">Siguiente</button>`);
    }

    $(".page-btn").click(function () {
        let page = $(this).data("page");
        let userId = $(this).data("user");
        loadFilteredRecords(userId, page);
    });
}

/** 🔹 Obtener la fecha de hoy */
function getTodayDate() {
    let today = new Date();
    return today.toISOString().split("T")[0];
}

/** 🔹 Obtener la fecha de inicio predeterminada (últimos 7 días) */
function getDefaultStartDate() {
    let date = new Date();
    date.setDate(date.getDate() - 7);
    return date.toISOString().split("T")[0];
}
