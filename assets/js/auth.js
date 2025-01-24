$(document).ready(function () {
  // Manejar el formulario de inicio de sesión
  function handleLogin() {
    $("#loginForm").on("submit", function (event) {
      event.preventDefault();

      const email = $("#email").val().trim();
      const password = $("#password").val().trim();

      $("#loginAlert").html("");

      // Validar campos vacíos
      if (email === "" || password === "") {
        $("#loginAlert").html(`
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Error:</strong> Completa todos los campos.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `);
        return;
      }

      // Enviar datos al backend
      $.ajax({
        url: BASE_URL + "/routes/api.php?action=login",
        type: "POST",
        data: { email: email, password: password },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            localStorage.setItem("user_id", response.user.id);
            localStorage.setItem("user_role", response.user.rol);
            localStorage.setItem("login_time", response.user.login_time);

            $("#loginAlert").html(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Éxito:</strong> Inicio de sesión exitoso. Redirigiendo al Dashboard...
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          `);

            const redirectUrl = response.redirect_url;
            setTimeout(() => window.location.href = redirectUrl, 2000);
          } else {
            $("#loginAlert").html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error:</strong> ${response.error}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          `);
          }
        },
        error: function (xhr, status, error) {
          console.error("Error en la solicitud:", {
            status: status,
            error: error,
            response: xhr.responseText,
          });

          // Analizar la respuesta si es JSON
          let errorDetails = "Ha ocurrido un problema al procesar la solicitud.";
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.error_details) {
              errorDetails += `<br>Detalle: ${response.error_details}`;
            }
          } catch (e) {
            console.error("No se pudo parsear la respuesta:", xhr.responseText);
          }

          $("#loginAlert").html(`
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Error:</strong> ${errorDetails}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          `);
        },

      });
    });
  }


  // Verificar si el usuario ya está autenticado
  function checkExistingSession() {
    const userId = localStorage.getItem("user_id");
    const userRole = localStorage.getItem("user_role");

    if (userId && userRole) {
      const redirectUrl =
        userRole === "admin"
          ? BASE_URL + "/public/dashboard/admin.php"
          : BASE_URL + "/public/dashboard/user.php";

      window.location.href = redirectUrl;
    }
  }

  // Manejar el cierre de sesión
  function handleLogout() {
    $("#logoutButton").on("click", function () {
      localStorage.removeItem("user_id");
      localStorage.removeItem("user_role");
      localStorage.removeItem("login_time");
      window.location.href = BASE_URL + "/public/login.php";
    });
  }

  // Inicializar funciones
  checkExistingSession();
  handleLogin();
  handleLogout();
});
