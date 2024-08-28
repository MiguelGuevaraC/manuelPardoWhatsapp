//DATATABLE

var columns = [
    { data: "conminmnet.cuotaNumber" },
    // {
    //     data: "user.person.names",
    //     render: function (data, type, row, meta) {
    //         if (row.user.person.typeofDocument === "DNI") {
    //             return `${row.user.person.documentNumber} | ${row.user.person.names} ${row.user.person.fatherSurname} ${row.user.person.motherSurname}`;
    //         } else if (row.user.person.typeofDocument === "RUC") {
    //             return `${row.user.person.documentNumber} | ${row.user.person.businessName}`;
    //         }
    //     },
    //     orderable: false,
    // },
    {
        data: "student.names",
        render: function (data, type, row, meta) {
            if (row.student.typeofDocument === "RUC") {
                return `${row.student.documentNumber} | ${row.student.businessName}`;
            } else {
                return `${row.student.documentNumber} | ${row.student.identityNumber} | ${row.student.names} ${row.student.fatherSurname} ${row.student.motherSurname}`;
            }
        },
        orderable: true,
    },
    {
        data: "student.representativeDni",
        render: function (data, type, row, meta) {
            return `${row.student.representativeDni} | ${row.student.representativeNames}`;
        },
        orderable: false,
    },

    {
        data: "student.level",
        render: function (data, type, row, meta) {
            return `${row.student.level} ${row.student.grade} ${row.student.section}`;
        },
        orderable: false,
    },
    {
        data: "student.telephone",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },

    {
        data: "conminmnet.conceptDebt",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },

    { data: "conminmnet.paymentAmount" },

    {
        data: "created_at",
        render: function (data, type, row, meta) {
            if (!data) return "";

            const date = new Date(data);
            const day = ("0" + date.getDate()).slice(-2);
            const month = ("0" + (date.getMonth() + 1)).slice(-2);
            const year = date.getFullYear();
            const hours = ("0" + date.getHours()).slice(-2);
            const minutes = ("0" + date.getMinutes()).slice(-2);
            const seconds = ("0" + date.getSeconds()).slice(-2);

            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        },
        orderable: true,
    },
    {
        data: "status",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
    {
        data: "description",
        render: function (data, type, row) {
            return (
                '  <a style="background:green; color:white;" class="view-description btn btn-info" data-description="' +
                data +
                '"><i class="fa-brands fa-whatsapp"></i> </a>'
            );
        },
    },
];

$(document).on("click", ".view-description", function () {
    var description = $(this).data("description");

    Swal.fire({
        title: "VISTA MENSAJE",
        html:
            "<div style='text-align:left'><b>Descripción:</b> " +
            description +
            "</div>",
    });
});

var lenguag = {
    lengthMenu: "Mostrar _MENU_ Registros por paginas",
    zeroRecords: "No hay Registros",
    info: "Mostrando la pagina _PAGE_ de _PAGES_",
    infoEmpty: "",
    infoFiltered: "Filtrado de _MAX_ entradas en total",
    search: "Buscar:",
    paginate: {
        next: "Siguiente",
        previous: "Anterior",
    },
};

var lengthmenu = [
    [5, 50, -1],
    [5, 50, "Todos"],
];
var butomns = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },

    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
];

var search = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};
var init = function () {
    var api = this.api();

    // Agregar checkbox en el encabezado de la primera columna
    var toggleAllCheckbox = $(
        '<input type="checkbox" id="toggleAll" checked="true" class="form-check-input" style="width: 20px; height: 20px;background:red">'
    );
    toggleAllCheckbox.addClass("form-check-input");

    var headerCell = $(".filters th").eq(0);
    $(headerCell).html(toggleAllCheckbox);

    // Evento para marcar o desmarcar todos los checkboxes
    toggleAllCheckbox.on("change", function () {
        var isChecked = $(this).prop("checked");
        $(".checkCominments").prop("checked", isChecked);
    });

    // Configuración de DataTables
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            var column = api.column(colIdx);
            var header = $(column.header());

            // Configurar filtro para columnas específicas
            if (
                colIdx == 0 ||
                colIdx == 1 ||
                colIdx == 2 ||
                colIdx == 3 ||
                colIdx == 5 ||
                colIdx == 4 ||
                colIdx == 6 ||
                colIdx == 7 ||
                colIdx == 8
            ) {
                var cell = $(".filters th").eq(header.index());
                var title = header.text();
                $(cell).html(
                    '<input type="text" placeholder="Escribe aquí..." />'
                );
                if (colIdx == 0) {
                    $(cell).html(
                        '<input style="width: 30px;" type="text" placeholder="#" />'
                    );
                }

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
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
            } else {
                $(header).html("");
            }
        });
};

$("#tbMensajerias thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbMensajerias thead");

$("#tbMensajerias .filters input").on("keyup change", function () {
    table.ajax.reload();
});

$(document).ready(function () {
    $(document).ready(function () {
        // Get today's date and yesterday's date
        let today = new Date();
        let yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 7);

        // Format dates to YYYY-MM-DD
        function formatDate(date) {
            let year = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, "0"); // Months are zero-based
            let day = String(date.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        }

        // Set the default values of the date inputs
        $("#startDate").val(formatDate(yesterday));
        $("#endDate").val(formatDate(today));

        var maxRetries = 3; // Número máximo de reintentos
        var retryCount = 0; // Contador de reintentos

        var table = $("#tbMensajerias").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "mensajeriaAll",
                type: "GET",
                data: function (d) {
                    // Aquí configuramos los filtros de búsqueda por columna
                    d.startDate = $("#startDate").val();
                    d.endDate = $("#endDate").val();
                    return d;
                },
                debounce: 500,
                error: function (xhr, error, thrown) {
                    // Manejo de errores
                    console.error("Error en la solicitud AJAX:", error);

                    // Intentar nuevamente si no se alcanzó el número máximo de reintentos
                    if (retryCount < maxRetries) {
                        retryCount++;
                        console.log(
                            "Reintentando... (Intento " +
                                retryCount +
                                " de " +
                                maxRetries +
                                ")"
                        );
                        table.ajax.reload();
                    } else {
                        alert(
                            "No se pudo recuperar los datos después de varios intentos. Por favor, inténtelo de nuevo más tarde."
                        );
                    }
                },
            },
            orderCellsTop: true,
            fixedHeader: true,
            columns: columns,
            dom: "Bfrtip",
            buttons: [],

            language: lenguag,
            search: search,
            initComplete: init,
            rowId: "id",
            stripeClasses: ["odd-row", "even-row"],
            scrollY: "300px",
            scrollX: true, // Habilitar desplazamiento horizontal si es necesario
            rowCallback: function (row, data, index) {
                if (data.status === "Envio Exitoso") {
                    $(row).css("background-color", "#d4edda"); // Verde claro
                } else if (data.status === "Envio Fallido") {
                    $(row).css("background-color", "#f8d7da"); // Rojo claro
                }
            },
        });

        // Handle form submission
        $("#filterForm").on("submit", function (event) {
            event.preventDefault(); // Prevent the default form submission
            table.ajax.reload(); // Reload table data with new filters
        });

        // Handle PDF export
        $("#savePdf").on("click", function () {
            $.ajax({
                url: "pdfExport",
                type: "GET",
                data: {
                    startDate: $("#startDate").val(),
                    endDate: $("#endDate").val(),
                },
                xhrFields: {
                    responseType: "blob",
                },
                success: function (response, status, xhr) {
                    // Obtener la fecha actual en formato YYYY-MM-DD
                    const currentDate = new Date().toISOString().split("T")[0];

                    // Obtener las fechas de los parámetros
                    const startDate = $("#startDate").val();
                    const endDate = $("#endDate").val();

                    // Formatear el nombre del archivo
                    let filename = `Reporte_${currentDate}_${startDate}_a_${endDate}.pdf`;

                    // Obtener el nombre del archivo desde la respuesta del servidor si está disponible
                    const disposition = xhr.getResponseHeader(
                        "Content-Disposition"
                    );
                    if (
                        disposition &&
                        disposition.indexOf("attachment") !== -1
                    ) {
                        const matches = /"([^"]*)"/.exec(disposition);
                        if (matches != null && matches[1])
                            filename = matches[1];
                    }

                    // Crear un enlace para la descarga
                    const link = document.createElement("a");
                    const url = window.URL.createObjectURL(response);
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                },
            });
        });

        // Handle Excel export
        $("#saveExcel").on("click", function () {
            $.ajax({
                url: "excelExport",
                type: "GET",
                data: {
                    startDate: $("#startDate").val(),
                    endDate: $("#endDate").val(),
                },
                xhrFields: {
                    responseType: "blob",
                },
                success: function (response, status, xhr) {
                    // Obtener la fecha actual en formato YYYY-MM-DD
                    const currentDate = new Date().toISOString().split("T")[0];

                    // Obtener las fechas de los parámetros
                    const startDate = $("#startDate").val();
                    const endDate = $("#endDate").val();

                    // Formatear el nombre del archivo
                    let filename = `Reporte_${currentDate}_${startDate}_a_${endDate}.xlsx`;

                    // Obtener el nombre del archivo desde la respuesta del servidor si está disponible
                    const disposition = xhr.getResponseHeader(
                        "Content-Disposition"
                    );
                    if (
                        disposition &&
                        disposition.indexOf("attachment") !== -1
                    ) {
                        const matches = /"([^"]*)"/.exec(disposition);
                        if (matches != null && matches[1])
                            filename = matches[1];
                    }

                    // Crear un enlace para la descarga
                    const link = document.createElement("a");
                    const url = window.URL.createObjectURL(response);
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                },
            });
        });
    });
});
