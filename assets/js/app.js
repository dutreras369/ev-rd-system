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
    $(".sidebar-toggle").on("click", function () {
      toggleSidebar(this);
    });

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

  
  // Botones de acceso rápido para monto
  function handleNumberButtonSelection() {
    $(".quick-amount").on("click", function () {
      const amount = $(this).data("amount");
      $("#amount").val(amount);
    });
  }

  function initializeMovementTypeSelection(formSelector) {
    if ($(formSelector).length === 0) return; // Solo se ejecuta si el formulario existe

    console.log("Inicializando selector de movimiento en:", formSelector);

    // Manejar selección de tipo de movimiento
    $(formSelector).find(".movement-type").on("click", function () {
        // Remover la clase "active" de todos los botones dentro del formulario
        $(formSelector).find(".movement-type").removeClass("btn-success btn-danger")
            .addClass("btn-outline-success btn-outline-danger");

        // Agregar la clase activa al botón seleccionado
        if ($(this).data("type") === "carga") {
            $(this).removeClass("btn-outline-success").addClass("btn-success");
        } else {
            $(this).removeClass("btn-outline-danger").addClass("btn-danger");
        }

        // Actualizar el valor del select oculto dentro del formulario
        $(formSelector).find("#movement-type").val($(this).data("type"));
    });

    // Manejar los botones de monto rápido dentro del formulario
    $(formSelector).find(".quick-amount").on("click", function () {
        $(formSelector).find("#amount").val($(this).data("amount"));
    });
  }

  // Ejecutar solo cuando el modal de registro se muestra
  $("#registerModal").on("shown.bs.modal", function () {
      initializeMovementTypeSelection("#registerForm");
  });


  // Inicializar funciones
  handleMenuNavigation();
  handleSidebarNavigation();
  handleNumberButtonSelection();
});
