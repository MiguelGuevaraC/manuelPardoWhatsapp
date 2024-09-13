
<!-- Modal -->
<div class="modal fade" id="modalCarrito" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align: center; font-size: 24px; color: ##0075ff;" id="nuevoModalLabel">
                    <strong>  
                        <span id="nroCompromisos" style="font-size: 28px; color: ##0075ff;">0</span> 
                        COMPROMISOS MARCADOS PARA ENVÍO
                    </strong>
                </h5>
                
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <style>

            </style>
            <div class="modal-body">

                
                <!-- Contenido del modal -->
                <table id="tbCarrito" class="table table-striped shadow-lg mt-4" style="width:100%">
                    
                    <thead>
                        <tr class="custom-header-bg">
                            <th scope="col">Accion</th>
                            <th scope="col">N° Cuota</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Level</th>
                            <th scope="col">Grado - Sección</th>
                            <th scope="col">Monto Pago</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Concepto</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí van los datos de la tabla, puedes añadir dinámicamente con JavaScript si es necesario -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" id="enviarWhatsapp" class="btn btn-success">ENVIAR</button>
            </div>
        </div>
    </div>
</div>
