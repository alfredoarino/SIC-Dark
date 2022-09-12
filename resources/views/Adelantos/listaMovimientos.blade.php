{{-- DIV del formulario modal para editar registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="listaMovimientos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1500px" role="document">
        <div class="modal-content">
            <div class="modal-header bg-sgls">
                <h5 class="modal-title text-black" id="exampleModalLabel">
                    <span>Listado de movimientos del adelanto</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-black">&times;</span>
                </button>
            </div>
            {{-- CAMPOS OCULTO PARA ALMACENAR LOS ID DEL REGISTRO SELECCIONADO--}}
            <input type="hidden" id="listamovimientos_id">
            <input type="hidden" id="listamovimientos_empleado_id">
            <input type="hidden" id="listamovimientos_adelanto_id">
            {{-- MOSTRAMOS RESUMEN DEL ADELANTO --}}
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing mt-2">
                <div class="widget widget-chart-three">
                    <div class="widget-heading">
                        <div class="">
                            <h5 class="" id="listamovimientos_empleado"></h5>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row">
                            <div class="form-group col-lg-2 col-md-2 col-sm-12 mt-2 ml-3">
                                <p id="listaMovimientos_fecha"></p>
                                <p id="listaMovimientos_solicitado_importe"></p>
                                <p id="listaMovimientos_fecha_fin"></p>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-12 mt-2 ml-3">
                                <p id="listaMovimientos_liquidados"></p>
                                <p id="listaMovimientos_liquidados_importe"></p>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-12 mt-2 ml-3">
                                <p id="listaMovimientos_aumentos"></p>
                                <p id="listaMovimientos_aumentos_importe"></p>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-12 mt-2 ml-3">
                                <p id="listaMovimientos_pendientes"></p>
                                <p id="listaMovimientos_pendientes_importe"></p>
                                <p id="listaMovimientos_observaciones"></p>
                            </div>
                        </div>
                        {{-- MOSTRAMOS LOS BOTONES --}}
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_listamovimientos_volver">Volver</button>
                                <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_listamovimientos_print">Imprimir adelanto</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- FIN: MOSTRAMOS RESUMEN DEL ADELANTO --}}

            <div class="modal-body">
                <div class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_listaMovimientos" class="tabla_datos2 table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="text-transform: none">Mes</th>
                                <th class="text-center text-dark" style="text-transform: none">Ano</th>
                                <th class="text-center text-dark" style="text-transform: none">Fecha</th>
                                <th class="text-center text-dark" style="text-transform: none">Tipo</th>
                                <th class="text-center text-dark" style="text-transform: none">Importe</th>
                                <th class="text-center text-dark" style="text-transform: none">Estado</th>
                                <th class="text-center text-dark" style="text-transform: none">Acciones</th>
                                <th class="text-center text-dark" style="text-transform: none;display: none">Creado</th>
                                <th class="text-center text-dark" style="text-transform: none;display: none">Adelanto</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- FINI: TABLA --}}
                </div>
            </div>
        </div>
    </div>
</div>
