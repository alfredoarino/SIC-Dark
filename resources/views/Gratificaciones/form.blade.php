{{-- DIV del formulario modal para editar el registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 750px" role="document">
{{--        <meta name="csrf-token" content="{{ csrf_token() }}">--}}
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
                    {{-- ROW 1 de datos [EMPLEADO] --}}
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <label for="empleado_id">Empleado</label>
                            <select class="placeholder js-states form-control" id="empleado_id" name="empleado_id">
                                <option value="">Empleado</option>
                                @foreach($empleados as $e)
                                    <option value="{{$e->id}}">{{$e->numero}} - {{$e->nombre}} {{$e->apellidos}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- CAMPO OCULTO DEL IDENTIFICADOR  --}}
                        <input type="hidden" id="id_" name="id_">
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 de datos [CONCEPTO,IMPORTE,APLICA ] --}}
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label for="concepto">Concepto</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="concepto" maxlength="100" name="concepto">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="importe">Importe</label>
                            <input id="importe" type="number" class="form-control text-right">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="aplica_descuento">Aplica descuento</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="aplica_descuento" name="aplica_descuento">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- FIN ROW 2 --}}
                    {{-- ROW 3 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar"></button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver">Volver</button>
                    </div>
                    {{-- FIN ROW 3 --}}
                </div>
            </div>
        </form>
    </div>
</div>
