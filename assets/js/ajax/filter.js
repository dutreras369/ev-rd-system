$(document).ready(function () {
  $("#filterDetailsForm").submit(function (event) {
      event.preventDefault();
      loadFilteredRecords();
  });

  function loadFilteredRecords(page = 1) {
      let dateFrom = $("#filter-date-from").val();
      let dateTo = $("#filter-date-to").val();
      let type = $("#filter-type").val();
      let user = $("#filter-user").val();
      let status = $("#filter-status").val();

      $.ajax({
          url: `${BASE_URL}/routes/filter.php?action=filter_records`,
          type: "POST",
          contentType: "application/json",
          data: JSON.stringify({
              user_id: user,
              token: token,
              date_from: dateFrom,
              date_to: dateTo,
              type: type,
              status: status,
              page: page,
              limit: 10
          }),
          success: function (response) {
              if (response.success) {
                  $("#details-table-body").empty();
                  response.records.forEach((record, index) => {
                      $("#details-table-body").append(`
                          <tr>
                              <td>${index + 1}</td>
                              <td>${record.usuario}</td>
                              <td>${record.fecha}</td>
                              <td>${record.tipo}</td>
                              <td>$${parseFloat(record.monto).toLocaleString()}</td>
                              <td>${record.estado}</td>
                          </tr>
                      `);
                  });

                  updatePagination(response.current_page, response.total_pages);
              } else {
                  console.error("⚠️ No se encontraron registros:", response.error);
              }
          },
          error: function (xhr) {
              console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
          }
      });
  }

  function updatePagination(currentPage, totalPages) {
      $("#pagination").empty();

      if (currentPage > 1) {
          $("#pagination").append(`<button class="page-btn" data-page="${currentPage - 1}">Anterior</button>`);
      }

      for (let i = 1; i <= totalPages; i++) {
          let activeClass = i === currentPage ? "active" : "";
          $("#pagination").append(`<button class="page-btn ${activeClass}" data-page="${i}">${i}</button>`);
      }

      if (currentPage < totalPages) {
          $("#pagination").append(`<button class="page-btn" data-page="${currentPage + 1}">Siguiente</button>`);
      }

      $(".page-btn").click(function () {
          let page = $(this).data("page");
          loadFilteredRecords(page);
      });
  }
});
