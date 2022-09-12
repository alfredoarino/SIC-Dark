<div class="tab-pane fade" id="icon-acciones" role="tabpanel" aria-labelledby="icon-acciones-tab">
    <div class="float-lg-right">
        <button class="btn btn-primary mr-2" type="button" id="btn_add_accion">Añadir acción</button>
    </div>
    <br>
    <div class="table-responsive mb-4">
        {{-- INICIO: TABLA --}}
        <table id="tabla_acciones" class="tabla_datos table table-hover">
            <thead class="bg-sgls">
            <tr>
                <th class="text-center text-dark" style="display: none">id</th>
                <th class="text-center text-dark" style="width: 700px;text-transform: none">Fecha inicio</th>
                <th class="text-center text-dark" style="text-transform: none">Fecha fin</th>
                <th class="text-center text-dark" style="width: 80px;text-transform: none">Acción</th>
                <th class="text-center text-dark" style="width: 80px;text-transform: none">Estado</th>
                <th class="text-center text-dark" style="width: 150px;text-transform: none">Acciones</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th style="display: none"></th>
                <th>fecha_inicio</th>
                <th>fecha_fin</th>
                <th>accion</th>
                <th>estado</th>
                <th style="display: none"></th>
            </tr>
            </tfoot>
        </table>
        {{-- FIN: TABLA --}}
    </div>
</div>
