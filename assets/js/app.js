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

 

  // Inicializar funciones
  handleMenuNavigation();
  handleSidebarNavigation();
  handleNumberButtonSelection();
});
