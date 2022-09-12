{{-- DIV del formulario modal para editar registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="importe_modal" style="z-index: 1400;" tabindex="-10" role="dialog" aria-labelledby="importe_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 350px" role="document">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-black" id="importe_modalLabel">
                        <span id="importe_titulo"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-black">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- CAMPOS OCULTOS --}}
                    <input type="hidden" id="importe_tipo">
                    <input type="hidden" id="importe_id">
                    <input type="hidden" id="importe_empleado_id">
                    <input type="hidden" id="importe_adelanto_id">
                    {{-- ROW 1 de datos [PLAZO] --}}
                    <div class="row float-right mb-3">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label class="float-right" id="importe_etiqueta" for="importe_importe">Importe</label>
                            <input id="importe_importe" type="number" class="form-control text-right" name="importe_importe">
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_importe_salvar">Grabar</button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_importe_volver">Volver</button>
                    </div>
                    {{-- FIN ROW 2 --}}
                </div>
            </div>
        </form>
    </div>
</div>
