<!-- MODAL PARA SOLICITAR LOS DATOS -->
<div class="modal animated zoomInUp custo-zoomInUp" id="copia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- TÍTULO DE LA VENTANA --}}
                    {{-- MOSTRAMOS LA CABECER DEL DÍA QUE SE HA SELECCIONADO --}}
                    <input value="Copiar movimientos" type="text" style="font-size: 22px; font-weight: bold;border: none" class="form-control modal-title col-12 text-center" readonly>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-1 col-md-1 col-sm-12"></div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label >Fecha origen</label>
                            <input id="fechaOrigen" class="form-control flatpickr active" type="text" placeholder="">
                        </div>
                        {{-- CAMPOS OCULTOS (id del registro de movimiento, id del empleado, fecha del evento--}}
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <input id="copia_id" class="form-control text-right" hidden>
                            <input id="copia_servicio_id" class="form-control text-right" hidden>
                            <input id="copia_fecha_movimiento" class="form-control text-right" hidden>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-1 col-md-1 col-sm-12"></div>
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label >Fecha(s) destino</label>
                            <input id="fechaDestino" class="form-control flatpickr-multiple active" type="text" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: inline;">
                    <button type="button" class="btn btn-outline-dark mr-1" data-dismiss="modal" style="float:right;">
                        <i class="mbri-left"></i> Volver
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btn_copia_salvar" style="float:right;">
                        <i class="mbri-left"></i> Aceptar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
