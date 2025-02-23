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
            tableBody.empty();

            if (response.success && response.records.length > 0) {
                response.records.forEach((record, index) => {
                    tableBody.append(`
                        <tr>
                            <td>${record.trabajador_nombre || "N/A"}</td>
                            <td>${record.codigo_usuario || "N/A"}</td>
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

            // 🔹 Actualizar el paginador si hay más de 10 registros
            updatePagination(response.current_page, response.total_pages, userId);

            // 🔹 Asegurar que el modal permanece abierto y actualizado
            $("#viewDetailsModal").modal("show");
        },
        error: function (xhr) {
            console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
        }
    });
}

/** 🔹 Función para actualizar el paginador */
function updatePagination(currentPage, totalPages, userId) {
    let paginationContainer = $("#pagination");
    paginationContainer.empty();

    if (totalPages > 1) {
        let paginationHTML = `<ul class="pagination">`;

        // Botón Anterior
        if (currentPage > 1) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, ${currentPage - 1})">«</a>
                </li>`;
        } else {
            paginationHTML += `
                <li class="page-item disabled">
                    <span class="page-link">«</span>
                </li>`;
        }

        // Mostrar siempre la primera página
        if (currentPage > 3) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, 1)">1</a>
                </li>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
        }

        // Mostrar las páginas cercanas al actual
        let start = Math.max(1, currentPage - 2);
        let end = Math.min(totalPages, currentPage + 2);

        for (let i = start; i <= end; i++) {
            let activeClass = i === currentPage ? "active" : "";
            paginationHTML += `
                <li class="page-item ${activeClass}">
                    <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, ${i})">${i}</a>
                </li>`;
        }

        // Mostrar siempre la última página
        if (currentPage < totalPages - 2) {
            paginationHTML += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, ${totalPages})">${totalPages}</a>
                </li>`;
        }

        // Botón Siguiente
        if (currentPage < totalPages) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, ${currentPage + 1})">»</a>
                </li>`;
        } else {
            paginationHTML += `
                <li class="page-item disabled">
                    <span class="page-link">»</span>
                </li>`;
        }

        paginationHTML += `</ul>`;
        paginationContainer.html(paginationHTML);
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
