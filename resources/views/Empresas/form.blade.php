{{-- DIV del formulario modal para editar el registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="max-width: 1000px" role="document">
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
                    {{-- ROW 1 de datos [NOMBRE, CIF, SIGLAS] --}}
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre"
                                   maxlength="100" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();">
                            <input type="hidden" id="id_" name="id_">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="cif">CIF</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="cif" maxlength="9">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="codigo">Código</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="codigo" maxlength="2">
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
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
