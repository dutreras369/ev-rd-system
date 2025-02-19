
$(document).ready(function () {

    const userId = localStorage.getItem("user_id");
    const token = localStorage.getItem("token");
    
    function loadTotalRecords() {
        if (!$("#analysis-section").length) return;
    
        const userId = localStorage.getItem("user_id");
        const token = localStorage.getItem("token");
    
        $.ajax({
            url: `${BASE_URL}/routes/analysis.php?action=total_records`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ user_id: userId, token: token }),
            success: function (response) {
                if (response.success) {
                    console.log("📊 Totales recibidos:", response);
    
                    function formatCurrency(value) {
                        return value > 0 ? `$${parseFloat(value).toLocaleString()}` : "$—";
                    }
    
                    $("#deposit-count").text(formatCurrency(response.total_cargas));
                    $("#withdrawal-count").text(formatCurrency(response.total_retiros));
                    $("#daily-records").text(response.total_registros);
                    $("#incorrect-records").text(formatCurrency(response.monto_incorrectos));
                } else {
                    console.error("⚠️ Error al obtener totales:", response.error);
                }
            },
            error: function (xhr) {
                console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    function loadUserRecords() {
        if (!$("#user-analysis-table-body").length) return;
    
        $.ajax({
            url: `${BASE_URL}/routes/analysis.php?action=user_records`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ user_id: userId, token: token }),
            success: function (response) {
                if (response.success) {
                    console.log("📊 Datos de usuarios:", response.records);
                    $("#user-analysis-table-body").empty();
    
                    response.records.forEach((user, index) => {
                        $("#user-analysis-table-body").append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${user.nombre}</td>
                                <td>${user.registros_mes}</td>
                                <td>${user.correctos}</td>
                                <td>${user.incorrectos}</td>
                                <td>
                                    <button class="btn btn-outline-primary filterDetailsForm" data-user="${user.id}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
    
                    // Agregar evento a los botones de "ver registros"
                    $("#filterDetailsForm").submit(function (event) {
                        event.preventDefault();
                        loadFilteredRecords();
                    });
                } else {
                    console.error("⚠️ Error al obtener registros de usuarios:", response.error);
                }
            },
            error: function (xhr) {
                console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }
    
   
    loadUserRecords();
    loadTotalRecords();
});