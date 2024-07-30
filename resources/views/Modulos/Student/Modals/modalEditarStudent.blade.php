<!-- Modal EDITAR -->
<div class="modal fade" id="modalEditarStudentE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel"><strong>EDITAR ESTUDIANTE</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registroStudentE">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="dniEstudianteE" class="form-label">DNI Estudiante:</label>
                                <input type="text" class="form-control" name="dniEstudianteE" id="dniEstudianteE" tabindex="1">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nombreEstudianteE" class="form-label">Nombre Estudiante:</label>
                                <input type="text" class="form-control" name="nombreEstudianteE" id="nombreEstudianteE" tabindex="2">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="fatherSurnameE" class="form-label">Apellido Padre:</label>
                                <input type="text" class="form-control" name="fatherSurnameE" id="fatherSurnameE" tabindex="3">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="motherSurnameE" class="form-label">Apellido Madre:</label>
                                <input type="text" class="form-control" name="motherSurnameE" id="motherSurnameE" tabindex="4">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="levellE" class="form-label">Nivel:</label>
                                <input type="text" class="form-control" name="levellE" id="levellE" tabindex="5">
                                <div class="error-messageGrupoE"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="gradooE" class="form-label">Grado:</label>
                                <input type="text" class="form-control" name="gradooE" id="gradooE" tabindex="6">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="seccionE" class="form-label">Sección:</label>
                                <input type="text" class="form-control" name="seccionE" id="seccionE" tabindex="7">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nombreApoderadoE" class="form-label">Nombre Apoderado:</label>
                                <input type="text" class="form-control" name="nombreApoderadoE" id="nombreApoderadoE" tabindex="8">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="dniApoderadoE" class="form-label">DNI Apoderado:</label>
                                <input type="text" class="form-control" name="dniApoderadoE" id="dniApoderadoE" tabindex="9">
                                <div class="error-messageGrupoE"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="telefonoE" class="form-label">Teléfono:</label>
                                <input type="text" class="form-control" name="telefonoE" id="telefonoE" tabindex="10">
                                <div class="error-messageGrupoE"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal" tabindex="11">Cancelar</button>
                        <button type="submit" class="btn btn-success" tabindex="12">Guardar</button>
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
