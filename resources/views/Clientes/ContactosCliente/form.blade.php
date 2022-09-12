{{-- DIV del formulario modal para editar el registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" style="margin-top: 100px" id="form_contacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="max-width: 750px" role="document">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content" style="background: azure">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-white" id="exampleModalLabel">
                        <span id="titulo_contacto"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-black">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ROW 1 de datos [NOMBRE, APELLIDOS] --}}
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="nombre_contacto">Nombre</label>
                            <input type="text" style="text-transform:uppercase;" class="form-control"
                                   id="nombre_contacto" maxlength="50" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            <input type="hidden" id="id_contacto" name="id_contacto">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="apellidos_contacto">Apellidos</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="apellidos_contacto" maxlength="50" name="apellidos_contacto">
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 de datos [CARGO, TELEFONO] --}}
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label for="cargo_contacto">Cargo</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="cargo_contacto" maxlength="100" name="cargo_contacto">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="telefono_contacto">Teléfono</label>
                            <input type="text" class="form-control" id="telefono_contacto" maxlength="9" name="telefono_contacto">
                        </div>
                    </div>
                    {{-- FIN ROW 2 --}}
                    {{-- ROW 3 de datos [EMAIL] --}}
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label for="email_contacto">E-Mail</label>
                            <input type="text" style="text-transform:lowercase;"
                                   onkeyup="javascript:this.value=this.value.toLowerCase();"
                                   class="form-control" id="email_contacto" maxlength="100" name="email_contacto">
                        </div>
                    </div>
                    {{-- FIN ROW 3 --}}
                    {{-- ROW 4 de datos OBSERVACIONES --}}
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label for="observaciones_contacto">Observaciones</label>
                            <textarea class="form-control" id="observaciones_contacto" name="observaciones_contacto"></textarea>
                        </div>
                    </div>
                    {{-- FIN ROW 4 --}}
                    {{-- ROW 6 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar_contacto"></button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver_contacto">Volver</button>
                    </div>
                    {{-- FIN ROW 6 --}}
                </div>
            </div>
        </form>
    </div>
</div>
