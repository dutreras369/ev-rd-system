
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

  /** 🔹 Evento para ver registros por usuario */
  $(document).on("click", ".view-records", function () {
    let codigoUsuario = $(this).data("user");

    $.ajax({
      url: `${BASE_URL}/record.php?action=filter`,
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        user_id: userId,
        token: token
        // codigo_usuario: codigoUsuario - este es un codigo del usuario foraneo que este caso no sera utilizado 
        // Segun la fecha del presente podemos dar un rango para el filtro, de 5 dias hacia atras con paginador 
      }),
      success: function (response) {
        if (response.success) {
          $("#details-table-body").empty();
          response.records.forEach((record, index) => {
            $("#details-table-body").append(`
                      <tr>
                          <td>${index + 1}</td>
                          <td>${record.codigo_usuario}</td>
                          <td>${record.fecha}</td>
                          <td>${record.tipo}</td>
                          <td>$${parseFloat(record.monto).toLocaleString()}</td>
                          <td>${record.estado}</td>
                      </tr>
                  `);
          });

          $("#viewDetailsModal").modal("show");
        } else {
          console.error("Error al cargar registros:", response.error);
        }
      },
      error: function (xhr) {
        console.error("Error en la solicitud:", xhr.status, xhr.responseText);
      }
    });
  });


  /** 🔹 Filtrar registros */
  function setupFilterForm() {
    if (!$("#filterDetailsForm").length) return;

    $("#filterDetailsForm").submit(function (event) {
      event.preventDefault();

      const dateFrom = $("#filter-date-from").val();
      const dateTo = $("#filter-date-to").val();
      const type = $("#filter-type").val();
      const status = $("#filter-status").val();

      $.ajax({
        url: `${BASE_URL}/routes/record.php?action=filter`,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
          user_id: userId,
          token: token,
          date_from: dateFrom,
          date_to: dateTo,
          status: status,
          page: currentPage,
          limit: recordsPerPage
        }),
        success: function (response) {
          if (response.success) {
            $("#details-table-body").empty();
            response.records.forEach(record => {
              $("#details-table-body").append(`
                              <tr>
                                  <td>${record.codigo_usuario}</td>
                                  <td>${record.tipo}</td>
                                  <td>$${parseFloat(record.monto).toLocaleString()}</td>
                                  <td>${record.fecha}</td>
                                  <td>${record.estado}</td>
                              </tr>
                          `);
            });
            updatePagination(response.currentPage, response.totalPages);
          } else {
            console.error("Error en filtro:", response.error);
          }
        }
      });
    });
  }

  setupFilterForm();
});