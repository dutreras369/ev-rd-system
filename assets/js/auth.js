$(document).ready(function () {
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
        url: `${BASE_URL}/controllers/AuthController.php`,
        type: "POST",
        data: JSON.stringify({ email: email, password: password }), // Enviar datos en JSON
        contentType: "application/json", // Especificar tipo de contenido
        dataType: "json",
        timeout: 10000, // Tiempo máximo de espera (10 segundos)
        beforeSend: function () {
          // Mostrar spinner o mensaje de carga
          $("#loginAlert").html(`
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Espere:</strong> Procesando su solicitud...
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        },
        success: function (response) {
          if (response.success) {
            // Guardar datos de sesión en localStorage
            localStorage.setItem("user_id", response.user.id);
            localStorage.setItem("user_role", response.user.rol);
            localStorage.setItem("login_time", response.login_time);

            $("#loginAlert").html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Éxito:</strong> ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);

            // Redirigir según el rol
            const redirectUrl =
              response.user.rol === "admin"
                ? `${BASE_URL}/public/dashboard/admin.php`
                : `${BASE_URL}/public/dashboard/user.php`;

            setTimeout(() => {
              window.location.href = redirectUrl;
            }, 2000);
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

          $("#loginAlert").html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> Ha ocurrido un problema al procesar la solicitud.<br>
                        Detalle: ${xhr.responseText || error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        },
        complete: function () {
          // Limpiar spinner o mensaje de carga
          console.log("Solicitud completada.");
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
