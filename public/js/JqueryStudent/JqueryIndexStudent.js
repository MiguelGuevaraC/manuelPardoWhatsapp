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
        data: "id",
        render: function (data, type, row, meta) {
            if (row.typeofDocument === "DNI") {
                return `${row.names} ${row.fatherSurname} ${row.motherSurname}`;
            } else if (row.typeofDocument === "RUC") {
                return row.businessName;
            }
        },
        orderable: false,
    },
    { data: "level" },
    { data: "grade" },
    { data: "section" },
    { data: "representativeDni" },
    { data: "representativeNames" },
    { data: "telephone" },

//     {
//         data: null,
//         render: function (data, type, full, meta) {
//             return `
  
//    `;
//         },
//     },
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
    [20, 50, -1],
    [20, 50, "Todos"],
];
var butomns = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2,3,4,5,6,7], // las columnas que se exportarán
        },
    },

    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2,3,4,5,6,7], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2,3,4,5,6,7], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2,3,4,5,6,7], // las columnas que se exportarán
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
                || colIdx == 2 
                || colIdx == 3
                || colIdx == 5
                || colIdx == 4
                || colIdx == 6|| colIdx == 7
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
    var table = $("#tbStudents").DataTable({
        ajax: {
            url: "estudianteAll",
            dataSrc: function (json) {
                console.log(json); // Agrega este console.log para ver la respuesta
                return json.data; // Asegúrate de retornar los datos correctos para DataTable
            },
        },
        orderCellsTop: true,
        fixedHeader: true,
        columns: columns,
        dom: "Bfrtip",
        buttons: butomns,
        lengthMenu: lengthmenu,
        language: lenguag,
        search: search,
        initComplete: init,
        
        rowId: "id",
        stripeClasses: ["odd-row", "even-row"],
        rowId: "id",
        scrollY: "300px",
    });
});
