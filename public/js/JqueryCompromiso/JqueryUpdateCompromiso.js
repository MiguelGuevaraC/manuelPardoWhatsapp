// Evento submit del formulario de edición
$("#registroCompromisoE").submit(function (event) {
    event.preventDefault(); // Evitar el comportamiento por defecto del formulario

    // Obtener los datos del formulario
    var formData = {
        _method: "PUT", // Agregar _method con valor PUT para simular PUT en Laravel
        documentNumber: $("#dniCompromisoE").val(), // Obtener el valor del campo documentNumber
        names: $("#nombreCompromisoE").val(), // Obtener el valor del campo names
        fatherSurname: $("#fatherSurnameE").val(), // Obtener el valor del campo fatherSurname
        motherSurname: $("#motherSurnameE").val(), // Obtener el valor del campo motherSurname
        level: $("#levellE").val(), // Obtener el valor del campo level
        grade: $("#gradooE").val(), // Obtener el valor del campo grade
        section: $("#seccionE").val(), // Obtener el valor del campo section
        representativeDni: $("#dniApoderadoE").val(), // Obtener el valor del campo representativeDni
        representativeNames: $("#nombreApoderadoE").val(), // Obtener el valor del campo representativeNames
        telephone: $("#telefonoE").val(), // Obtener el valor del campo telephone
    };

    // Obtener el token CSRF
    var token = $('meta[name="csrf-token"]').attr("content");

    // Obtener el ID del compromiso
    var id = $("#idE").val();

    // Realizar la solicitud AJAX para actualizar el compromiso
    $.ajax({
        url: "compromiso/" + id, // Ruta donde se encuentra el método para actualizar el compromiso
        type: "POST", // Método HTTP POST con _method: 'PUT' para simular PUT
        data: formData, // Datos del formulario con los nombres esperados en el servidor
        headers: {
            "X-CSRF-TOKEN": token, // Incluir el token CSRF en el encabezado de la solicitud
        },
        success: function (response) {
            // Cerrar el modal de edición
            $("#modalEditarCompromisoE").modal("hide");

            // Recargar los datos en la tabla utilizando DataTables
            $("#tbCompromisos").DataTable().ajax.reload();

            // Mostrar notificación de éxito
            $.niftyNoty({
                type: "purple",
                icon: "fa fa-check",
                message: "Compromiso actualizado correctamente",
                container: "floating",
                timer: 4000,
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error al actualizar compromiso:", errorThrown);

            // Mostrar notificación de error
            $.niftyNoty({
                type: "danger",
                icon: "fa fa-times",
                message: "Error al actualizar compromiso",
                container: "floating",
                timer: 4000,
            });
        },
    });
});
