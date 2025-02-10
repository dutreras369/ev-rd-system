
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
                    $("#incorrect-records").text(response.total_incorrectos);
                } else {
                    console.error("⚠️ Error al obtener totales:", response.error);
                }
            },
            error: function (xhr) {
                console.error("🚨 Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    loadTotalRecords();
});