// Evento submit del formulario de edición
$("#registroRolE").submit(function (event) {
    event.preventDefault(); // Evitar el comportamiento por defecto del formulario

    // Obtener los datos del formulario
    var formData = {
        name: $("#nameE").val(), // Mapear nameE a name
        id: $("#idE").val(), // Mapear idE a id
        _method: "PUT", // Agregar _method con valor PUT para simular PUT en Laravel
    };

    // Obtener el token CSRF
    var token = $('meta[name="csrf-token"]').attr("content");

    // Realizar la solicitud AJAX para actualizar el tipo de usuario
    $.ajax({
        url: "access/" + formData.id, // Ruta donde se encuentra el método para actualizar el tipo de usuario
        type: "POST", // Método HTTP POST con _method: 'PUT' para simular PUT
        data: formData, // Datos del formulario con los nombres esperados en el servidor
        headers: {
            "X-CSRF-TOKEN": token, // Incluir el token CSRF en el encabezado de la solicitud
        },
        success: function (response) {
            // Cerrar el modal de edición
            $("#modalEditarRolE").modal("hide");
            $("#tbRoles").DataTable().ajax.reload();

            // Mostrar notificación de éxito
            $.niftyNoty({
                type: "purple",
                icon: "fa fa-check",
                message: "Tipo de usuario actualizado correctamente",
                container: "floating",
                timer: 4000,
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error al actualizar tipo de usuario:", errorThrown);
            // Mostrar notificación de error
            $.niftyNoty({
                type: "danger",
                icon: "fa fa-times",
                message: "Error al actualizar tipo de usuario",
                container: "floating",
                timer: 4000,
            });
        },
    });
});
