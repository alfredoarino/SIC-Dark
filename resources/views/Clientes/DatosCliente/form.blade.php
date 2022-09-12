<div class="tab-pane fade show active" id="icon-datos" role="tabpanel" aria-labelledby="icon-datos-tab">
        {{-- ROW 1 de datos [RAZON SOCIAL Y CIF] --}}
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12">
            <label for="razonsocial">Razón Social</label>
            <input type="text" style="text-transform:uppercase;" class="form-control"
                   id="razonsocial" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
            <input type="hidden" id="id_" name="id_">
        </div>
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="cif">CIF</label>
            <input type="text" style="text-transform:uppercase;"
                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                   class="form-control" id="cif" maxlength="12" name="cif">
        </div>
        <div class="form-group col-lg-2 col-md-2 col-sm-12">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" maxlength="9" name="telefono">
        </div>
    </div>
    {{-- FIN ROW 1 --}}
    {{-- ROW 2 de datos [DIRECCION, POBLACION, PROVINCIA, CP] --}}
    <div class="row">
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="direccion">Dirección</label>
            <input type="text" style="text-transform:uppercase;"
                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                   class="form-control" id="direccion" maxlength="100" name="direccion">
        </div>
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="poblacion">Población</label>
            <input type="text" style="text-transform:uppercase;"
                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                   class="form-control" id="poblacion" maxlength="75" name="poblacion">
        </div>
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="provincia">Provincia</label>
            <input type="text" style="text-transform:uppercase;"
                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                   class="form-control" id="provincia" maxlength="50" name="provincia">
        </div>
        <div class="form-group col-lg-2 col-md-2 col-sm-12">
            <label for="cp">Código postal</label>
            <input type="text" style="text-transform:uppercase;"
                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                   class="form-control" id="cp" maxlength="5" name="cp">
        </div>
    </div>
    {{-- FIN ROW 2 --}}
    {{-- ROW 3 de datos [DELEGACION, EMPRESA, SECTOR, FORMA DE PAGO] --}}
    <div class="row">
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="delegacion_id">Delegación</label>
            <select class="placeholder js-states form-control" id="delegacion_id" name="delegacion_id">
                <option value="">Delegación</option>
                @foreach($delegaciones as $d)
                    <option value="{{$d->id}}">{{$d->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="empresa_id">Empresa</label>
            <select class="placeholder js-states form-control" id="empresa_id" name="empresa_id">
                <option value="">Empresa</option>
                @foreach($empresas as $e)
                    <option value="{{$e->id}}">{{$e->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="sector_id">Sector</label>
            <select class="placeholder js-states form-control" id="sector_id" name="sector_id">
                <option value="">Sector</option>
                @foreach($sectores as $s)
                    <option value="{{$s->id}}">{{$s->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="forma_pago_id">Forma de pago</label>
            <select class="placeholder js-states form-control" id="forma_pago_id" name="forma_pago_id">
                <option value="">Forma de pago</option>
                @foreach($formasPago as $f)
                    <option value="{{$f->id}}">{{$f->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{-- FIN ROW 3 --}}
    {{-- ROW 4 de datos [EMAIL,CUENTACONTABLE,FACTURAS CONJUNTAS, FACTURA ELECTRÓNICA] --}}
    <div class="row">
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="email">E-Mail</label>
            <input type="text" style="text-transform:lowercase;"
                   onkeyup="javascript:this.value=this.value.toLowerCase();"
                   class="form-control" id="email" maxlength="100" name="email">
        </div>
        <div class="form-group col-lg-3 col-md-3 col-sm-12">
            <label for="cuentacontable">Cuenta contable</label>
            <input type="text" class="form-control" id="cuentacontable" maxlength="10" name="cuentacontable">
        </div>
        <div class="form-group col-lg-2 col-md-2 col-sm-12">
            <label for="facturas_conjuntas">F. Conjuntas</label>
            <div>
                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                    <input type="checkbox" id ="facturas_conjuntas" name="facturas_conjuntas">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2 col-md-2 col-sm-12">
            <label for="factura_electronica">F. Electrónica</label>
            <div>
                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                    <input type="checkbox" id ="factura_electronica" name="factura_electronica">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>
    {{-- FIN ROW 4 --}}
    {{-- ROW 5 de datos OBSERVACIONES --}}
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
        </div>
    </div>
    {{-- FIN ROW 5 --}}
    {{-- ROW 6 BOTONES--}}
    <div class="row" style="float: right">
        <button class="btn btn-primary mr-2" type="button" id="btn_salvar"></button>
        <button class="btn btn-primary mr-2" type="button" id="btn_volver">Volver</button>
    </div>
    {{-- FIN ROW 6 --}}
</div>
