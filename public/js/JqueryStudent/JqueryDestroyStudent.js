function destroyStudent(id) {
    // Mostrar SweetAlert para confirmar la eliminación
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminarlo",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Usuario confirmó la eliminación, proceder con la solicitud AJAX
            var token = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: "estudiante/" + id,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": token,
                },
                success: function (data) {
                    $.niftyNoty({
                        type: "purple",
                        icon: "fa fa-check",
                        message: "Eliminación Exitosa",
                        container: "floating",
                        timer: 4000,
                    });

                    // Eliminar la fila correspondiente de la tabla DataTables
                    var table = $("#tbStudents").DataTable();
                    var row = table.row("#" + id);

                    if (row.length > 0) {
                        row.remove().draw(false);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $.niftyNoty({
                        type: "danger",
                        icon: "fa fa-times",
                        message: "Error al Eliminar: " + textStatus + " - " + errorThrown,
                        container: "floating",
                        timer: 4000,
                    });
                },
            });
        }
    });
}