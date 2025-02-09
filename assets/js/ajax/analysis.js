
$(document).ready(function () {
    function loadTotalRecords() {
        if (!$("#records-section").length) return;
    
        $.ajax({
            url: `${BASE_URL}/routes/analysis.php?action=total_records`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ user_id: userId, token: token }),
            success: function (response) {
                if (response.success) {
                    $("#daily-records").text(response.total_records);
                    $("#correct-records").text(response.total_correct);
                    $("#incorrect-records").text(response.total_incorrect);
                } else {
                    console.error("Error al obtener totales:", response.error);
                }
            },
            error: function (xhr) {
                console.error("Error en la solicitud:", xhr.status, xhr.responseText);
            }
        });
    }

    loadTotalRecords();
});