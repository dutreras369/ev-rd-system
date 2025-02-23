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

/** 🔹 Evento para cambiar estado con confirmación */
$(document).on("change", ".toggle-switch", function () {
    let switchInput = $(this);
    let recordId = switchInput.data("id");
    let newStatus = switchInput.is(":checked") ? "correcto" : "incorrecto";
    const token = localStorage.getItem("token");

    // Mostrar confirmación antes de cambiar el estado
    Swal.fire({
        title: "¿Confirmar cambio de estado?",
        text: `Estás a punto de marcar este registro como "${newStatus.toUpperCase()}".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, actualizar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${BASE_URL}/routes/filter.php?action=update_status`,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({ record_id: recordId, status: newStatus, token: token }),
                success: function (response) {
                    if (response.success) {
                        // Actualizar visualmente el switch y el texto
                        switchInput.next("label").text(newStatus === "correcto" ? "Correcto" : "Incorrecto");

                        Swal.fire({
                            title: "Estado actualizado",
                            text: "El estado del registro ha sido cambiado exitosamente.",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Refrescar SOLO la fila editada sin recargar toda la tabla
                        updateSingleRecord(recordId);
                    } else {
                        Swal.fire("Error", response.error, "error");
                        // Restaurar el estado anterior si falla la actualización
                        switchInput.prop("checked", !switchInput.is(":checked"));
                    }
                },
                error: function (xhr) {
                    console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
                    Swal.fire("Error", "No se pudo actualizar el estado.", "error");
                    switchInput.prop("checked", !switchInput.is(":checked")); // Restaurar estado anterior
                }
            });
        } else {
            // Restaurar el estado anterior si se cancela la acción
            switchInput.prop("checked", !switchInput.is(":checked"));
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
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-switch" type="checkbox" id="switch${record.id}" data-id="${record.id}" ${record.estado === "correcto" ? "checked" : ""}>
                                    <label class="form-check-label" for="switch${record.id}">${record.estado === "correcto" ? "Correcto" : "Incorrecto"}</label>
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
