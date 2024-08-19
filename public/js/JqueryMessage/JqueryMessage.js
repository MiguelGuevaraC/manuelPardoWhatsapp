$(document).ready(function () {
    $("#btonShowEtiquetas").click(function () {
        Swal.fire({
            title: "Etiquetas",
            html: `
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Etiqueta</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{numCuotas}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Número de cuotas vencidas</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{nombreApoderado}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Nombre del apoderado</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{dniApoderado}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">DNI del apoderado</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{nombreAlumno}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Nombre del alumno</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{codigoAlumno}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Código del alumno</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{grado}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Grado del alumno</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{seccion}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Sección del alumno</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{montoPago}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Monto del pago pendiente</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{nivel}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Nivel de estudio del alumno</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left"><strong>{{meses}}</strong></td>
                            <td style="border-bottom: 1px solid #ddd; padding: 8px;text-align:left">Meses de deudas</td>
                        </tr>
                    </tbody>
                </table>
            `,
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
        formData.append('block4', $('#block4').val());
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
                        title:  'Error Validación',
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
