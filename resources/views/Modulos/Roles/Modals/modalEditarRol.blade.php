<!-- Modal EDITAR-->
<div class="modal fade" id="modalEditarRolE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR TIPO DE USUARIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroRolE">
                    @method('PUT')
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="nameE" class="form-label labelFormato">NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="nameE" id="nameE" tabindex="1">
                                    <div class="error-messageGrupoE"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalE" class="btn btn-dark m-2 btnEditar " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-success m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>