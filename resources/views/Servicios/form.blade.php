{{-- DIV del formulario modal para editar el registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1500px" role="document">
{{--        <meta name="csrf-token" content="{{ csrf_token() }}">--}}
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-white" id="exampleModalLabel">
                            <img id="imagenSeleccionada" style="max-height: 150px;">
                        <span id="titulo"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ROW 1 de datos [NUMERO, NOMBRE, NOMBRE REDUCIDO] --}}
                    <div class="row">
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="numero">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" maxlength="5" readonly>
                            <input type="hidden" id="id_" name="id_">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="nombre" maxlength="100" name="nombre">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="nombre_reducido">Abreviado</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="nombre_reducido" maxlength="50" name="nombre_reducido">
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 de datos [CLIENTE, DELEGACION, EMPRESA, TELEFONO] --}}
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="cliente_id">Cliente</label>
                            <select class="placeholder js-states form-control" id="cliente_id" name="cliente_id">
                                <option value="">Cliente</option>
                                @foreach($clientes as $c)
                                    <option value="{{$c->id}}">{{$c->razonsocial}}</option>
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
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="delegacion_id">Delegación</label>
                            <select class="placeholder js-states form-control" id="delegacion_id" name="delegacion_id">
                                <option value="">Delegación</option>
                                @foreach($delegaciones as $d)
                                    <option value="{{$d->id}}">{{$d->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" maxlength="9" name="telefono">
                        </div>
                    </div>
                    {{-- FIN ROW 2 --}}
                    {{-- ROW 3 de datos [DIRECCION, LATITUD, LONGITUD] --}}
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="direccion">Dirección</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button onClick="buscarDireccion()" id="button-addon2" type="button" class="btn btn-outline-primary">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-geo-alt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M12.166 8.94C12.696 7.867 13 6.862 13 6A5 5 0 0 0 3 6c0 .862.305 1.867.834 2.94.524 1.062 1.234 2.12 1.96 3.07A31.481 31.481 0 0 0 8 14.58l.208-.22a31.493 31.493 0 0 0 1.998-2.35c.726-.95 1.436-2.008 1.96-3.07zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                            <path fill-rule="evenodd" d="M8 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                        </svg>
                                    </button>
                                </div>
                                <input type="text" style="text-transform:uppercase;"
                                       onkeyup="javascript:this.value=this.value.toUpperCase();"
                                       class="form-control" id="direccion" maxlength="255" name="direccion">
                            </div>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="latitud">Latitud</label>
                            <input type="text" class="form-control" id="latitud" readonly name="latitud">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="longitud">Longitud</label>
                            <input type="text" class="form-control" id="longitud" readonly name="longitud">
                        </div>
                    </div>
                    {{-- FIN ROW 3 --}}
                    {{-- ROW 4 de datos [PAGOS, TIPO TARIFA, IMPORTE, COPIAS] --}}
                    <div class="row">
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="pago_id">Pago a empleados</label>
                            <select class="placeholder js-states form-control" id="pago_id" name="pago_id">
                                <option value="">Pago</option>
                                @foreach($pagos as $p)
                                    <option value="{{$p->id}}">{{$p->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="tipo_tarifa">Tipo tarifa</label>
                            <select class="placeholder js-states form-control" id="tipo_tarifa" name="tipo_tarifa">
                                <option value="">Tipo tarifa</option>
                                <option value="H">Horas</option>
                                <option value="M">Mensual</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="importe">Importe</label>
                            <input id="importe" name="importe" type="number" class="form-control text-right">
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="copias">Copias</label>
                            <input id="copias" name="copias" type="number" class="form-control text-right">
                        </div>
                    </div>
                    {{-- FIN ROW 4 --}}
                    {{-- ROW 5 de datos [FECHA TARIFA, SERIE, CONTRATO, SIN MOVIMIENTOS, FACTURA MANUAL, ACTIVO] --}}
                    <div class="row">
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="fecha_tarifa">Fecha tarifa</label>
                            <input type="date" class="form-control" id="fecha_tarifa" name="fecha_tarifa">
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="serie">Serie</label>
                            <input type="text" class="form-control" id="serie" maxlength="3" name="serie" readonly>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="contrato">Contrato</label>
                            <input type="text" class="form-control" id="contrato" maxlength="10" name="contrato">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="sin_movimientos">Sin movimientos</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="sin_movimientos" name="sin_movimientos">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="plantilla">Plantilla activa</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="plantilla" name="plantilla">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="factura_manual">Factura manual</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="factura_manual" name="factura_manual">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="activo">Activo</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="activo" name="activo">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- FIN ROW 5 --}}
                    {{-- ROW 6 de datos [CONCEPTO DE LA FACTURA] --}}
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12" id="div_concepto_factura" style="display: none">
                            <label for="concepto_factura">Concepto en la factura</label>
                            <textarea class="form-control" id="concepto_factura" name="concepto_factura"></textarea>
                        </div>
                    </div>
                    {{-- FIN ROW 6 --}}
                    {{-- ROW 7 de datos [REFERENCIA CLIENTE, REFERENCIA NUESTRA] --}}
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="ref_cliente">Referencia del cliente</label>
                            <textarea class="form-control" id="ref_cliente" name="ref_cliente"></textarea>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="ref_nuestra">Referencia nuestra</label>
                            <textarea class="form-control" id="ref_nuestra" name="ref_nuestra"></textarea>
                        </div>
                    </div>
                    {{-- FIN ROW 7 --}}
                    {{-- ROW 8 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar"></button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver">Volver</button>
                    </div>
                    {{-- FIN ROW 8 --}}
                </div>
            </div>
        </form>
    </div>
</div>
