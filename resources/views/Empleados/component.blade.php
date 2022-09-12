@extends('layouts.theme.app')
@section('estilos')

    {{-- ESTILOS PROPIOS DE LA PAGINA --}}
    @include('Empleados.estilos')

@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing ml-5 mr-5">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                             <h3>Empleados</h3>--}}
                        </div>
                    </div>
                </div>
                <div class="float-lg-right">
                    <button class="btn btn-primary mr-2" type="button" id="btn_add">Añadir empleado</button>
                </div>
                <br>
                <div class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_empleados" class="tabla_datos table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="width: 80px;text-transform: none">Número</th>
                                <th class="text-center text-dark" style="text-transform: none">Nombre</th>
                                <th class="text-center text-dark" style="text-transform: none">Apellidos</th>
                                <th class="text-center text-dark" style="text-transform: none">DNI</th>
                                <th class="text-center text-dark" style="text-transform: none">Teléfono</th>
                                <th class="text-center text-dark" style="text-transform: none">TIP</th>
                                <th class="text-center text-dark" style="width:12px;text-transform: none">Activo</th>
                                <th class="text-center text-dark" style="width: 80px;text-transform: none">Delegación</th>
                                <th class="text-center text-dark" style="width: 150px;text-transform: none">Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th>numero</th>
                                <th>nombre</th>
                                <th>apellidos</th>
                                <th>dni</th>
                                <th>telefono</th>
                                <th>tip</th>
                                <th>activo</th>
                                <th>delegacion</th>
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
    @include('empleados.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('empleados.scripts')
@stop
