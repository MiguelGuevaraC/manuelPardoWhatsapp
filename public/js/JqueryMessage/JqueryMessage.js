$(document).ready(function () {
    $("#btonShowEtiquetas").click(function () {
        Swal.fire({
            title: "Etiquetas",
            html:
                '<ul style="text-align:left">' +
                "<li><strong>{{numCuotas}}</strong>: Número de cuotas vencidas</li>" +
                "<li><strong>{{nombreApoderado}}</strong>: Nombre del apoderado</li>" +
                "<li><strong>{{dniApoderado}}</strong>: DNI del apoderado</li>" +
                "<li><strong>{{nombreAlumno}}</strong>: Nombre del alumno</li>" +
                "<li><strong>{{codigoAlumno}}</strong>: Código del alumno</li>" +
                "<li><strong>{{grado}}</strong>: Grado del alumno</li>" +
                "<li><strong>{{seccion}}</strong>: Sección del alumno</li>" +
                "<li><strong>{{montoPago}}</strong>: Monto del pago pendiente</li>" +
                "</ul>",
            icon: "info",
        });
    });

    $("#btonShowView").click(function () {
        $.ajax({
            url: "message/showExample",
            method: "GET",
            success: function (response) {
                console.log(response);
                let data = response;
                Swal.fire({
                    title: "VISTA MENSAJE",
                    html:
                        "<div style='text-align:left;'><b>" +
                        data.title +
                        "</b></div><br>" +
                        "<div style='text-align:left'>" +
                        "<div>" +
                        data.block1 +
                        "</div><br>" +
                        "<div>" +
                        data.block2 +
                        "</div><br>" +
                        "<div>" +
                        data.block3 +
                        "</div></div>",
                });
            },
            error: function () {
                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema al obtener los datos.",
                    icon: "error",
                });
            },
        });
    });

    $('#btonSaveMessage').on('click', function() {
        // Crear un objeto FormData
        var formData = new FormData();
        
        // Agregar los valores de los campos del formulario
        formData.append('title', $('#title').val());
        formData.append('block1', $('#block1').val());
        formData.append('block2', $('#block2').val());
        formData.append('block3', $('#block3').val());
        formData.append('_token', $('input[name="_token"]').val());
    
        // Enviar los datos mediante AJAX
        $.ajax({
            url: 'message', // Ruta del controlador
            type: 'POST', // Usar PUT en lugar de POST
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer el contentType
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registro actualizado exitosamente',
                    text: 'El mensaje se ha guardado correctamente.',
                    confirmButtonText: 'Aceptar'
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Manejo de errores de validación
                    var errors = xhr.responseJSON.error;
                 
                    Swal.fire({
                        icon: 'error',
                        title:  'Error Etiqueta',
                        text:  errors,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    // Otros errores
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al guardar el mensaje.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        });
    });
    
});
