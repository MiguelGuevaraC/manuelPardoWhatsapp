//FUNCION EDITAR
function editRol(id) {
    $("#registroUsuarioE")[0].reset();
   
    $("#modalNuevoUsuarioE").modal("show");

    $.ajax({
        url: "user/" + id,
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

$(document).on("click", "#cerrarModalUsuarioE", function () {
    $("#modalNuevoUsuarioE").modal("hide");
});
