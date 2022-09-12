{{-- DIV del formulario modal para editar registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 750px" role="document">
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
                    {{-- ROW 1 de datos [FECHA_INICIO, FECHA_FIN, DIAS] --}}
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="fecha_fin">Fecha final</label>
                            <input type="date" class="form-control" id="fecha_fin">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="dias">Total días</label>
                            <input type="text" class="form-control text-right" id="dias" readonly>
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    <br>
                    {{-- ROW 2 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar"></button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver">Volver</button>
                    </div>
                    {{-- FIN ROW 2 --}}
                </div>
            </div>
        </form>
    </div>
</div>
