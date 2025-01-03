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

  // Manejar el formulario de inicio de sesión
  function handleLogin() {
    $("#loginForm").on("submit", function (event) {
      event.preventDefault();
      const email = $("#email").val().trim();
      const password = $("#password").val().trim();
      $("#loginAlert").html("");

      if (email === "" || password === "") {
        $("#loginAlert").html(`
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Error:</strong> Completa todos los campos.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        `);
        return;
      }

      $("#loginAlert").html(`
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Éxito:</strong> Inicio de sesión exitoso. Redirigiendo al Dashboard...
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `);

      setTimeout(function () {
        window.location.href = BASE_URL + "/dashboard/admin";
      }, 2000);
    });
  }

  // Botones de acceso rápido para monto
  function handleNumberButtonSelection() {
    $(".quick-amount").on("click", function () {
      const amount = $(this).data("amount");
      $("#amount").val(amount);
    });
  }

  // Cargar registros desde JSON y mostrar en tabla
  function loadRecords() {
    $.ajax({
      url: "assets/data/data.json",
      type: "GET",
      dataType: "json",
      success: function (data) {
        renderTable(data);
      },
      error: function () {
        alert("Error al cargar los registros.");
      },
    });
  }

  function renderTable(records) {
    const tableBody = $("#records-table-body");
    tableBody.empty();
    if (records.length > 0) {
      records.forEach((record) => {
        tableBody.append(`
          <tr>
            <td>${record.id}</td>
            <td>${record.usuario}</td>
            <td>${record.fecha}</td>
            <td>${record.tipo}</td>
            <td>$${record.monto}</td>
            <td>${record.estado === "true" ? "Correcto" : "Incorrecto"}</td>
          </tr>
        `);
      });
    } else {
      tableBody.append('<tr><td colspan="6" class="text-center">No hay registros.</td></tr>');
    }
  }

  // Filtrar registros
  $("#filterDetailsForm").on("submit", function (event) {
    event.preventDefault();
    const filterFrom = $("#filter-date-from").val();
    const filterTo = $("#filter-date-to").val();
    const filterType = $("#filter-type").val();
    const filterUser = $("#filter-user").val();
    const filterStatus = $("#filter-status").val();

    $.ajax({
      url: "assets/data/data.json",
      type: "GET",
      dataType: "json",
      success: function (data) {
        const filtered = data.filter((record) => {
          return (
            (!filterFrom || record.fecha >= filterFrom) &&
            (!filterTo || record.fecha <= filterTo) &&
            (!filterType || record.tipo === filterType) &&
            (!filterUser || record.usuario === filterUser) &&
            (!filterStatus || record.estado === filterStatus)
          );
        });
        renderTable(filtered);
      },
    });
  });

  // Inicializar funciones
  handleMenuNavigation();
  handleSidebarNavigation();
  handleLogin();
  handleNumberButtonSelection();
  loadRecords();
});

