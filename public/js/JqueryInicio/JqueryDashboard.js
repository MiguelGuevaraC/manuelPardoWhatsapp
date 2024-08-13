


$(document).ready(function () {
    var startDate, endDate;
    var mensajesEnviadosChart, analisisCostosChart;

    // Establecer las fechas por defecto al cargar la página
    function initializeDates() {
        var fechaInicio = new Date();
        fechaInicio.setDate(1);
        var fechaFin = new Date();
        var currentDate = new Date();
        var firstDayOfYear = new Date(currentDate.getFullYear(), 0, 1);
        
        startDate = fechaInicio.toISOString().split("T")[0];
        endDate = fechaFin.toISOString().split("T")[0];

        $("#fechaInicio").val(startDate);
        $("#fechaFin").val(endDate);

        // Inicializar los gráficos con fechas predeterminadas
        fetchDataAndUpdateCharts(startDate, endDate);
    }

    initializeDates();

    $("#filtrar").click(function () {
        var fechaInicioInput = $("#fechaInicio").val();
        var fechaFinInput = $("#fechaFin").val();

        if (fechaInicioInput) {
            startDate = new Date(fechaInicioInput).toISOString().split("T")[0];
        } else {
            var defaultStartDate = new Date();
            defaultStartDate.setDate(1);
            startDate = defaultStartDate.toISOString().split("T")[0];
        }

        if (fechaFinInput) {
            endDate = new Date(fechaFinInput).toISOString().split("T")[0];
        } else {
            endDate = new Date().toISOString().split("T")[0];
        }

        fetchDataAndUpdateCharts(startDate, endDate);
    });

    function fetchDataAndUpdateCharts(startDate, endDate) {
        $.ajax({
            url: "dataDashboard",
            method: "GET",
            data: {
                fechaStart: startDate,
                fechaEnd: endDate,
            },
            success: function (response) {
                updateCharts(response);
            },
        });
    }

    function updateCharts(data) {
        var labels = [];
        var mensajesData = {};
        var costosData = {};

        // Agrupar los datos por mes
        function getMonthLabel(date) {
            var monthNames = [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre",
            ];
            var month = new Date(date).getMonth();
            var year = new Date(date).getFullYear();
            return monthNames[month] + " " + year;
        }

        function addToMonthData(date, value, dataMap) {
            var monthLabel = getMonthLabel(date);
            if (!dataMap[monthLabel]) {
                dataMap[monthLabel] = 0;
            }
            dataMap[monthLabel] += value;
        }

        // Parsear las fechas y agregar a las colecciones de datos
        Object.keys(data.mensajesPorFecha).forEach((date) => {
            addToMonthData(date, data.mensajesPorFecha[date], mensajesData);
        });

        Object.keys(data.costosPorFecha).forEach((date) => {
            addToMonthData(date, data.costosPorFecha[date], costosData);
        });

        // Convertir los datos a arrays ordenados por fecha
        var sortedLabels = Object.keys(mensajesData).sort();
        var sortedMensajesData = sortedLabels.map(
            (label) => mensajesData[label]
        );
        var sortedCostosData = sortedLabels.map((label) => costosData[label]);

        // Destruir gráficos anteriores si existen
        if (mensajesEnviadosChart) {
            mensajesEnviadosChart.destroy();
        }

        if (analisisCostosChart) {
            analisisCostosChart.destroy();
        }

        // Crear gráfico de mensajes enviados
        var ctx1 = document
            .getElementById("mensajesEnviadosChart")
            .getContext("2d");
        mensajesEnviadosChart = new Chart(ctx1, {
            type: "line",
            data: {
                labels: sortedLabels,
                datasets: [
                    {
                        label: "Mensajes Enviados",
                        data: sortedMensajesData,
                        backgroundColor: "rgba(54, 162, 235, 0.6)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 2,
                        fill: true,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(200, 200, 200, 0.3)",
                        },
                    },
                    x: {
                        grid: {
                            color: "rgba(200, 200, 200, 0.3)",
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: "black",
                        },
                    },
                },
            },
        });

        // Crear gráfico de costos
        var ctx2 = document
            .getElementById("analisisCostosChart")
            .getContext("2d");
        analisisCostosChart = new Chart(ctx2, {
            type: "bar",
            data: {
                labels: sortedLabels,
                datasets: [
                    {
                        label: "Costo ($)",
                        data: sortedCostosData,
                        backgroundColor: "rgba(75, 192, 192, 0.6)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(200, 200, 200, 0.3)",
                        },
                    },
                    x: {
                        grid: {
                            color: "rgba(200, 200, 200, 0.3)",
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: "black",
                        },
                    },
                },
            },
        });

        // Actualizar valores de total de mensajes y costo total
        $("#costoUnitario").text(data.costoUnitario);
        $("#totalEnviados").text(data.totalMensajes);
        $("#costoTotal").text("S/." + data.costoTotal.toFixed(2));
    }
});
