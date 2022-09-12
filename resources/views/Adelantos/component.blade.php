@extends('layouts.theme.app')
@section('estilos')
    {{-- ESTILOS PROPIOS DE LA PÁGINA --}}
      @include('Adelantos.estilos')
@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing ml-5 mr-5">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                             <h3>Adelantos</h3>--}}
                        </div>
                    </div>
                </div>
                <br>
                <div class="seleccion">
                    <div class="estado">
                        {{-- ESTADOS --}}
                        <label for="estado"><strong>Estado</strong></label>
                        <select class="placeholder js-states form-control" style="max-width: 20px" id="estado">
                            <option value="">Elegir estado</option>
                            <option value="1">Pendientes</option>
                            <option value="0">Finalizados</option>
                            <option value="9">Todos</option>
                        </select>
                    </div>
                    <div class="botones">
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_print">Imprimir adelantos</button>
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_add">Añadir adelanto</button>
                    </div>
                </div>
                <br>
                <div id ="tabla_" class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_adelantos" class="tabla_datos table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="max-width:5px;text-transform: none">Número</th>
                                <th class="text-center text-dark" style="max-width:7px;text-transform: none">Nombre</th>
                                <th class="text-center text-dark" style="text-transform: none">Apellidos</th>
                                <th class="text-center text-dark" style="max-width:7px;text-transform: none">Fecha</th>
                                <th class="text-center text-dark" style="max-width:7px;text-transform: none">Estado</th>
                                <th class="text-center text-dark" style="text-transform: none">Acciones</th>
                                <th class="text-center text-dark" style="display: none">Importe plazo</th>
{{--                                <th class="text-center text-dark" style="display: none">Saldo</th>--}}
                                <th class="text-center text-dark" style="display: none">Empleado</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th style="max-width:5px;">numero</th>
                                <th style="max-width:7px;">nombre</th>
                                <th>apellidos</th>
                                <th style="max-width:7px;">fecha</th>
                                <th style="max-width:7px;display: none">estado</th>
                                <th style="display: none"></th>
                                <th style="display: none"></th>
                                <th style="display: none"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- FINI: TABLA --}}
                </div>
            </div>
        </div>
    </div>
    {{-- Incluimos la ventana modal --}}
    @include('Adelantos.form')
    @include('Adelantos.importe')
    @include('Adelantos.aumento')
    @include('Adelantos.listaMovimientos')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Adelantos.scripts')
@stop
