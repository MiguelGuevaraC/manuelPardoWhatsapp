//DATATABLE

var columns = [
    {
        data: "id",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
    {
        data: "documentNumber",
        render: function (data, type, row, meta) {
            if (row.typeofDocument === "DNI") {
                return `${row.documentNumber} | ${row.identityNumber} | ${row.names} ${row.fatherSurname} ${row.motherSurname}`;
            } else if (row.typeofDocument === "RUC") {
                return `${row.documentNumber} | ${row.businessName}`;
            }
        },
        orderable: false,
    },
    { data: "level" },
    { data: "grade" },
    { data: "section" },

    {
        data: "representativeDni",
        render: function (data, type, row, meta) {
            return `${row.representativeDni} | ${row.representativeNames}`;
        },
        orderable: false,
    },

    { data: "telephone" },

    {
        data: null,
        render: function (data, type, full, meta) {
            return `
                <a href="javascript:void(0)" onclick="editStudent(${data.id})" style="background:#ffc107; color:white;" class="btn btn-info"> 
                    <i class="fas fa-edit"></i>
                </a>
               
             `;
        },
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
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
    },

    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
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
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            if (
                colIdx == 0 ||
                colIdx == 1 ||
                colIdx == 2 ||
                colIdx == 3 ||
                colIdx == 5 ||
                colIdx == 4 ||
                colIdx == 6
            ) {
                var cell = $(".filters th").eq(
                    $(api.column(colIdx).header()).index()
                );
                var title = $(cell).text();
                $(cell).html(
                    '<input type="text" placeholder="Escribe aquí..." />'
                );
                if (colIdx == 0) {
                    $(cell).html(
                        '<input style="width: 30px;" type="text" placeholder="#" />'
                    );
                }
                $(
                    "input",
                    $(".filters th").eq($(api.column(colIdx).header()).index())
                )
                    .off("keyup change")
                    .on("keyup change", function (e) {
                        e.stopPropagation();
                        // Get the search value
                        $(this).attr("title", $(this).val());
                        var regexr = "({search})";
                        var cursorPosition = this.selectionStart;
                        api.column(colIdx)
                            .search(
                                this.value != ""
                                    ? regexr.replace(
                                          "{search}",
                                          "(((" + this.value + ")))"
                                      )
                                    : "",
                                this.value != "",
                                this.value == ""
                            )
                            .draw();
                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
            } else {
                var cell = $(".filters th").eq(
                    $(api.column(colIdx).header()).index()
                );
                $(cell).html("");
            }
        });
};

$("#tbStudents thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbStudents thead");

$(document).ready(function () {
    var maxRetries = 3; // Número máximo de reintentos
    var retryCount = 0; // Contador de reintentos
    var table = $("#tbStudents").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "estudianteAll",
            type: "GET",
            data: function (d) {
                // Aquí configuramos los filtros de búsqueda por columna
                $('#tbStudents .filters input').each(function () {
                    var name = $(this).attr('name');
                    d.columns.forEach(function (column) {
                        if (column.data === name) {
                            column.search.value = $(this).val();
                        }
                    }, this);
                });
            },
            debounce: 500 ,
            error: function (xhr, error, thrown) {
                // Manejo de errores
                console.error("Error en la solicitud AJAX:", error);

                // Intentar nuevamente si no se alcanzó el número máximo de reintentos
                if (retryCount < maxRetries) {
                    retryCount++;
                    console.log("Reintentando... (Intento " + retryCount + " de " + maxRetries + ")");
                    fetchTableData(retryCount);
                } 
            }
        },
        orderCellsTop: true,
        fixedHeader: true,
        columns: columns,
        dom: "Bfrtip",
        buttons: butomns,

        language: lenguag,
        search: search,
        initComplete: init,

        rowId: "id",
        stripeClasses: ["odd-row", "even-row"],
        scrollY: "300px",
        scrollX: true,
    });
});
