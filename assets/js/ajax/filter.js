$(document).ready(function () {
    
    /** 🔹 Abrir modal de filtro general */
    $("#openFilterModal").click(function () {
        loadFilteredRecords(); // Cargar datos generales
        $("#viewDetailsModal").modal("show");
    });
    
    /** 🔹 Manejo del formulario dentro del modal */
    $("#filterDetailsForm").submit(function (event) {
        event.preventDefault(); // 🔹 Evitar recarga de página
                
        console.log("🔍 Enviando filtro para usuario ID:", userId);
        
        loadFilteredRecords(); // 🔹 Recargar registros con el filtro aplicado
    });

   
});

/** 🔹 Cargar registros filtrados */
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
            console.log("🔍 Respuesta del filtro:", response); // Depuración

            let tableBody = $("#details-table-body");

            // 🔹 Limpiar la tabla antes de agregar nuevos datos
            tableBody.empty();

            if (response.success && response.records.length > 0) {
                response.records.forEach((record, index) => {
                    tableBody.append(`
                        <tr>
                            <td>${index + 1 + offset}</td>
                            <td>${record.codigo_usuario}</td>
                            <td>${record.fecha}</td>
                            <td>${record.tipo}</td>
                            <td>$${parseFloat(record.monto).toLocaleString()}</td>
                            <td>${record.estado === "true" ? "Correcto" : "Incorrecto"}</td>
                        </tr>
                    `);
                });

                // 🔹 Actualizar paginación
                updatePagination(response.current_page, response.total_pages, userId);
            } else {
                tableBody.append(`
                    <tr>
                        <td colspan="6" class="text-center text-muted">No se encontraron registros.</td>
                    </tr>
                `);
                $("#pagination").empty(); // 🔹 Limpiar paginador si no hay datos
            }

            // 🔹 Forzar actualización del DOM
            $("#viewDetailsModal").modal("show");
            $(".modal-body").scrollTop(0);

            console.log("✅ Tabla actualizada dentro del modal.");
        },
        error: function (xhr) {
            console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
        }
    });
}

/** 🔹 Función para actualizar la paginación */
function updatePagination(currentPage, totalPages, userId) {
    let pagination = $("#pagination");
    pagination.empty();

    if (totalPages > 1) {
        let prevDisabled = currentPage === 1 ? "disabled" : "";
        let nextDisabled = currentPage === totalPages ? "disabled" : "";

        pagination.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, ${currentPage - 1})">Anterior</a>
            </li>
        `);

        for (let i = 1; i <= totalPages; i++) {
            let active = i === currentPage ? "active" : "";
            pagination.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, ${i})">${i}</a>
                </li>
            `);
        }

        pagination.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" onclick="loadFilteredRecords(${userId}, ${currentPage + 1})">Siguiente</a>
            </li>
        `);
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
