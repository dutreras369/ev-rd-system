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
        url: BASE_URL + "/routes/auth.php?action=login",
        type: "POST",
        data: { email: email, password: password },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            const user = response.user;  // Definir correctamente la variable user

            localStorage.setItem("user_id", user.id);
            localStorage.setItem("user_role", user.rol);  // Guardar nombre del rol
            localStorage.setItem("login_time", new Date().toISOString());  // Guardar la fecha
            localStorage.setItem("token", user.token);

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

  // Verificar si el usuario ya está autenticado y evitar redirección infinita
  function checkExistingSession() {
    const userId = localStorage.getItem("user_id");
    const userRole = localStorage.getItem("user_role");

    if (userId && userRole) {
      const currentPath = window.location.pathname;

      const isAdminPage = currentPath.includes("/dashboard/admin.php");
      const isUserPage = currentPath.includes("/dashboard/user.php");

      const redirectUrl = userRole === "admin"
        ? BASE_URL + "/public/dashboard/admin.php"
        : BASE_URL + "/public/dashboard/user.php";

      // Evitar redireccionar si ya está en la página correcta
      if ((!isAdminPage && userRole === "admin") || (!isUserPage && userRole === "user")) {
        window.location.href = redirectUrl;
      }
    }
  }

  function updateUI() {
    const userId = localStorage.getItem("user_id");
    const userRole = localStorage.getItem("user_role");

    if (userId && userRole) {
      $("#loginButton").hide();
      $("#logoutButton, #userIcon").show();

      if (userRole === "admin") {
        $("#menuAdmin, #menuUser, #sidebarToggleMobile").show();
        $("#sidebarToggle").removeClass("d-none").addClass("d-lg-block");
      } else if (userRole === "user") {
        $("#menuUser").show();
      }
    } else {
      $("#loginButton").show();
      $("#logoutButton, #userIcon, #menuUser, #menuAdmin, #sidebarToggleMobile").hide();
      $("#sidebarToggle").addClass("d-none");
    }
  }

  function handleLogout() {
    $("#logoutButton").on("click", function () {
      const userId = localStorage.getItem("user_id");
      const token = localStorage.getItem("token");

      $.ajax({
        url: BASE_URL + "/routes/auth.php?action=logout",
        type: "POST",
        data: { user_id: userId, token: token},
        dataType: "json",
        success: function (response) {
          if (response.success) {
            localStorage.removeItem("user_id");
            localStorage.removeItem("user_role");
            localStorage.removeItem("token");
            localStorage.removeItem("login_time");

            window.location.href = BASE_URL + "/public/login.php";
          }
        },
        error: function () {
          console.error("Error al cerrar sesión.");
        }
      });
    });
  }


  /* Sincronizar localStorage con $_SESSION
  function syncSession() {
    $.ajax({
      url: BASE_URL + "/routes/auth.php?action=sessionStatus",
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.success && response.is_authenticated) {
          localStorage.setItem("user_id", response.user_id);
          localStorage.setItem("user_role", response.user_role);
          console.log("Sesión sincronizada:", response);
        } else {
          console.warn("No hay sesión activa.");
          localStorage.removeItem("user_id");
          localStorage.removeItem("user_role");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al sincronizar sesión:", xhr.responseText);
      }
    });
  }*/

  // Inicializar funciones
  checkExistingSession();
  handleLogin();
  updateUI();
  handleLogout();
  // syncSession();

});
