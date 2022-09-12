{{-- DIV del formulario modal para editar registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="aumento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 750px" role="document">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-white" id="exampleModalLabel">
                        <span id="titulo_aumento"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-black">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- CAMPOS OCULTO PARA ALMACENAR LOS ID DEL REGISTRO SELECCIONADO --}}
                    <input type="hidden" id="id_">
                    <input type="hidden" id="empleado_id_">
                    {{-- ROW 1 de datos [FECHA, IMPORTE] --}}
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="fecha_aumento">Fecha</label>
                            <input type="date" class="form-control" id="fecha_aumento" name="fecha_aumento">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="importe_aumento">Importe</label>
                            <input id="importe_aumento" type="number" class="form-control text-right" name="importe_aumento">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="observaciones_aumento">Observaciones</label>
                            <textarea id="observaciones_aumento" class="form-control" name="observaciones_aumento"></textarea>
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    <br>
                    {{-- ROW 2 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar_aumento">Grabar</button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver_aumento">Volver</button>
                    </div>
                    {{-- FIN ROW 2 --}}
                </div>
            </div>
        </form>
    </div>
</div>
