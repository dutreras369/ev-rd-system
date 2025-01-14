
$(document).ready(function () {
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

    //loadRecords();
});