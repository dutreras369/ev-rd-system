$(document).ready(function () {
    function loadUserInfo() {
        const userId = localStorage.getItem("user_id");
        const token = localStorage.getItem("token");
    
        console.log("Petición a getUser - userId:", userId, "token:", token);
    
        if (!userId || !token) {
            console.error("No hay usuario autenticado o falta el token en localStorage.");
            return;
        }
    
        $.ajax({
            url: BASE_URL + "/routes/user.php?action=get_user",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ user_id: userId, token: token }),
            dataType: "json",
            success: function(response) {
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
            error: function(xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    function loadUsers() {
        if (!$("#users-table-body").length) return;
    
        const userId = localStorage.getItem("user_id");
        const token = localStorage.getItem("token");
    
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
    
    // Ejecutar la función al abrir el modal
    $("#userInfoModal").on("show.bs.modal", function() {
        loadUserInfo();
    });
    
    loadUsers();

});
