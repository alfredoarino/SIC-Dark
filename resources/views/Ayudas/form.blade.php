{{-- DIV del formulario modal para editar movimiento --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1500px" role="document">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-white" id="exampleModalLabel">
                        <span id="titulo"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- CAMPO OCULTO PARA ALMACENAR EL ID DEL REGISTRO SELECCIONADO --}}
                    <input type="hidden" id="id_"> {{-- Id del registro de la ayuda  --}}
                    <input id="empleado_id" class="form-control text-right" hidden> {{-- Id del empleado seleccionado  --}}
                    <input id="convenio_id" class="form-control text-right" hidden> {{-- Id del convenio del empleado  --}}

                    {{-- ROW 1 de datos [EMPLEADO] --}}
                    <div class="row">
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label >Número</label>
                            {{-- BUSCAMOS EL NOMBRE DEL EMPLEADO CUANDO SE HA PULSADO UN NUMERO --}}
                            <input id="numero_empleado" maxlength="4" type="text" class="form-control text-center"  autocomplete="off" placeholder="">
                        </div>
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label >Nombre del empleado</label>
                            <input id="nombre_empleado" maxlength="100" type="text" class="form-control text-left"  autocomplete="off" placeholder="" readonly disabled>
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}

                    {{-- ROW 2 de datos [GASOLINA, JUZGADOS, BAJA_ENFERMEDAD, BAJA_ACCIDENTE] --}}
                    <div class="row">
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="gasolina">Gasolina</label>
                            <input id="gasolina" type="number" class="form-control text-right">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="juzgados">Juzgados</label>
                            <input id="juzgados" type="number" class="form-control text-right">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="baja_enfermedad">Baja por enfermedad</label>
                            <input id="baja_enfermedad" type="number" class="form-control text-right">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="baja_accidente">Baja por accidente</label>
                            <input id="baja_accidente" type="number" class="form-control text-right">
                        </div>
                    </div>
                    {{-- FIN ROW 2 --}}

                    {{-- ROW 3 de datos [INSPECCIONES, MINUSVALIA, OTROS, TOTAL] --}}
                    <div class="row">
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="inspecciones">Inspecciones</label>
                            <input id="inspecciones" type="number" class="form-control text-right">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="minusvalia">Minusvalía</label>
                            <input id="minusvalia" type="number" class="form-control text-right">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="otros">Otros</label>
                            <input id="otros" type="number" class="form-control text-right">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="total" style="font-size: 18px"><strong>Total:</strong></label>
                            <label id="total" style="font-size: 18px"></label>
                        </div>
                    </div>
                    {{-- FIN ROW 3 --}}

                    <br>

                    {{-- ROW 4 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar"></button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver">Volver</button>
                    </div>
                    {{-- FIN ROW 4 --}}

                </div>
            </div>
        </form>
    </div>
</div>
