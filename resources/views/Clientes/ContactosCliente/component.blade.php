<div class="tab-pane fade" id="icon-contactos" role="tabpanel" aria-labelledby="icon-contactos-tab">
    <div class="float-lg-right">
        <button class="btn btn-primary mr-2" type="button" id="btn_add_contacto">Añadir contacto</button>
    </div>
    <br>
    <div class="table-responsive mb-4">
        {{-- INICIO: TABLA --}}
        <table id="tabla_contactos" class="tabla_datos table table-hover">
            <thead class="bg-sgls">
            <tr>
                <th class="text-center text-dark" style="display: none">id</th>
                <th class="text-center text-dark" style="width: 700px;text-transform: none">Nombre</th>
                <th class="text-center text-dark" style="text-transform: none">Apellidos</th>
                <th class="text-center text-dark" style="width: 80px;text-transform: none">Cargo</th>
                <th class="text-center text-dark" style="width: 80px;text-transform: none">Teléfono</th>
                <th class="text-center text-dark" style="width: 150px;text-transform: none">Acciones</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th style="display: none"></th>
                <th>nombre</th>
                <th>apellidos</th>
                <th>cargo</th>
                <th>telefono</th>
                <th style="display: none"></th>
            </tr>
            </tfoot>
        </table>
        {{-- FIN: TABLA --}}
    </div>
</div>
