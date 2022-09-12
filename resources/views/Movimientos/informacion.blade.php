<!-- MODAL PARA MOSTRAR LA INFORMACIÓN DE LA SEMANA O DÍA, SEGÚN LO SOLICITADO -->
<div class="modal animated zoomInUp custo-zoomInUp" id="informacion" tabindex="-1" role="dialog" aria-labelledby="informacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- MOSTRAMOS LA CABECERA DEL DÍA QUE SE HA SELECCIONADO --}}
                <input id="fechas_saleccionada" type="text" style="font-size: 22px; font-weight: bold;border: none" class="form-control modal-title col-12 text-center" readonly>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <h5 class="modal-title col-12 text-center" ><strong>Horas realizadas</strong></h5>
                    <table id="tabla_informacion" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                        <thead>
                        <tr>
                            <th class="text-center">Número</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Apellidos</th>
                            <th class="text-center">Horas</th>
                        </tr>
                        </thead>
                        <tbody>
                            {{-- Aquí se colocará la información de las horas --}}
                        </tbody>
                    </table>
                </div>
                {{-- Tabla de los conflictos. Al inicio está oculta  --}}
                <div class="table-responsive" id ="id_tabla_conflictos" style="display: none">
                    <h5 class="modal-title col-12 text-center" ><strong>Conflictos</strong></h5>
                    <table id="tabla_conflictos" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                        <thead>
                        <tr>
                            <th class="text-center">Empleado</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">H.E</th>
                            <th class="text-center">H.S</th>
                            <th class="text-center">Servicio</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">H.E</th>
                            <th class="text-center">H.S</th>
                        </tr>
                        </thead>
                        <tbody>
                            {{-- Aquí se colocará la información de los conflictos, si los hubiese --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="display: inline;">
                <button type="button" class="btn btn-outline-dark mr-1" data-dismiss="modal" style="float:right;">
                    <i class="mbri-left"></i> Volver
                </button>
            </div>
        </div>
    </div>
</div>
