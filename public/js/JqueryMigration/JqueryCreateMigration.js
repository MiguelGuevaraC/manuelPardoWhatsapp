$(document).ready(function () {
    $("#registroMigration").submit(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe por el método tradicional

        var token = $('meta[name="csrf-token"]').attr("content");
        var name = $("#name").val();

        $.ajax({
            url: "migracion",
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



$("#btonNuevo").click(function (e) {
    $("#registroMigration")[0].reset();
    $("#modalNuevoMigration").modal("show");
});

$(document).on("click", "#cerrarModal", function () {
    $("#modalNuevoMigration").modal("hide");
});
