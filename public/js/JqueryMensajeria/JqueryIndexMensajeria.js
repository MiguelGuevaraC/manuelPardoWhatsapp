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
            if (row.student.typeofDocument === "DNI") {
                return `${row.student.documentNumber} | ${row.student.identityNumber} | ${row.student.names} ${row.student.fatherSurname} ${row.student.motherSurname}`;
            } else if (row.student.typeofDocument === "RUC") {
                return `${row.student.documentNumber} | ${row.student.businessName}`;
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
];

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
                colIdx == 8 ||
                colIdx == 9 ||
                colIdx == 10 ||
                colIdx == 11
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
                $("#tbMensajerias .filters input").each(function () {
                    var name = $(this).attr("name");
                    d.columns.forEach(function (column) {
                        if (column.data === name) {
                            column.search.value = $(this).val();
                        }
                    }, this);
                });
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
                    fetchTableData(retryCount);
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
    });
});
