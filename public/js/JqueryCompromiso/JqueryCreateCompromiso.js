// $(document).ready(function () {
//     $("#registroCompromiso").submit(function (event) {
//         event.preventDefault(); // Evita que el formulario se envíe por el método tradicional

//         var token = $('meta[name="csrf-token"]').attr("content");
//         var name = $("#name").val();

//         $.ajax({
//             url: "compromiso",
//             type: "POST",
//             data: {
//                 name: name,
//                 _token: token,
//             },
//             success: function (data) {
//                 console.log("Respuesta del servidor:", data);
//                 $.niftyNoty({
//                     type: "purple",
//                     icon: "fa fa-check",
//                     message: "Registro exitoso",
//                     container: "floating",
//                     timer: 4000,
//                 });
//                 var table = $("#tbRoles").DataTable();
//                 table.row
//                     .add({
//                         id: data.id,
//                         name: name,
//                     })
//                     .draw(false);
//                 $("#cerrarModal").click();
//             },
//             error: function (jqXHR, textStatus, errorThrown) {
//                 console.error("Error al registrar:", errorThrown);
//                 $.niftyNoty({
//                     type: "danger",
//                     icon: "fa fa-times",
//                     message: "Error al registrar: " + textStatus,
//                     container: "floating",
//                     timer: 4000,
//                 });
//             },
//         });
//     });
// });

$(document).ready(function () {
    $("#registroCompromiso").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        // Leer el archivo Excel
        let file = $("#excelFile")[0].files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let data = new Uint8Array(e.target.result);
                let workbook = XLSX.read(data, { type: "array" });

                // Suponiendo que los datos están en la primera hoja
                let firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                let excelData = XLSX.utils.sheet_to_json(firstSheet, {
                    header: 1,
                });

                // Guardar el archivo en el servidor
                formData.append("excelFile", file);

                // Realizar la solicitud AJAX
                $.ajax({
                    url: "importExcel", // Ajusta la ruta según tu configuración de Laravel
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        // Aquí puedes manejar la respuesta del servidor
                        $("#modalNuevoCompromiso").modal("hide");
                        $("#tbCompromisos").DataTable().ajax.reload();
                    },
                    error: function (xhr, status, error) {},
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                });
            };
            reader.readAsArrayBuffer(file);
        } else {
            alert("Por favor, selecciona un archivo Excel.");
        }
    });
});

$("#btonNuevo").click(function (e) {
    $("#registroCompromiso")[0].reset();
    $("#modalNuevoCompromiso").modal("show");
});

$(document).on("click", "#cerrarModal", function () {
    $("#modalNuevoCompromiso").modal("hide");
});
