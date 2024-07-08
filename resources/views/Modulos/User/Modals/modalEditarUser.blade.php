<!-- Modal CREAR-->
<div class="modal fade" id="modalNuevoUsuarioE" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabel">EDITAR USUARIO</strong></h5>
            </div>
            <div class="modal-body">

                <form id="registroUsuarioE" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="cajaUsuario row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="usernameE" class="form-label labelFormato"><strong>NOMBRE
                                                USUARIO:</strong></label>
                                        <input type="text" name="usernameE" id="usernameE"
                                            class="form-control ajuste" placeholder="Escribe aqui..." required>
                                        <div class="error-message"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="passE"
                                            class="form-label labelFormato"><strong>CONTRASEÑA:</strong></label>
                                        <input type="passwordE" name="passE" id="passE"
                                            class="form-control ajuste" placeholder="Escribe aqui..." required>
                                        <div class="error-message"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="typeuserE" class="form-label labelFormato"><strong>Tipo
                                                Usuario:</strong></label>
                                        <select name="typeuserE" id="typeuserE" class="form-control ajuste" required>
                                            <option value="">Selecciona un tipo de usuario</option>
                                            <!-- Opciones se cargarán dinámicamente aquí -->
                                        </select>
                                        <div class="error-message"></div>
                                    </div>
                                    <input type="hidden" id="idE">
                                    <br>
                                    <div class="botonesModal">
                                        <a id="cerrarModalUsuarioE" class="btn btn-dark m-2 btnCrear"
                                            tabindex="3">CANCELAR</a>
                                        <button type="submit" class="btn btn-success m-2 btnCrear"
                                            tabindex="4">GUARDAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
