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

    /** 🔹 Manejo del formulario de exportacion a excel */
    $("#exportExcelForm").submit(function (event) {
        event.preventDefault(); // Evitar recarga de la página
        exportRecordsToExcel(); // Llamar a la función para exportar
    });
    

});

/** 🔹 Evento para actualizar el estado del registro */
$(document).on("change", ".toggle-status", function () {
    let recordId = $(this).data("record");
    let newStatus = $(this).is(":checked") ? "correcto" : "incorrecto";

    Swal.fire({
        title: "¿Estás seguro?",
        text: "Cambiarás el estado de este registro.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, cambiar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${BASE_URL}/routes/filter.php?action=update_status`,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    record_id: recordId,
                    status: newStatus,
                    token: localStorage.getItem("token")
                }),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Estado actualizado",
                            text: response.message,
                            confirmButtonColor: "#28a745",
                            timer: 1200,
                            showConfirmButton: false
                        });

                        // 🔹 Refrescar solo el registro cambiado
                        $(`#status-${recordId}`).prop("checked", newStatus === "correcto");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.error,
                            confirmButtonColor: "#d33"
                        });
                    }
                },
                error: function (xhr) {
                    console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
                }
            });
        } else {
            // Revertir cambio si el usuario cancela
            $(this).prop("checked", !$(this).is(":checked"));
        }
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
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox" id="status-${record.id}" data-record="${record.id}" ${record.estado === "correcto" ? "checked" : ""}>
                                    <label class="form-check-label" for="status-${record.id}">${record.estado}</label>
                                </div>
                            </td>
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

/** 🔹 Refrescar SOLO la fila del registro editado */
function updateSingleRecord(recordId) {
    $.ajax({
        url: `${BASE_URL}/routes/record.php?action=get_record`,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({ record_id: recordId, token: token }),
        success: function (response) {
            if (response.success) {
                let record = response.record;
                let row = $(`#switch${recordId}`).closest("tr");

                // Actualizar los datos en la fila correspondiente
                row.html(`
                    <td>${record.trabajador_nombre || "N/A"}</td>
                    <td>${record.codigo_usuario || "N/A"}</td>
                    <td>${record.fecha}</td>
                    <td>${record.tipo}</td>
                    <td>$${parseFloat(record.monto).toLocaleString()}</td>
                    <td class="text-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-switch" type="checkbox" id="switch${record.id}" data-id="${record.id}" ${record.estado === "correcto" ? "checked" : ""}>
                            <label class="form-check-label" for="switch${record.id}">${record.estado === "correcto" ? "Correcto" : "Incorrecto"}</label>
                        </div>
                    </td>
                `);
            } else {
                console.error("⚠️ No se pudo actualizar el registro en la tabla.");
            }
        },
        error: function (xhr) {
            console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
        }
    });
}


/** 🔹 Función para exportar registros a Excel */
function exportRecordsToExcel() {
    $.ajax({
        url: `${BASE_URL}/routes/filter.php?action=export_excel`,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            user_id: $("#export-user").val(),
            date_from: $("#export-date-from").val() || getDefaultStartDate(),
            date_to: $("#export-date-to").val() || getTodayDate(),
            type: $("#export-type").val(),
            status: $("#export-status").val(),
            token: localStorage.getItem("token")
        }),
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    icon: "success",
                    title: "Excel generado",
                    text: "Haz clic en el botón para descargar.",
                    confirmButtonText: '<a href="' + BASE_URL + '/' + response.file_url + '" download class="btn btn-success">Descargar</a>',
                    showConfirmButton: true
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.error,
                    confirmButtonColor: "#d33"
                });
            }
        },
        error: function (xhr) {
            console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
        }
    });
}
