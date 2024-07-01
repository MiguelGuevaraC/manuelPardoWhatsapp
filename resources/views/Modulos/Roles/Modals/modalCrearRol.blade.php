<!-- Modal CREAR-->
<div class="modal fade" id="modalNuevoRol" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR NUEVO TIPO DE USUARIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroRol">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="name" class="form-label labelFormato">NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="name" id="name">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="botonesModal">
                        <a id="cerrarModal" class="btn btn-dark m-2 ancho btnCrear" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-success m-2 ancho btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



