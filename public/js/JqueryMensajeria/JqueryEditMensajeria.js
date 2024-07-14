function editMensajeria(id) {
    $("#registroMensajeriaE")[0].reset(); // Reinicia el formulario para evitar datos residuales

    // Muestra el modal de edición
    $("#modalEditarMensajeriaE").modal("show");

    // Petición AJAX para obtener los datos del mensajeria por su ID
    $.ajax({
        url: "mensajeria/" + id,
        type: "GET",
        dataType: "json",
        success: function (data) {
            // Llenar los campos del formulario con los datos recibidos
            console.log(data.motherSurname);
            $("#idE").val(data.id);
            $("#dniMensajeriaE").val(data.documentNumber);
            $("#nombreMensajeriaE").val(data.names);
            $("#fatherSurnameE").val(data.fatherSurname);
            $("#motherSurnameE").val(data.motherSurname);
            $("#businessNameE").val(data.businessName);
            $("#levellE").val(data.level);
            $("#gradooE").val(data.grade);
            $("#seccionE").val(data.section);
            $("#nombreApoderadoE").val(data.representativeDni);
            $("#dniApoderadoE").val(data.representativeNames);
            $("#telefonoE").val(data.telephone);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error al cargar datos del mensajeria:", errorThrown);
        },
    });
}

$(document).on("click", "#cerrarModalE", function () {
    $("#modalEditarMensajeriaE").modal("hide");
});
