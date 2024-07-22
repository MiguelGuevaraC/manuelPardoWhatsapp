//DATATABLE

var columns = [
    {
        data: "id",
        visible: false, // Oculta esta columna
    },
    {
        data: "id",
        render: function (data, type, row, meta) {
            return (
                '<input type="checkbox" class="checkCominments" style="width: 20px; height: 20px;" value="' +
                data +
                '">'
            );
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

// Función para actualizar los IDs marcados y guardarlos en localStorage
function updateMarkedIds() {
    $("#tbCompromisos input.checkCominments:checked").each(function () {
        var rowId = $(this).val(); // Obtener el valor del checkbox, que es el ID
        markedIds.push(rowId);
    });
    localStorage.setItem("markedIds", JSON.stringify(markedIds)); // Guardar en localStorage
}

// Llamar a la función cuando se cambia el estado de los checkboxes
$("#tbCompromisos").on("change", "input.checkCominments", function () {
    // Obtener el array de IDs actualmente guardado en localStorage
    var markedIds = JSON.parse(localStorage.getItem("markedIds") || "[]");

    // Obtener el ID de la fila actual
    var rowId = $(this).val();

    // Comprobar si el checkbox está marcado o desmarcado
    if ($(this).is(":checked")) {
        // Agregar el ID al array si no está ya presente
        if (!markedIds.includes(rowId)) {
            markedIds.push(rowId);
        }
    } else {
        // Eliminar el ID del array si está presente
        markedIds = markedIds.filter(function (id) {
            return id !== rowId;
        });
    }

    // Guardar el array actualizado en localStorage
    localStorage.setItem("markedIds", JSON.stringify(markedIds));
});


var init = function () {
    var api = this.api();
    var table = api.table().node(); // Asegúrate de obtener la referencia de la tabla

    // Agregar checkbox en el encabezado de la primera columna
    var toggleAllCheckbox = $(
        '<input type="checkbox" id="toggleAll"   class="form-check-input" style="width: 20px; height: 20px;">'
    );

    var headerCell = $(".filters th").eq(0);
    $(headerCell).html(toggleAllCheckbox);

    // Evento para marcar o desmarcar todos los checkboxes
    toggleAllCheckbox.on("change", function () {
        alert("change");
        var checked = $(this).is(":checked");
        console.log("Checkbox toggleAll is", checked);

        if (checked) {
            // Obtener todos los IDs desde la API y actualizar localStorage
            $.get("compromisoAllId") // Cambia la URL a la de tu API
                .done(function (response) {
                    var allIds = response || []; // Asegúrate de que la respuesta tenga una propiedad `ids`
                    console.log("Response:", response);
                    localStorage.setItem("markedIds", JSON.stringify(allIds));
                    console.log(
                        "Updated markedIds:",
                        localStorage.getItem("markedIds")
                    );

                    // Marca los checkboxes correspondientes en la tabla
                    var table = $("#tbCompromisos").DataTable();
                    table.on("draw", function () {
                        table.rows().every(function () {
                            var row = this.node();
                            var rowId = $(row).attr("id");

                            $(row)
                                .find("input.checkCominments")
                                .prop("checked", true);
                        });
                    });
                    table.draw(); // Redibuja la tabla para aplicar los cambios
                })
                .fail(function () {
                    console.error("Error al obtener los IDs desde la API");
                });
        } else {
            // Desmarcar todos los checkboxes y limpiar localStorage
            localStorage.setItem("markedIds", JSON.stringify([]));
            var table = $("#tbCompromisos").DataTable();
            table.on("draw", function () {
                table.rows().every(function () {
                    $("input.checkCominments").prop("checked", false);
                });
            });
            table.draw();
        }
    });

    // Configuración de DataTables
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            var column = api.column(colIdx);
            var header = $(column.header());

            // Configurar filtro para columnas específicas
            if ([8, 2, 3, 5, 4, 6, 7, 9].includes(colIdx)) {
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
                        var cursorPosition = this.selectionStart;
                        column.search(this.value, true, false).draw();
                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
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
            data: function (d) {
                // Aquí configuramos los filtros de búsqueda por columna
                $("#tbCompromisos .filters input").each(function () {
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
        autoWidth: true,
        pageLength: 30,
        lengthChange: false,
    });

    // Restaurar el estado de los checkboxes desde localStorage

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
}
$(document).ready(function () {
    initialTableCompromisos();
    // localStorage.setItem("markedIds", JSON.stringify([]));
var table =  $("#tbCompromisos").DataTable()
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
        if (typeof initialCount !== 'undefined' && typeof markedIds !== 'undefined' && initialCount !== markedIds.length) {
            $("#modalCarrito").on("hidden.bs.modal", function () {
                initialTableCompromisos(); // Llama a la función para recargar la tabla
            });
        }
    }
    
});
