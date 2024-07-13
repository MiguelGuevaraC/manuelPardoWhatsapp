//DATATABLE

var columns = [
    {
        data: "id",
        render: function (data, type, row, meta) {
            return '<input type="checkbox" checked="true" class="checkCominments" style="width: 20px; height: 20px;" value="' + data + '">';
        },
        orderable: false,
    },
    
    {
        data: "cuotaNumber",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },

    {
        data: "id",
        render: function (data, type, row, meta) {
            if (row.student.typeofDocument === "DNI") {
                return `${row.student.documentNumber} | ${row.student.names} ${row.student.fatherSurname} ${row.student.motherSurname}`;
            } else if (row.student.typeofDocument === "RUC") {
                return `${row.student.documentNumber} | ${row.student.businessName}`;
            }
        },
        orderable: false,
    },
    { data: "student.level" },

    {
        data: "paymentAmount",
        render: function (data, type, row, meta) {
            return row.student.grade + " " + row.student.section;
        },
        orderable: false,
    },

    {
        data: "paymentAmount",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
    {
        data: "expirationDate",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
    {
        data: "conceptDebt",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },
    {
        data: "status",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
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
            columns: [ 1, 2, 3, 4, 5, 6,7,8], // las columnas que se exportarán
        },
    },

    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [ 1, 2, 3, 4, 5, 6,7,8], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [ 1, 2, 3, 4, 5, 6,7,8], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [ 1, 2, 3, 4, 5, 6,7,8], // las columnas que se exportarán
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
    var toggleAllCheckbox = $('<input type="checkbox" id="toggleAll" checked="true" class="form-check-input" style="width: 20px; height: 20px;background:red">');
    toggleAllCheckbox.addClass('form-check-input');
    
    
    var headerCell = $(".filters th").eq(0);
    $(headerCell).html(toggleAllCheckbox);

    // Evento para marcar o desmarcar todos los checkboxes
    toggleAllCheckbox.on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.checkCominments').prop('checked', isChecked);
    });

    // Configuración de DataTables
    api.columns().eq(0).each(function (colIdx) {
        var column = api.column(colIdx);
        var header = $(column.header());

        // Configurar filtro para columnas específicas
        if (colIdx == 8 || colIdx == 1 || colIdx == 2 || colIdx == 3 || colIdx == 5 || colIdx == 4 || colIdx == 6 || colIdx == 7) {
            var cell = $(".filters th").eq(header.index());
            var title = header.text();
            $(cell).html('<input type="text" placeholder="Escribe aquí..." />');
            if (colIdx == 0) {
                $(cell).html('<input style="width: 30px;" type="text" placeholder="#" />');
            }

            // Evento para filtrar cuando se escriba en el input
            $("input", cell).off("keyup change").on("keyup change", function (e) {
                e.stopPropagation();
                var regexr = "({search})";
                var cursorPosition = this.selectionStart;
                column.search(
                    this.value !== "" ?
                    regexr.replace("{search}", "(((" + this.value + ")))") :
                    "",
                    this.value !== "",
                    this.value === ""
                ).draw();

                $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
            });
        } else {
            $(header).html("");
        }
    });
};



$("#tbCompromisos thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbCompromisos thead");

$(document).ready(function () {
    var table = $("#tbCompromisos").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "compromisoAll",
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
    });
});
