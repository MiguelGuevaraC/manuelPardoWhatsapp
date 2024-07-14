<!-- Modal CREAR -->
<div class="modal fade" id="modalNuevoMensajeria" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoModalLabel"><strong>Cargar Data</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registroMensajeria" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label for="comment" class="form-label labelFormato">Comentario:</label>
                                <input type="text" class="form-control ajuste" value="Cargar Archivo Excel" name="comment" id="comment">
                                <div class="error-messageGrupo"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="excelFile" class="form-label labelFormato">Cargar Archivo Excel:</label>
                                <input type="file" class="form-control ajuste" name="excelFile" id="excelFile" accept=".xls,.xlsx">
                                <div class="error-messageGrupo"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark m-2 ancho btnCrear" data-dismiss="modal" tabindex="3">Cancelar</button>
                        <button type="submit" class="btn btn-success m-2 ancho btnCrear" tabindex="4">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Importar las librerías necesarias -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Añadir el soporte de Bootstrap para los modales y las librerías -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
