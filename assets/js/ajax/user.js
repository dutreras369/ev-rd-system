$(document).ready(function () {
    const userId = localStorage.getItem("user_id");
    const token = localStorage.getItem("token");

    function loadUserInfo() {
        console.log("Petición a getUser - userId:", userId, "token:", token);

        if (!userId || !token) {
            console.error("No hay usuario autenticado o falta el token en localStorage.");
            return;
        }

        $.ajax({
            url: BASE_URL + "/routes/user.php?action=user_info",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ user_id: userId, token: token }),
            dataType: "json",
            success: function (response) {
                console.log("Respuesta de get_user:", response);
                if (response.success) {
                    $("#userName").text(response.user.nombre);
                    $("#userEmail").text(response.user.email);
                    $("#userRole").text(response.user.rol);
                    $("#userHoursIn").text(response.user.hora_inicio);
                } else {
                    console.error("Error al obtener usuario:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    function loadUsers() {
        if (!$("#users-table-body").length) return;

        $.ajax({
            url: `${BASE_URL}/routes/user.php?action=list_users`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ user_id: userId, token: token }),
            success: function (response) {
                if (response.success) {
                    console.log("📊 Lista de usuarios:", response.users);
                    $("#users-table-body").empty();

                    response.users.forEach((user, index) => {
                        $("#users-table-body").append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${user.nombre}</td>
                                <td>${user.email}</td>
                                <td>${user.rol}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="#" class="btn-icon btn-secondary view-user" data-user="${user.id}" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <a href="#" class="btn-icon btn-warning edit-user" data-user="${user.id}" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <a href="#" class="btn-icon btn-danger delete-user" data-user="${user.id}" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    // Agregar eventos a los botones
                    $(".view-user").click(function () {
                        let userId = $(this).data("user");
                        viewUserDetails(userId);
                    });

                    $(".edit-user").click(function () {
                        let userId = $(this).data("user");
                        editUser(userId);
                    });

                    $(".delete-user").click(function () {
                        let userId = $(this).data("user");
                        deleteUser(userId);
                    });
                } else {
                    console.error("⚠️ Error al obtener usuarios:", response.error);
                }
            },
            error: function (xhr) {
                console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    /** 🔹 Manejo del formulario de agregar usuario */
    $("#addWorkerForm").submit(function (event) {
        event.preventDefault();

        const nombre = $("#worker-name").val().trim();
        const email = $("#worker-email").val().trim();
        const rol = $("#worker-role").val();
        const contrasena = $("#worker-password").val().trim();

        if (!nombre || !email || !rol || !contrasena) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Todos los campos son obligatorios.",
                confirmButtonColor: "#d33",
            });
            return;
        }

        // Convertir rol a ID numérico
        let rol_id = rol === "admin" ? 1 : 2;
        const username = nombre.toLowerCase().replace(/\s+/g, '');

        const formData = {
            usuario_id: userId,
            nombre: nombre,
            email: email,
            rol_id: rol_id,
            username: username,
            contrasena: contrasena
        };

        $.ajax({
            url: `${BASE_URL}/routes/user.php?action=add`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Usuario agregado",
                        text: "El usuario ha sido registrado correctamente.",
                        confirmButtonColor: "#28a745",
                        timer: 1200,
                        showConfirmButton: false
                    });

                    // Limpiar formulario y cerrar modal
                    $("#addWorkerForm")[0].reset();
                    $("#addWorkerModal").modal("hide");

                    // Recargar la tabla de usuarios
                    loadUsers();
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


    /** 🔹 Función para cargar datos en el modal de edición */
    function editUser(id) {
        $.ajax({
            url: `${BASE_URL}/routes/user.php?action=get_user`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ id: id, token: token }),
            success: function (response) {
                if (response.success) {
                    // Mapear datos en el modal
                    $("#edit-worker-id").val(response.user.id);
                    $("#edit-worker-name").val(response.user.nombre);
                    $("#edit-worker-email").val(response.user.email);
                    $("#edit-worker-role").val(response.user.rol);
                    $("#edit-worker-password").val(""); // Limpiar campo de contraseña

                    // Mostrar el modal
                    $("#editWorkerModal").modal("show");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo obtener la información del usuario.",
                        confirmButtonColor: "#d33",
                    });
                }
            },
            error: function (xhr) {
                console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    /** 🔹 Manejo del formulario de edición */
    $("#editWorkerForm").submit(function (event) {
        event.preventDefault();

        const userId = $("#edit-worker-id").val();
        const role = $("#edit-worker-role").val();
        const password = $("#edit-worker-password").val().trim();

        if (!userId || !role) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "El rol es obligatorio.",
                confirmButtonColor: "#d33",
            });
            return;
        }

        const formData = {
            user_id: userId,
            token: token,
            rol: role
        };

        // Solo incluir la contraseña si el usuario ingresó una nueva
        if (password) {
            formData.contrasena = password;
        }

        $.ajax({
            url: `${BASE_URL}/routes/user.php?action=edit_user`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Usuario actualizado",
                        text: "Los cambios se han guardado correctamente.",
                        confirmButtonColor: "#28a745",
                        timer: 1200,
                        showConfirmButton: false
                    });

                    $("#editWorkerModal").modal("hide");
                    loadUsers(); // Recargar lista de usuarios
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
                console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    });

    // Ejecutar la función al abrir el modal
    $("#userInfoModal").on("show.bs.modal", function () {
        loadUserInfo();
    });

    loadUsers();
});
