{{-- DIV del formulario modal para editar movimiento --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 800px" role="document">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-black" id="exampleModalLabel">
                        <span id="titulo"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-black">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- CAMPO OCULTO PARA ALMACENAR EL ID DEL REGISTRO SELECCIONADO --}}
                    <input type="hidden" id="id_">
                    {{-- ROW 1 de datos [DELEGACIONES, FECHA] --}}
                    <div class="row" >
                        <div class="form-group col-lg-8 col-md-8 col-sm-12" id="seleccion_simple">
                            <label for="delegacion_id">Delegaciones</label>
                            <select class="placeholder js-states form-control" id="delegacion_id">
                                @foreach($delegaciones as $d)
                                    <option value="{{$d->id}}">{{$d->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-8 col-md-8 col-sm-12" id="seleccion_multiple">
                            <label for="delegaciones">Delegaciones</label>
                            <select class="form-control tagging" multiple="multiple" id="delegaciones">
                                @foreach($delegaciones as $d)
                                    <option value="{{$d->id}}">{{$d->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="fecha">Fecha</label>
                            <input id="fecha" type="date" class="form-control text-right">
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 de datos [NOMBRE] --}}
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" style="text-transform:uppercase;" class="form-control"
                                   id="nombre" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                    </div>
                    {{-- FIN ROW 2 --}}
                    <br>
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
