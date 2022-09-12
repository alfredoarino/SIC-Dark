{{-- DIV del formulario modal para editar registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 750px" role="document">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-white" id="exampleModalLabel">
                        <span id="titulo"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-black">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ROW 1 de datos [EMPLEADO] --}}
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label for="empleado_id"><strong>Empleado</strong></label>
                            <select class="placeholder js-states form-control" id="empleado_id" name="empleado_id">
                                <option value="">Empleado</option>
                                @foreach($empleados as $e)
                                    <option value="{{$e->id}}">{{$e->numero}} - {{$e->nombre}} {{$e->apellidos}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 de datos [FECHA, IMPORTE, IMPORTE_PLAZO] --}}
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="importe">Importe</label>
                            <input id="importe" type="number" class="form-control text-right" name="importe">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="importe_plazo">Importe del plazo</label>
                            <input id="importe_plazo" type="number" class="form-control text-right" name="importe_plazo">
                        </div>
                    </div>
                    {{-- FIN ROW 2 --}}
                    {{-- ROW 3 de datos [OBSERVACIONES] --}}
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
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
