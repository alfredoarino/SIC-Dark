@extends('layouts.theme.app')
@section('estilos')

    {{-- ESTILOS PROPIOS DE LA PAGINA --}}
    @include('Gratificaciones.estilos')

@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing ml-5 mr-5">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                             <h3>Gratificaciones</h3>--}}
                        </div>
                    </div>
                </div>
                <div class="botones">
                    <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_print">Imprimir gratificaciones</button>
                    <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_add">Añadir gratificación</button>
                </div>
                <br>
                <div class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_gratificaciones" class="tabla_datos table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="width: 80px;text-transform: none">Número</th>
                                <th class="text-center text-dark" style="text-transform: none">Nombre</th>
                                <th class="text-center text-dark" style="text-transform: none">Apellidos</th>
                                <th class="text-center text-dark" style="text-transform: none">Concepto</th>
                                <th class="text-center text-dark" style="text-transform: none">Importe</th>
                                <th class="text-center text-dark" style="text-transform: none">Descuento</th>
                                <th class="text-center text-dark" style="width: 150px;text-transform: none">Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th>numero</th>
                                <th>nombre</th>
                                <th>apellidos</th>
                                <th>concepto</th>
                                <th>importe</th>
                                <th>aplica_descuento</th>
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
    @include('Gratificaciones.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Gratificaciones.scripts')
@stop
