
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
    
                    // Función para formatear los valores en moneda y evitar mostrar "0"
                    function formatCurrency(value) {
                        return value > 0 ? `$${parseFloat(value).toLocaleString()}` : "$—";
                    }
    
                    // Mapear los valores al DOM con formato
                    $("#deposit-count").text(formatCurrency(response.total_records));
                    $("#correct-records").text(formatCurrency(response.total_correct));
                    $("#incorrect-records").text(formatCurrency(response.total_incorrect));
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