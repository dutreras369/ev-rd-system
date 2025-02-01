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
            url: `${BASE_URL}/routes/user.php?action=getUser`,
            type: "POST", // Cambiamos a POST
            dataType: "json",
            data: {
                user_id: userId,
                token: token
            },
            success: function(response) {
                console.log("Respuesta de getUser:", response);
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
    

    // Ejecutar la función al abrir el modal
    $("#userInfoModal").on("show.bs.modal", function() {
        loadUserInfo();
    });
});
