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
        url: BASE_URL + "/controllers/AuthController.php",
        type: "POST",
        data: { email: email, password: password },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#loginAlert").html(`
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Éxito:</strong> Inicio de sesión exitoso. Redirigiendo al Dashboard...
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            `);
  
            // Redirigir según el rol
            const role = response.role;
            const redirectUrl = role === "admin" 
              ? BASE_URL + "/public/dashboard/admin.php" 
              : BASE_URL + "/public/dashboard/user.php";
  
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
        error: function () {
          $("#loginAlert").html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error:</strong> Ha ocurrido un problema al procesar la solicitud.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          `);
        }
      });
    });
  }
  
  // Botones de acceso rápido para monto
  function handleNumberButtonSelection() {
    $(".quick-amount").on("click", function () {
      const amount = $(this).data("amount");
      $("#amount").val(amount);
    });
  }

  // Inicializar DataTables
  function initializeDataTable() {
    $('#dynamic-table').DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" // Traducción al español
      },
      paging: true,
      searching: true,
      ordering: true,
      autoWidth: false,
      responsive: true
    });
  }

  /* Cargar registros desde JSON y mostrar en tabla
  function loadRecords() {
    $.ajax({
      url: BASE_URL + "/assets/data/data.json",
      type: "GET",
      dataType: "json",
      success: function (data) {
        renderTable(data);
        initializeDataTable(); // Inicializar DataTables después de renderizar los registros
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
      url: BASE_URL + "/assets/data/data.json",
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
  });*/

  // Inicializar funciones
  handleMenuNavigation();
  handleSidebarNavigation();
  handleLogin();
  handleNumberButtonSelection();
  //loadRecords();
});
