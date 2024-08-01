// $(document).ready(function () {
//     $("#registroMensajeria").submit(function (event) {
//         event.preventDefault(); // Evita que el formulario se envíe por el método tradicional

//         var token = $('meta[name="csrf-token"]').attr("content");
//         var name = $("#name").val();

//         $.ajax({
//             url: "Mensajeria",
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
    $("#registroMensajeria").on("submit", function (e) {
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
                        $("#modalNuevoMensajeria").modal("hide");
                        $("#tbMensajerias").DataTable().ajax.reload();
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
    $("#registroMensajeria")[0].reset();
    $("#modalNuevoMensajeria").modal("show");
});

$(document).on("click", "#cerrarModal", function () {
    $("#modalNuevoMensajeria").modal("hide");
});

$(document).ready(function () {
    var carritoColumns = [
        { data: "cuotaNumber" },
        {
            data: "student.names",
            render: function (data, type, row, meta) {
                if (row.student.typeofDocument === "RUC") {
                    return `${row.student.documentNumber} | ${row.student.businessName}`;
                }else{
                        return `${row.student.documentNumber} | ${row.student.identityNumber} | ${row.student.names} ${row.student.fatherSurname} ${row.student.motherSurname}`;
                }
            },
        },
        { data: "student.level" },
        {
            data: "student.grade",
            render: function (data, type, row, meta) {
                return row.student.grade + " " + row.student.section;
            },
        },
        { data: "paymentAmount" },
        { data: "expirationDate" },
        { data: "conceptDebt" },
        { data: "status" },
    ];

    var carritoLanguage = {
        lengthMenu: "Mostrar _MENU_ Registros por página",
        zeroRecords: "No hay Registros",
        info: "Mostrando la página _PAGE_ de _PAGES_",
        infoEmpty: "",
        infoFiltered: "Filtrado de _MAX_ entradas en total",
        search: "Buscar:",
        paginate: {
            next: "Siguiente",
            previous: "Anterior",
        },
    };

    var carritoButtons = [
        {
            extend: "copy",
            text: 'COPY <i class="fa-solid fa-copy"></i>',
            className: "btn-secondary copy",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8], // Las columnas que se exportarán
            },
        },
        {
            extend: "excel",
            text: 'EXCEL <i class="fas fa-file-excel"></i>',
            className: "excel btn-success",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8], // Las columnas que se exportarán
            },
        },
        {
            extend: "pdf",
            text: 'PDF <i class="far fa-file-pdf"></i>',
            className: "btn-danger pdf",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8], // Las columnas que se exportarán
            },
        },
        {
            extend: "print",
            text: 'PRINT <i class="fa-solid fa-print"></i>',
            className: "btn-dark print",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8], // Las columnas que se exportarán
            },
        },
    ];

    var carritoSearch = {
        regex: true,
        caseInsensitive: true,
        type: "html-case-insensitive",
    };

    var carritoInit = function () {
        var api = this.api();

        // Configurar filtros de búsqueda para las columnas específicas
        api.columns()
            .eq(0)
            .each(function (colIdx) {
                var column = api.column(colIdx);
                var header = $(column.header());

                if (
                    colIdx == 0 ||
                    colIdx == 1 ||
                    colIdx == 2 ||
                    colIdx == 3 ||
                    colIdx == 5 ||
                    colIdx == 4 ||
                    colIdx == 6 ||
                    colIdx == 7
                ) {
                    var cell = $(".filters1 th").eq(header.index());
                    var title = header.text();
                    $(cell).html(
                        '<input type="text" placeholder="Escribe aquí..." />'
                    );

                    // Evento para filtrar cuando se escriba en el input
                    $("input", cell)
                        .off("keyup change")
                        .on("keyup change", function (e) {
                            e.stopPropagation();
                            var regexr = "({search})";
                            var cursorPosition = this.selectionStart;
                            column
                                .search(
                                    this.value !== ""
                                        ? regexr.replace(
                                              "{search}",
                                              "(((" + this.value + ")))"
                                          )
                                        : "",
                                    this.value !== "",
                                    this.value === ""
                                )
                                .draw();

                            $(this)
                                .focus()[0]
                                .setSelectionRange(
                                    cursorPosition,
                                    cursorPosition
                                );
                        });
                } else {
                    $(header).html("");
                }
            });
    };

    // Clonar el encabezado para agregar filtros
    $("#tbCarrito thead tr")
        .clone(true)
        .addClass("filters1")
        .appendTo("#tbCarrito thead");

    // Configurar DataTable para la tabla de carrito
    var carritoTable = $("#tbCarrito").DataTable({
        columns: carritoColumns,
        dom: "Bfrtip",
        buttons: carritoButtons,
        language: carritoLanguage,
        search: carritoSearch,
        initComplete: carritoInit,
        orderCellsTop: true,
        fixedHeader: true,
        stripeClasses: ["odd-row", "even-row"],
        scrollY: "300px",
        scrollX: true,
    });
});

$("#btonCarrito").click(function () {
    // Abrir el modal del carrito al hacer clic en el botón
    $("#modalCarrito").modal("show");

    // Limpiar la tabla de carrito antes de agregar nuevos elementos
    $("#tbCarrito").DataTable().clear().draw();

    // Iterar sobre los checkboxes marcados en la tabla de Mensajerias
    $("#tbMensajerias input.checkCominments:checked").each(function () {
        // Obtener la fila padre del checkbox marcado
        var row = $(this).closest("tr");

        // Obtener los datos de la fila de Mensajerias
        var rowData = $("#tbMensajerias").DataTable().row(row).data();

        // Agregar los datos a la tabla de carrito
        $("#tbCarrito").DataTable().row.add(rowData).draw();
    });
});

$(document).ready(function () {
    $("#enviarWhatsapp").click(function () {
        // Verificar si la tabla tiene registros
        if ($("#tbCarrito tbody tr").length > 0) {
            swal({
                title: "¿Confirmar Envio por Whatsapp?",
                text: "Se realizará el envio de Mensajería",
                icon: "info",
                buttons: true,
                dangerMode: true,
            }).then((willSend) => {
                if (willSend) {
                    // Aquí puedes poner la lógica para enviar los Mensajerias
                    // Ejemplo: llamar a una función para enviar los datos por WhatsApp
                    enviarMensajerias();
                    $("#modalCarrito").modal("hide");
                    swal(
                        "¡Enviado!",
                        "Los Mensajerias han sido enviados.",
                        "success"
                    );
                    // Desmarcar todos los checkboxes
                    $(".checkCominments").prop("checked", false);
                    $("#toggleAll").prop("checked", false);
                } else {
                    swal(
                        "Envío cancelado",
                        "Los Mensajerias no fueron enviados.",
                        "error"
                    );
                }
            });
        } else {
            swal(
                "No hay Mensajerias",
                "No se encontraron Mensajerias en la lista.",
                "warning"
            );
        }
    });
});

function enviarMensajerias() {}
