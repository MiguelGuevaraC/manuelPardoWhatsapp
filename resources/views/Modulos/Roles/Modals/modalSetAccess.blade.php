<!-- Modal para configurar accesos -->
<div class="modal fade" id="modalConfigurarAccesos" tabindex="-1" role="dialog" aria-labelledby="configurarAccesosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="configurarAccesosModalLabel">CONFIGURAR ACCESOS</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido del formulario para configurar accesos -->
                <form id="formularioAccesos">
                    @csrf
                                  
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nombreRol" class="form-label labelFormato">NOMBRE DEL ROL:</label>
                                <input type="text" class="form-control ajuste" id="nombreRol" name="nombreRol" tabindex="1" readonly>
                                <input type="hidden" id="typeUser_id" name="typeUser_id">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="accesosContainer">
                
                    </div>
                                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark m-2" data-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-success m-2">GUARDAR CAMBIOS</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
