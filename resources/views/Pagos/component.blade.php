@extends('layouts.theme.app')
@section('estilos')

    {{-- ESTILOS PROPIOS DE LA PAGINA --}}
    @include('pagos.estilos')

@stop
@section('content')
    {{-- Componente principal --}}
    <div class="container">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-4">
                    <div class="widget-header">
                        <div class="page-header">
                            <div class="page-title">
{{--                                <h3>Pagos</h3>--}}
                            </div>
                        </div>
                    </div>
                    <div class="float-lg-right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_add">Añadir pago</button>
                    </div>
                    <br>
                    <div class="table-responsive mb-4">
                        {{-- INICIO: TABLA --}}
                        <table id="tabla_pagos" class="tabla_datos table table-hover">
                            <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="text-transform: none">Nombre</th>
                                <th class="text-center text-dark" style="text-transform: none">Importe</th>
                                <th class="text-center text-dark" style="text-transform: none">Incentivos</th>
                                <th class="text-center text-dark" style="width: 150px;text-transform: none">Acciones</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th style="display: none">nombre</th>
                                <th style="display: none">importe</th>
                                <th style="display: none">incentivos</th>
                                <th style="display: none"></th>
                            </tr>
                            </tfoot>
                        </table>
                        {{-- FINI: TABLA --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Incluimos la ventana modal --}}
    @include('pagos.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('pagos.scripts')
@stop
