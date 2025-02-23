$(document).ready(function () {

    /** 🔹 Abrir modal de filtro general */
    $("#openFilterModal").click(function () {
        loadFilteredRecords(); // Cargar datos generales
        $("#viewDetailsModal").modal("show");
    });

    /** 🔹 Manejo del formulario dentro del modal */
    $("#filterDetailsForm").submit(function (event) {
        event.preventDefault();

        let userId = $("#filter-user").val() || null;

        console.log("🔍 Filtrando registros para usuario ID:", userId);

        // 🔹 Cargar datos filtrados sin cerrar el modal
        loadFilteredRecords(userId, 1);
    });




});

/** 🔹 Cargar registros filtrados */
function loadFilteredRecords(userId = null, page = 1) {
    let limit = 10;
    let offset = (page - 1) * limit;
    const token = localStorage.getItem("token");

    $.ajax({
        url: `${BASE_URL}/routes/filter.php?action=filter_records`,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            user_id: userId || null,
            token: token,
            date_from: $("#filter-date-from").val() || getDefaultStartDate(),
            date_to: $("#filter-date-to").val() || getTodayDate(),
            type: $("#filter-type").val(),
            status: $("#filter-status").val(),
            limit: limit,
            offset: offset
        }),
        success: function (response) {
            let tableBody = $("#details-table-body");

            // 🔹 LIMPIAR la tabla antes de agregar nuevos datos
            tableBody.html("");

            if (response.length > 0) {
                response.records.forEach((record, index) => {
                    $("#details-table-body").append(`
                        <tr>
                            <td>${record.trabajador_nombre || "N/A"}</td>
                            <td>${record.usuario_nombre || "N/A"}</td>
                            <td>${record.fecha}</td>
                            <td>${record.tipo}</td>
                            <td>$${parseFloat(record.monto).toLocaleString()}</td>
                            <td>${record.estado === "correcto" ? "Correcto" : "Incorrecto"}</td>
                        </tr>
                    `);
                });
                console.log("✅ Registros actualizados correctamente en la tabla.");
            } else {
                tableBody.append(`
                    <tr>
                        <td colspan="6" class="text-center text-muted">No se encontraron registros.</td>
                    </tr>
                `);
            }

            // 🔹 Forzar que el modal permanezca abierto y actualizado
            setTimeout(() => {
                $("#viewDetailsModal").modal("show").find(".modal-body").scrollTop(0);
            }, 200);
        },
        error: function (xhr) {
            console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
        }
    });
}


/** 🔹 Función para actualizar la paginación */
function updatePagination(currentPage, totalPages, userId = null) {
    let paginationContainer = $("#pagination");
    paginationContainer.empty();

    if (totalPages > 1) {
        let prevDisabled = currentPage === 1 ? "disabled" : "";
        let nextDisabled = currentPage === totalPages ? "disabled" : "";

        let paginationHtml = `
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item ${prevDisabled}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">« Anterior</a>
                    </li>`;

        for (let i = 1; i <= totalPages; i++) {
            let activeClass = i === currentPage ? "active" : "";
            paginationHtml += `
                <li class="page-item ${activeClass}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
        }

        paginationHtml += `
                    <li class="page-item ${nextDisabled}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente »</a>
                    </li>
                </ul>
            </nav>`;

        paginationContainer.html(paginationHtml);

        $(".page-link").click(function (event) {
            event.preventDefault();
            let selectedPage = $(this).data("page");
            if (selectedPage > 0 && selectedPage <= totalPages) {
                loadFilteredRecords(userId, selectedPage);
            }
        });
    }
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
