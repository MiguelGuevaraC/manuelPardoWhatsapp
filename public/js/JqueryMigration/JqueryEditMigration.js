//FUNCION EDITAR
function editRol(id) {
    $("#registroRolE")[0].reset();

   
    $("#modalEditarRolE").modal("show");

    $.ajax({
        url: "migracion/" + id,
        type: "GET",
        dataType: "json",
        success: function (data) {
            $("#nameE").val(data.name);
            $("#idE").val(data.id);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(
                "Error al cargar datos del tipo de usuario:",
                errorThrown
            );
        },
    });
}

$(document).on("click", "#cerrarModalE", function () {
    $("#modalEditarRolE").modal("hide");
});
