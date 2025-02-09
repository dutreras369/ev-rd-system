$(document).ready(function () {
    const userId = localStorage.getItem("user_id");
    const token = localStorage.getItem("token");
    let selectedType = null;
    let currentPage = 1;
    const recordsPerPage = 5;

    if (!userId || !token) {
        console.error("Usuario no autenticado.");
        return;
    }

    /** 🔹 Evento para seleccionar el tipo de movimiento */
    function setupMovementSelection() {
        if ($(".movement-type").length) {
            $(".movement-type").on("click", function () {
                $(".movement-type").removeClass("btn-success btn-danger active")
                    .addClass("btn-outline-success btn-outline-danger");

                selectedType = $(this).data("type");

                if (selectedType === "carga") {
                    $(this).removeClass("btn-outline-success").addClass("btn-success active");
                } else if (selectedType === "retiro") {
                    $(this).removeClass("btn-outline-danger").addClass("btn-danger active");
                }
            });
        }
    }

    /** 🔹 Envío del formulario de registro */
    function setupRegisterForm() {
        if ($("#registerForm").length) {
            $("#registerForm").submit(function (event) {
                event.preventDefault();

                const codigoUsuario = $("#codigo_usuario").val();
                const amount = $("#amount").val();

                if (!codigoUsuario || !selectedType || !amount) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Todos los campos son obligatorios.",
                        confirmButtonColor: "#d33",
                    });
                    return;
                }

                const now = new Date();
                const timestamp = now.toISOString().slice(0, 19).replace("T", " ");

                const formData = {
                    user_id: userId,
                    codigo_usuario: codigoUsuario,
                    token: token,
                    movement_type: selectedType,
                    amount: amount,
                    timestamp: timestamp
                };

                $.ajax({
                    url: `${BASE_URL}/routes/record.php?action=register`,
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(formData),
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Registro exitoso",
                                text: "Movimiento registrado correctamente.",
                                confirmButtonColor: "#28a745",
                                timer: 1200,
                                showConfirmButton: false
                            });

                            resetRegisterForm();
                            setTimeout(() => {
                                $("#registerModal").modal("hide");
                                loadRecords(currentPage);
                            }, 1600);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.error,
                                confirmButtonColor: "#d33",
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error("Error en la solicitud:", xhr.status, xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Error en la solicitud",
                            text: "Ver consola para más detalles.",
                            confirmButtonColor: "#d33",
                        });
                    },
                });
            });
        }
    }

    /** 🔹 Restablecer formulario después de registrar */
    function resetRegisterForm() {
        $("#codigo_usuario, #amount").val("");
        selectedType = null;
        $(".movement-type").removeClass("btn-success btn-danger active")
            .addClass("btn-outline-success btn-outline-danger");
    }

    /** 🔹 Cargar registros paginados */
    function loadRecords(page = 1) {
        if (!$("#daily-records-table").length) return;
    
        $.ajax({
            url: `${BASE_URL}/routes/record.php?action=list`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: userId,
                token: token,
                page: page,
                limit: 5
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
    
                    updatePagination(response.current_page, response.total_pages);
                } else {
                    console.error("No hay registros:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    /** 🔹 Actualizar paginación */
    function updatePagination(currentPage, totalPages) {
        if (!$("#pagination").length) return;

        $("#pagination").empty();

        if (currentPage > 1) {
            $("#pagination").append(`<button class="page-btn" data-page="${currentPage - 1}">Anterior</button>`);
        }

        for (let i = 1; i <= totalPages; i++) {
            let activeClass = i === currentPage ? "active" : "";
            $("#pagination").append(`<button class="page-btn ${activeClass}" data-page="${i}">${i}</button>`);
        }

        if (currentPage < totalPages) {
            $("#pagination").append(`<button class="page-btn" data-page="${currentPage + 1}">Siguiente</button>`);
        }

        $(".page-btn").click(function () {
            let page = $(this).data("page");
            loadRecords(page);
        });
    }

    function loadTotalRecords() {
        if (!$("#records-section").length) return;

        $.ajax({
            url: `${BASE_URL}/routes/record.php?action=total_records`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ user_id: userId, token: token }),
            success: function (response) {
                if (response.success) {
                    $("#daily-records").text(response.total_records);
                    $("#correct-records").text(response.total_correct);
                    $("#incorrect-records").text(response.total_incorrect);
                } else {
                    console.error("Error al obtener totales:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    /** 🔹 Evento para ver registros por usuario */
    $(document).on("click", ".view-records", function () {
        let codigoUsuario = $(this).data("user");

        $.ajax({
            url: `${BASE_URL}/record.php?action=filter`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: userId,
                token: token
                // codigo_usuario: codigoUsuario - este es un codigo del usuario foraneo que este caso no sera utilizado 
                // Segun la fecha del presente podemos dar un rango para el filtro, de 5 dias hacia atras con paginador 
            }),
            success: function (response) {
                if (response.success) {
                    $("#details-table-body").empty();
                    response.records.forEach((record, index) => {
                        $("#details-table-body").append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${record.codigo_usuario}</td>
                            <td>${record.fecha}</td>
                            <td>${record.tipo}</td>
                            <td>$${parseFloat(record.monto).toLocaleString()}</td>
                            <td>${record.estado}</td>
                        </tr>
                    `);
                    });

                    $("#viewDetailsModal").modal("show");
                } else {
                    console.error("Error al cargar registros:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    });


    /** 🔹 Filtrar registros */
    function setupFilterForm() {
        if (!$("#filterDetailsForm").length) return;

        $("#filterDetailsForm").submit(function (event) {
            event.preventDefault();

            const dateFrom = $("#filter-date-from").val();
            const dateTo = $("#filter-date-to").val();
            const type = $("#filter-type").val();
            const status = $("#filter-status").val();

            $.ajax({
                url: `${BASE_URL}/routes/record.php?action=filter`,
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
                        updatePagination(response.currentPage, response.totalPages);
                    } else {
                        console.error("Error en filtro:", response.error);
                    }
                }
            });
        });
    }

    /** 🔹 Inicializar funciones */
    setupMovementSelection();
    setupRegisterForm();
    setupFilterForm();
    loadRecords();
    loadTotalRecords();
});
