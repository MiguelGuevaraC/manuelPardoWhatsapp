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
                $("#modalNuevoCompromiso").modal("hide");

                // Mostrar alerta de espera
                Swal.fire({
                    title: "Por favor espera...",
                    text: "Estamos procesando tu solicitud.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        // Ajustar el z-index para que SweetAlert esté por encima del modal
                        $(".swal2-container").css("z-index", "2000");
                    },
                });

                // Realizar la solicitud AJAX
                $.ajax({
                    url: "importExcelCominments", // Ajusta la ruta según tu configuración de Laravel
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        // Cerrar la alerta de SweetAlert
                        Swal.close();
                        // Aquí puedes manejar la respuesta del servidor
                        $("#tbCompromisos").DataTable().ajax.reload();
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Cerrar la alerta de SweetAlert
                        Swal.close();
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Hubo un problema al procesar la solicitud.",
                        });
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                });
            };
            reader.readAsArrayBuffer(file);
        } else {
            Swal.fire({
                icon: "warning",
                title: "Archivo no seleccionado",
                text: "Por favor, selecciona un archivo Excel.",
            });
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

$(document).ready(function () {
    // Evento click del botón del carrito
    $("#btonCarrito").click(function () {
        $("#modalCarrito").modal("show");
        initialCarritoTable();
    });

    // Inicializar DataTable para la tabla de carrito

    // Clonar el encabezado para agregar filtros
});

$("#tbCarrito thead tr")
    .clone(true)
    .addClass("filters1")
    .appendTo("#tbCarrito thead");

function initialCarritoTable() {
    $("#tbCarrito").DataTable().destroy();

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
                columns: [1, 2, 3, 4, 5, 6, 7], // Las columnas que se exportarán
            },
        },
        {
            extend: "excel",
            text: 'EXCEL <i class="fas fa-file-excel"></i>',
            className: "excel btn-success",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7], // Las columnas que se exportarán
            },
        },
        {
            extend: "pdf",
            text: 'PDF <i class="far fa-file-pdf"></i>',
            className: "btn-danger pdf",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7], // Las columnas que se exportarán
            },
        },
        {
            extend: "print",
            text: 'PRINT <i class="fa-solid fa-print"></i>',
            className: "btn-dark print",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7], // Las columnas que se exportarán
            },
        },
    ];

    var carritoSearch = {
        regex: true,
        caseInsensitive: true,
        type: "html-case-insensitive",
    };
    var carritoColumns = [
        {
            data: "stateSend", // Asegúrate de que este es el nombre correcto del campo
            render: function (data, type, row, meta) {
                // Comprobar si el campo stateSend es 1 (true)
                var isChecked = data === 1 ? 'checked="checked"' : "";
                return (
                    '<input type="checkbox" ' +
                    isChecked +
                    ' class="checkCominmentsCarrito" style="width: 20px; height: 20px;" value="' +
                    row.id +
                    '">'
                );
            },
            orderable: false,
        },
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
        { data: "student.telephone" },
        { data: "conceptDebt" },
    ];

    var carritoInit = function () {
        var api = this.api();

        api.columns()
            .eq(0)
            .each(function (colIdx) {
                var column = api.column(colIdx);
                var header = $(column.header());

                if (
                    colIdx == 1 ||
                    colIdx == 2 ||
                    colIdx == 3 ||
                    colIdx == 7 ||
                    colIdx == 4 ||
                    colIdx == 5 ||
                    colIdx == 6
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

    var table = $("#tbCarrito").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "actualizarCarrito",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: function (d) {


                // Aquí configuramos los filtros de búsqueda por columna
                $("#tbCarrito .filters1 input").each(function () {
                    var name = $(this).attr("name");
                    d.columns.forEach(function (column) {
                        if (column.data === name) {
                            column.search.value = $(this).val();
                        }
                    }, this);
                });

            },
            debounce: 500,
        },
        orderCellsTop: true,
        fixedHeader: true,
        columns: carritoColumns,
        dom: "rtip",
        buttons: carritoButtons,
        language: carritoLanguage,
        search: carritoSearch,
        initComplete: carritoInit,
        rowId: "id",
        stripeClasses: ["odd-row", "even-row"],
        scrollY: "300px",
        scrollX: true,
        autoWidth: true,
        pageLength: 50,
        lengthChange: false,
    });
}

$("#tbCarrito").on("change", "input.checkCominmentsCarrito", function () {
    var checkbox = $(this);
    var id = checkbox.val(); // Obtener el ID del registro desde el valor del checkbox
    var isChecked = checkbox.is(":checked");

    $.ajax({
        url: "stateSend/" + id,
        method: "GET",
        success: function (response) {
            $("#tbCarrito").DataTable().ajax.reload();
            var table = $("#tbCompromisos").DataTable();
            table.column(1)
                 .search(null, true, false)
                 .draw(); 
        },
        error: function (xhr) {
            // Ocultar el modal de espera y mostrar mensaje de error
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error al actualizar el estado",
            });
        },
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
                    // Aquí puedes poner la lógica para enviar los compromisos
                    // Ejemplo: llamar a una función para enviar los datos por WhatsApp
                    enviarCompromisos();
                    $("#modalCarrito").modal("hide");
                    swal(
                        "¡Enviado!",
                        "Los compromisos han sido enviados.",
                        "success"
                    );
                    // Desmarcar todos los checkboxes
                    $(".checkCominments").prop("checked", false);
                    $("#toggleAll").prop("checked", false);
                } else {
                    swal(
                        "Envío cancelado",
                        "Los compromisos no fueron enviados.",
                        "error"
                    );
                }
            });
        } else {
            swal(
                "No hay compromisos",
                "No se encontraron compromisos en la lista.",
                "warning"
            );
        }
    });
});
$(document).ready(function () {
    // Evento para marcar todos los ítems
    // $("#toggleAllInput").on("change", function () {
    //     var isChecked = $(this).is(":checked");
    //     var markedIds = (localStorage.getItem("markedIds"));
    //     // Marca o desmarca todos los checkboxes en el DataTable
    //     var table = $("#tbCompromisos").DataTable();
    //     table.rows().every(function () {
    //         var data = this.data();
    //         var rowId = data.id;
    //         var checkbox = $(this.node()).find("input.checkCominments");
    //         // Si el checkbox está marcado o desmarcado, actualiza el localStorage
    //         if (isChecked) {
    //             if (!markedIds.includes(rowId)) {
    //                 markedIds.push(rowId);
    //             }
    //             checkbox.prop("checked", true);
    //         } else {
    //             markedIds = markedIds.filter(function (itemId) {
    //                 return itemId !== rowId;
    //             });
    //             checkbox.prop("checked", false);
    //         }
    //     });
    //     // Guarda los IDs actualizados en el localStorage
    //     localStorage.setItem("markedIds", (markedIds));
    //     // Opcional: Actualiza la tabla de compromisos si es necesario
    //     updateCompromisosTable();
    // });
});

function enviarCompromisos() {
    var arracompormisos = [];
    var arracompormisos = localStorage.getItem("markedIds");

    // Hacer la solicitud AJAX
    $.ajax({
        url: "mensajeria",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            arrayCompromisos: [],
        },
        success: function (response) {
            console.log('Enviando mensajes');
            console.log(response);

            // Aquí puedes hacer algo adicional después de enviar los mensajes, si es necesario
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        },
    });
}
