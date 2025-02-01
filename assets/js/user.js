function loadUserInfo() {
    const userId = localStorage.getItem("user_id");
    const token = localStorage.getItem("token");

    if (!userId || !token) {
        console.error("No hay usuario autenticado o falta el token en localStorage.");
        return;
    }

    $.ajax({
        url: `${BASE_URL}/routes/user.php?action=getUser&user_id=${encodeURIComponent(userId)}&token=${encodeURIComponent(token)}`,
        type: "GET",
        dataType: "json",
        success: function(response) {
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
            console.error("Error en la solicitud:", xhr.responseText);
        }
    });
}

// Ejecutar la función al abrir el modal
$("#userInfoModal").on("show.bs.modal", function() {
    loadUserInfo();
});
