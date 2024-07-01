
$(document).ready(function () {

    $("#registroUsuario").submit(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe por el método tradicional

        var token = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: "user",
            type: "POST",
            data: {
                password: $("#pass").val(),
                username: $("#username").val(),
                typeofUser_id: $("#typeuser").val(),
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
                var table = $("#tbUsuarios").DataTable();
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

    // Obtener opciones de tipos de usuario desde la ruta
    $.ajax({
        url: "accessAll", // Reemplaza con la ruta correcta en tu aplicación
        type: "GET",
        dataType: "json",
        success: function (response) {
            // Limpiar opciones existentes
            $("#typeuser").empty();

            // Agregar opciones al select
            $("#typeuser").append(
                $("<option>", {
                    value: "",
                    text: "Selecciona un tipo de usuario",
                })
            );

            // Iterar sobre los tipos de usuario recibidos
            $.each(response.data, function (index, tipoUsuario) {
                $("#typeuser").append(
                    $("<option>", {
                        value: tipoUsuario.id,
                        text: tipoUsuario.name,
                    })
                );
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar tipos de usuario:", error);
        },
    });
});

$("#btonNuevo").click(function (e) {
    $("#registroUsuario")[0].reset();
    $("#modalNuevoUsuario").modal("show");
});

$(document).on("click", "#cerrarModalUsuario", function () {
    $("#modalNuevoUsuario").modal("hide");
});
