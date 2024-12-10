$(document).ready(function () {
  // Manejar la navegación del menú
  function handleMenuNavigation() {
    $(".menu-toggle").on("click", function () {
      const $menu = $("#primary-menu");
      const isExpanded = $(this).attr("aria-expanded") === "true";

      if (isExpanded) {
        $menu.slideUp(300);
        $(this).attr("aria-expanded", "false");
      } else {
        $menu.slideDown(300);
        $(this).attr("aria-expanded", "true");
      }

      $(".main-navigation").toggleClass("menu-open");
    });
  }

  // Manejar la navegación del sidebar
  function handleSidebarNavigation() {
    // Toggle del Sidebar usando la clase común
    $(".sidebar-toggle").on("click", function () {
      toggleSidebar(this);
    });

    // Cerrar el Sidebar al hacer clic fuera de él (Opcional)
    $(document).on("click", function (event) {
      const sidebar = $("#sidebar-wrapper");
      const toggleButtons = $(".sidebar-toggle");

      if (
        !sidebar.is(event.target) &&
        sidebar.has(event.target).length === 0 &&
        !toggleButtons.is(event.target)
      ) {
        sidebar.removeClass("sidebar-open");
        toggleButtons.attr("aria-expanded", "false");
      }
    });
  }

  // Función para alternar la visibilidad del sidebar
  function toggleSidebar(button) {
    const $sidebar = $("#sidebar-wrapper");
    const isExpanded = $(button).attr("aria-expanded") === "true";

    if (isExpanded) {
      $sidebar.removeClass("sidebar-open");
      $(button).attr("aria-expanded", "false");
    } else {
      $sidebar.addClass("sidebar-open");
      $(button).attr("aria-expanded", "true");
    }
  }

  // Manejar el formulario de inicio de sesión
  function handleLogin() {
    $("#loginForm").on("submit", function (event) {
      event.preventDefault(); // Evita que el formulario se envíe

      // Obtiene los valores del formulario
      const email = $("#email").val().trim();
      const password = $("#password").val().trim();

      // Limpia el contenedor de alertas antes de mostrar una nueva
      $("#loginAlert").html("");

      // Validación básica de los campos
      if (email === "" || password === "") {
        $("#loginAlert").html(`
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Error:</strong> Por favor completa todos los campos.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        `);
        return;
      }

      // Simulación del login y redirección al Dashboard
      $("#loginAlert").html(`
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Éxito:</strong> Inicio de sesión exitoso. Redirigiendo al Dashboard...
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `);

      setTimeout(function () {
        window.location.href =
          BASE_URL + "/dashboard/admin"; // URL de redirección simulada
      }, 2000);
    });

    // Enlace para "Recordar contraseña"
    $("#forgot-password-link").on("click", function (event) {
      event.preventDefault();
      $("#loginAlert").html(`
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Info:</strong> Se abrirá el formulario para recordar contraseña (simulación).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `);
    });

    // Enlace para "Crear cuenta"
    $("#create-account-link").on("click", function (event) {
      event.preventDefault();
      $("#loginAlert").html(`
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Info:</strong> Se abrirá el formulario para crear una cuenta (simulación).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `);
    });
  }

  // Ejecutar todas las funciones
  handleMenuNavigation();
  handleSidebarNavigation();
  handleLogin();
});
