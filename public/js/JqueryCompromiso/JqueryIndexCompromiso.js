//DATATABLE

var columns = [
    {
        data: "id",
        visible: false, // Oculta esta columna
    },
    {
        data: "stateSend", // Asegúrate de que este es el nombre del campo en tus datos
        render: function (data, type, row, meta) {
            // Retorna el checkbox con estado según el valor de data
            return `
                <input class="checkCompro" type="checkbox" ${
                    data === 1 ? "checked" : ""
                } ${data === 0 ? "" : ""} value="${row.id}">
            `;
            return '';
        },
        orderable: false,
    },

    // {
    //     data: "countCominments",
    //     render: function (data, type, row, meta) {
    //         return data;
    //     },
    //     orderable: false,
    // },
    {
        data: "cuotaNumber",
        render: function (data, type, row, meta) {
            return data;
        },
        orderable: false,
    },

    {
        data: "student.names",
        render: function (data, type, row, meta) {
            if (row.student.typeofDocument === "DNI") {
                return `${row.student.identityNumber} |${row.student.documentNumber} | ${row.student.names} ${row.student.fatherSurname} ${row.student.motherSurname}`;
            } else if (row.student.typeofDocument === "RUC") {
                return `${row.student.documentNumber} | ${row.student.businessName}`;
            }
        },
        orderable: false,
    },
    { data: "student.level" },

    {
        data: "student.grade",
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
        data: "student.telephone",
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
    [15, 50, -1],
    [15, 50, "Todos"],
];
var butomns = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },

    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },
];

var search = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};
var markedIds = []; // Variable global para almacenar los IDs marcados

$("#tbCompromisos").on("change", "input.checkCompro", function () {
    var checkbox = $(this);
    var id = checkbox.val(); // Obtener el ID del registro desde el valor del checkbox
    var isChecked = checkbox.is(":checked");

    $.ajax({
        url: "stateSend/" + id,
        method: "GET",
        success: function (response) {
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

var init = function (settings, json) {
    var api = this.api();
    var table = api.table().node(); // Asegúrate de obtener la referencia de la tabla

    // Agregar checkbox en el encabezado de la primera columna
    var toggleAllCheckbox = $(
        '<input type="checkbox" id="toggleAll" class="form-check-input" style="width: 20px; height: 20px;">'
    );

    var headerCell = $(".filters th").eq(0);
    $(headerCell).html(toggleAllCheckbox);

    api.columns()
        .eq(0)
        .each(function (colIdx) {
            var column = api.column(colIdx);
            var header = $(column.header());

            // Configurar filtro para columnas específicas
            if ([8, 2, 3, 5, 4, 6, 7, 9, 1].includes(colIdx)) {
                var cell = $(".filters th").eq(header.index());
                var title = header.text();

                if (colIdx == 0) {
                    $(cell).html(
                        '<input style="width: 30px;" type="text" placeholder="#" />'
                    );
                } else if (colIdx == 1) {
                    $(cell).html(
                        '<input type="checkbox" id="toggleAlll" class="form-check-input" style="width: 20px; height: 20px;">'
                    );
                } else {
                    $(cell).html(
                        '<input type="text" placeholder="Escribe aquí..." />'
                    );
                }

                // Evento para filtrar cuando se escriba en el input
                $("input", cell)
                    .off("keyup change")
                    .on("keyup change", function (e) {
                        e.stopPropagation();
                        var checkedInput = $("#toggleAlll").is(":checked");
                        if (this.type === "text") {
                            var cursorPosition = this.selectionStart;
                            column.search(this.value, true, false).draw();

                            $(this)
                                .focus()[0]
                                .setSelectionRange(
                                    cursorPosition,
                                    cursorPosition
                                );

                            // Desmarcar el checkbox del encabezado superior
                            $("#toggleAlll").prop("checked", false);
                            checkedInput = "";
                        
                      
                                api.column(1)
                                    .search("false", true, false)
                                    .draw();
                           
                        }

                        console.log("Checkbox in is checked:", checkedInput);

                        if (this.type === "checkbox") {
                         
                                console.log(
                                    "Checkbox is checked:",
                                    checkedInput
                                );
                            
                                column.search(checkedInput, true, false).draw();
                           
                        } else {
                        }
                        console.log("Checkbox in is checked:", checkedInput);
                    });
            } else {
                $(header).html("");
            }
        });
};

$("#searchBtn").click(function () {
    $(".panelBusqueda").toggle(); // Alterna la visibilidad del panel
});

$("#tbCompromisos thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbCompromisos thead");

$("#tbCompromisos .filters input").on("keyup change", function () {
    table.ajax.reload();
});

function initialTableCompromisos() {
    $("#tbCompromisos").DataTable().destroy();
    var table = $("#tbCompromisos").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "compromisoAll",
            type: "GET",
            data: function (d) {},
            debounce: 500,
        },
        drawCallback: function (settings) {
            var api = this.api();
            var json = api.ajax.json();

            console.log(json.recordsFiltered);
            $("#countCompromiso").text(json.recordsFiltered);
            $("#countCompromisoSelected").text(json.recordsSelected);

            $("#amountCompromiso").text(json.amountFiltered);
            $("#amountCompromisoSelected").text(json.amountSelected);
        },
        orderCellsTop: true,
        fixedHeader: true,
        columns: columns,
        dom: "Brtip",
        buttons: [],
        language: lenguag,
        search: search,
        initComplete: init,
        rowId: "id",
        stripeClasses: ["odd-row", "even-row"],
        scrollY: "300px",
        scrollX: true,
        autoWidth: true,
        pageLength: 50,
        lengthChange: false,
    });
}
$(document).ready(function () {
    initialTableCompromisos();
    // localStorage.setItem("markedIds", JSON.stringify([]));
    var table = $("#tbCompromisos").DataTable();
    var markedIds = JSON.parse(localStorage.getItem("markedIds") || "[]");
    table.on("draw", function () {
        table.rows().every(function () {
            var row = this.node();
            var rowId = this.id();
            console.log(markedIds.includes(rowId));

            $(row)
                .find("input.checkCominments")
                .each(function () {
                    $(this).prop("checked", markedIds.includes(rowId));
                });
        });
    });
    // Evento para manejar el cambio en los checkboxes del carrito

    function removeItemFromCarrito(id) {
        var carritoTable = $("#tbCarrito").DataTable();

        // Busca y elimina la fila basada en el ID
        carritoTable.rows().every(function () {
            var rowId = $(this.node()).attr("id"); // Obtén el id de la fila

            if (rowId === id.toString()) {
                carritoTable.row(this).remove(); // Elimina la fila de la tabla
                return false; // Termina el bucle si se encuentra la fila
            }
        });

        carritoTable.draw(); // Actualiza la vista de la tabla

        // Verifica si el carrito está vacío
        var isCarritoEmpty = carritoTable.data().count() === 0;

        if (isCarritoEmpty) {
            $("#modalCarrito").modal("hide");
            Swal.fire({
                icon: "warning",
                title: "Carrito vacío",
                text: "El carrito está vacío. Debe agregar ítems.",
                confirmButtonText: "Aceptar",
            });
        }

        // Recarga la tabla de compromisos solo si hubo un cambio en el carrito
        if (
            typeof initialCount !== "undefined" &&
            typeof markedIds !== "undefined" &&
            initialCount !== markedIds.length
        ) {
            $("#modalCarrito").on("hidden.bs.modal", function () {
                initialTableCompromisos(); // Llama a la función para recargar la tabla
            });
        }
    }
});
