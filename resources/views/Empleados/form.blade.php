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
                    {{-- ROW 1 de datos [NUMERO, NOMBRE, APELLIDOS, DNI] --}}
                    <div class="row">
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="numero">Número</label>
                            <input type="text" class="form-control" id="numero" maxlength="5">
                            <input type="hidden" id="id_" name="id_">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="nombre" maxlength="50" name="nombre">
                        </div>
                        <div class="form-group col-lg-5 col-md-5 col-sm-12">
                            <label for="Apellidos">Apellidos</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="apellidos" maxlength="75" name="apellidos">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="dni">DNI</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="dni" maxlength="10" name="dni">
                        </div>
                    </div>
                    {{-- FIN ROW 1 --}}
                    {{-- ROW 2 de datos [DELEGACION, EMPRESA, CONVENIO, TELEFONO1, TELEFONO2] --}}
                    <div class="row">
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
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
                            <label for="convenio_id">Convenio</label>
                            <select class="placeholder js-states form-control" id="convenio_id" name="convenio_id">
                                <option value="">Convenio</option>
                                @foreach($convenios as $c)
                                    <option value="{{$c->id}}">{{$c->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" maxlength="9" name="telefono">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="telefono2">Otro teléfono</label>
                            <input type="text" class="form-control" id="telefono2" maxlength="9" name="telefono2">
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
                    {{-- ROW 4 de datos [FECHA DE ALTA, FECHA DE NACIMIENTO, CORREO ELECTRÓNICO, TIP, PERSONAL OPERATIVO, LICENCIA DE ARMAS, VEHÍCULO] --}}
                    <div class="row">
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="fecha_alta">Fecha de alta</label>
                            <input type="date" class="form-control" id="fecha_alta" name="fecha_alta">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-12">
                            <label for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <label for="email">Correo electrónico</label>
                            <input type="text" style="text-transform:lowercase;"
                                   onkeyup="javascript:this.value=this.value.toLowerCase();"
                                   class="form-control" id="email" maxlength="100" name="email">
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="tip">TIP</label>
                            <input type="text" class="form-control" id="tip" maxlength="6" name="tip">
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="operativo">P.Operativo</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="operativo" name="operativo">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="licencia_armas">Armas</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="licencia_arma" name="licencia_arma">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="vehiculo">Vehículo</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id ="vehiculo_propio" name="vehiculo_propio">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- FIN ROW 4 --}}
                    {{-- ROW 5 de datos [COBRO POR TRANSFERENCIA, CUENTA BANCARIA, ACTIVO, FOTOGRAFÍA] --}}
                    <div class="row">
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="cobro_transferencia">Transferencia</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id="cobro_transferencia" name="cobro_transferencia">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="cuenta_bancaria">Cuenta bancaria</label>
                            <input type="text" style="text-transform:uppercase;"
                                   onkeyup="javascript:this.value=this.value.toUpperCase();"
                                   class="form-control" id="cuenta_bancaria" name="cuenta_bancaria" maxlength="24" readonly>
                        </div>
                        <div class="form-group col-lg-1 col-md-1 col-sm-12">
                            <label for="activo">Activo</label>
                            <div>
                                <label class="switch s-icons s-outline s-outline-dark mr-2 mt-2">
                                    <input type="checkbox" id="activo" name="activo">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="imagen">Fotografía del empleado</label>
                            <input name="imagen" id="imagen" type='file' class="form-control" name="imagen" accept="image/*">
                        </div>
                    </div>
                    {{-- ROW 6 BOTONES--}}
                    <div class="row" style="float: right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_salvar"></button>
                        <button class="btn btn-primary mr-2" type="button" id="btn_volver">Volver</button>
                    </div>
                    {{-- FIN ROW 6 --}}
                </div>
            </div>
        </form>
    </div>
</div>
