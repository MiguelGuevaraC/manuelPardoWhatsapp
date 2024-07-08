<!-- Modal CREAR-->
<div class="modal fade" id="modalNuevoStudent" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabel">CARGAR DATA</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroStudent" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="comment" class="form-label labelFormato">COMENTARIO:</label>
                                    <input type="text" class="form-control ajuste" value='Cargar Archivo Excel'
                                        name="comment" id="comment">
                                    <div class="error-messageGrupo"></div>
                                </div>
                                <div class="mb-4">
                                    <label for="excelFile" class="form-label labelFormato">CARGAR ARCHIVO EXCEL:</label>
                                    <input type="file" class="form-control ajuste" name="excelFile" id="excelFile"
                                        accept=".xls,.xlsx">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="botonesModal">
                        <a id="cerrarModal" class="btn btn-dark m-2 ancho btnCrear" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-success m-2 ancho btnCrear"
                            tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Importar las librerías necesarias -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
