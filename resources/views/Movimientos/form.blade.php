<!-- MODAL PARA SOLICITAR LOS DATOS -->
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- MOSTRAMOS LA CABECER DEL DÍA QUE SE HA SELECCIONADO --}}
                    <input id="dia" type="text" style="font-size: 22px; font-weight: bold;border: none" class="form-control modal-title col-12 text-center" readonly>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-1 col-md-1 col-sm-12"></div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label >Número</label>
                            {{-- BUSCAMOS EL NOMBRE DEL EMPLEADO CUANDO SE HA PULSADO UN NUMERO --}}
                            <input id="numero_empleado" maxlength="4" type="text" class="form-control text-center"  autocomplete="off" placeholder="">
                        </div>
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label >Nombre del empleado</label>
                            <input id="nombre_empleado" maxlength="100" type="text" class="form-control text-left"  autocomplete="off" placeholder="" readonly>
                        </div>
                        {{-- CAMPOS OCULTOS (id del registro de movimiento, id del empleado, fecha del evento--}}
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <input id="id" class="form-control text-right" hidden>
                            <input id="empleado_id" class="form-control text-right" hidden>
                            <input id="fecha_movimiento" class="form-control text-right" hidden>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-1 col-md-1 col-sm-12"></div>
                        <div class="form-group col-lg-3 col-md-2 col-sm-12">
                            <label >Entrada</label>
                            <input id="hora_entrada" type="time" maxlength="5"  class="form-control text-center">
                        </div>
                        <div class="form-group col-lg-3 col-md-2 col-sm-12">
                            <label >Salida</label>
                            <input id="hora_salida" type="time" maxlength="5" class="form-control text-center">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="plus_id">Plus</label>
                            <select class="placeholder js-states form-control" id="plus_id" name="plus_id">
                                <option value="">Plus</option>
                                @foreach($pluses as $p)
                                    <option value="{{$p->id}}">{{$p->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12"></div>
                    </div>
                </div>
                <div class="modal-footer" style="display: inline;">
                    <button type="button" class="btn btn-outline-danger" id="btn_eliminar" style="float:left;display: none" >
                        <i class="mbri-left"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-outline-dark mr-1" data-dismiss="modal" style="float:right;">
                        <i class="mbri-left"></i> Volver
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btn_salvar" style="float:right;">
                        <i class="mbri-left"></i> Aceptar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
