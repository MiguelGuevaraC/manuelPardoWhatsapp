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
        data: "number",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
   
    {
        data: "type",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
    {
        data: "comment",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
    {
        data: "created_at",
        render: function (data, type, row, meta) {
            if (type === 'display' || type === 'filter') {
                var date = new Date(data);
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            }
            return data;
        },
    },

    {
        data: "routeExcel",
        render: function (data, type, row, meta) {
            var downloadUrl =
                window.location.origin + "/manuelPardoWhatsapp/public" + data;

            return (
                '<a  href="' +
                downloadUrl +
                '" class="btn btn-primary">Descargar Excel</a>'
            );
        },
        orderable: false,
        searchable: false,
        width: "10%",
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
    [25, 50, -1],
    [25, 50, "Todos"],
];
var butomns = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2,3,4], // las columnas que se exportarán
        },
    },

    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2,3,4], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2,3,4], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2,3,4], // las columnas que se exportarán
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
            if (colIdx == 0 || colIdx == 1
                || colIdx == 2|| colIdx == 3|| colIdx == 4
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

$("#tbMigrations thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbMigrations thead");

    $(document).ready(function () {
        var table = $("#tbMigrations").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "migracionAll",
                type: "GET",
                dataSrc: function (json) {
                    console.log(json); 
                    return json.data;
                },
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
    
