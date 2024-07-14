<!-- Modal EDITAR -->
<div class="modal fade" id="modalEditarMensajeriaE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="editarModalLabel">EDITAR ESTUDIANTE</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registroMensajeriaE">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label for="dniEstudianteE" class="form-label labelFormato">DNI Estudiante:</label>
                                <input type="text" class="form-control ajuste" name="dniEstudianteE" id="dniEstudianteE" tabindex="1">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="nombreEstudianteE" class="form-label labelFormato">Nombre Estudiante:</label>
                                <input type="text" class="form-control ajuste" name="nombreEstudianteE" id="nombreEstudianteE" tabindex="2">
                                <div class="error-messageGrupoE"></div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="fatherSurnameE" class="form-label labelFormato">Apellido Padre:</label>
                                <input type="text" class="form-control ajuste" name="fatherSurnameE" id="fatherSurnameE" tabindex="2">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="motherSurnameE" class="form-label labelFormato">Apellido Madre:</label>
                                <input type="text" class="form-control ajuste" name="motherSurnameE" id="motherSurnameE" tabindex="2">
                                <div class="error-messageGrupoE"></div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="levellE" class="form-label labelFormato">Level:</label>
                                <input type="text" class="form-control ajuste" name="levellE" id="levellE" tabindex="3">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="gradooE" class="form-label labelFormato">Grado:</label>
                                <input type="text" class="form-control ajuste" name="gradooE" id="gradooE" tabindex="4">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="seccionE" class="form-label labelFormato">Sección:</label>
                                <input type="text" class="form-control ajuste" name="seccionE" id="seccionE" tabindex="5">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="nombreApoderadoE" class="form-label labelFormato">Nombre Apoderado:</label>
                                <input type="text" class="form-control ajuste" name="nombreApoderadoE" id="nombreApoderadoE" tabindex="6">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="dniApoderadoE" class="form-label labelFormato">DNI Apoderado:</label>
                                <input type="text" class="form-control ajuste" name="dniApoderadoE" id="dniApoderadoE" tabindex="7">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="telefonoE" class="form-label labelFormato">Teléfono:</label>
                                <input type="text" class="form-control ajuste" name="telefonoE" id="telefonoE" tabindex="8">
                                <div class="error-messageGrupoE"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark m-2 btnEditar" data-dismiss="modal" tabindex="9">Cancelar</button>
                        <button type="submit" class="btn btn-success m-2 btnEditar" tabindex="10">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Importar las librerías necesarias -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
