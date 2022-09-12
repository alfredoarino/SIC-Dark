{{-- DIV del formulario modal para editar el registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" style="margin-top: 100px" id="form_accion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="max-width: 750px" role="document">
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content" style="background: azure">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-white" id="exampleModalLabel">
                        <span id="titulo_accion"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-black">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ROW 1 de datos [FECHA_INICIO, FECHA_FIN] --}}
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="fecha_inicio_accion">Fecha inicio</label>
                            <input type="date" class="form-control"
                                   id="fecha_inicio_accion">
                            <input type="hidden" id="id_accion" name="id_accion">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="fecha_fin_accion">Fecha fin</label>
                            <input type="date" class="form-control"
                                   id="fecha_fin_accion">
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 de datos [ACCION] --}}
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label for="accion_accion">Acción</label>
                            <textarea class="form-control" id="accion_accion" style="height: 350px" name="accion_accion"></textarea>
                        </div>
                    </div>
                    {{-- FIN ROW 2 --}}
                    {{-- ROW 3 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar_accion"></button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver_accion">Volver</button>
                    </div>
                    {{-- FIN ROW 3 --}}
                </div>
            </div>
        </form>
    </div>
</div>
