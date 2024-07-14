$(document).ready(function () {
    $("#registroRol").submit(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe por el método tradicional

        var token = $('meta[name="csrf-token"]').attr("content");
        var name = $("#name").val();

        $.ajax({
            url: "access",
            type: "POST",
            data: {
                name: name,
                _token: token,
            },
            success: function (data) {
                console.log("Respuesta del servidor:", data);
                $.niftyNoty({
                    type: "purple",
                    icon: "fa fa-check",
                    message: "Registro exitoso",
                    container: "floating",
                    timer: 4000,
                });
                var table = $("#tbRoles").DataTable();
                table.row
                    .add({
                        id: data.id,
                        name: name,
                    })
                    .draw(false);
                $("#cerrarModal").click();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error al registrar:", errorThrown);
                $.niftyNoty({
                    type: "danger",
                    icon: "fa fa-times",
                    message: "Error al registrar: " + textStatus,
                    container: "floating",
                    timer: 4000,
                });
            },
        });
    });
});

function setAccesos(id, nombre) {
    $("#registroRol")[0].reset();
    $("#modalConfigurarAccesos").modal("show");
    $("#typeUser_id").val(id);
    $("#nombreRol").val(nombre);
    $("#nameE").val(nombre);

    $("#accesosContainer").empty();

    // Realizar la solicitud AJAX para obtener los accesos
    $.ajax({
        url: "access/" + id, // Ajustar la URL para obtener los accesos por tipo de usuario
        type: "GET",
        dataType: "json",
        success: function (response) {
            $("#accesosContainer").empty();
            response.data.forEach(function (acceso) {
                var checked = acceso.checked ? 'checked' : '';
                var checkbox = `
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="acceso_${acceso.id}" name="accesses[]" value="${acceso.id}" ${checked}>
                            <label class="custom-control-label" for="acceso_${acceso.id}">
                                ${acceso.name} (${acceso.route})
                            </label>
                        </div>
                    </div>`;
                $("#accesosContainer").append(checkbox);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener los accesos:", errorThrown);
        },
    });
}


$(document).ready(function () {
    $("#formularioAccesos").submit(function (event) {
        event.preventDefault(); // Evitar el comportamiento por defecto del formulario

        // Obtener el token CSRF
        var token = $('meta[name="csrf-token"]').attr('content');

        // Realizar la solicitud AJAX para actualizar los accesos
        $.ajax({
            url: "access/setAccess", // Ruta donde se encuentra el método para actualizar los accesos
            type: "POST", // Método HTTP POST para enviar los datos
            data: $(this).serialize(), // Datos del formulario serializados
            headers: {
                'X-CSRF-TOKEN': token // Incluir el token CSRF en el encabezado de la solicitud
            },
            success: function (response) {
                // Cerrar el modal de configuración de accesos
                $("#modalConfigurarAccesos").modal("hide");

                // Opcional: recargar la tabla DataTables para mostrar los cambios
                $("#tbRoles").DataTable().ajax.reload();

                // Mostrar notificación de éxito
                $.niftyNoty({
                    type: "purple",
                    icon: "fa fa-check",
                    message: "Accesos actualizados correctamente",
                    container: "floating",
                    timer: 4000,
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error al actualizar accesos:", errorThrown);
                // Mostrar notificación de error
                $.niftyNoty({
                    type: "danger",
                    icon: "fa fa-times",
                    message: "Error al actualizar accesos",
                    container: "floating",
                    timer: 4000,
                });
            }
        });
    });
});


$("#btonNuevo").click(function (e) {
    $("#registroRol")[0].reset();
    $("#modalNuevoRol").modal("show");
});

$(document).on("click", "#cerrarModal", function () {
    $("#modalNuevoRol").modal("hide");
});
