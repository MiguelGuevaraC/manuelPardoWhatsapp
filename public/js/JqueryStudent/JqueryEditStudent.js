function editStudent(id) {
    $("#registroStudentE")[0].reset(); // Reinicia el formulario para evitar datos residuales

    // Muestra el modal de edición
    $("#modalEditarStudentE").modal("show");

    // Petición AJAX para obtener los datos del estudiante por su ID
    $.ajax({
        url: "estudiante/" + id,
        type: "GET",
        dataType: "json",
        success: function (data) {
            // Llenar los campos del formulario con los datos recibidos
            console.log(data.motherSurname);
            $("#idE").val(data.id);
            $("#dniEstudianteE").val(data.documentNumber);
            $("#nombreEstudianteE").val(data.names);
            $("#fatherSurnameE").val(data.fatherSurname);
            $("#motherSurnameE").val(data.motherSurname);
            $("#businessNameE").val(data.businessName);
            $("#levellE").val(data.level);
            $("#gradooE").val(data.grade);
            $("#seccionE").val(data.section);
            $("#nombreApoderadoE").val(data.representativeNames);
            $("#dniApoderadoE").val(data.representativeDni);
            $("#telefonoE").val(data.telephone);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error al cargar datos del estudiante:", errorThrown);
        },
    });
}

$(document).on("click", "#cerrarModalE", function () {
    $("#modalEditarStudentE").modal("hide");
});
