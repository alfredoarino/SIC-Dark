@extends('layouts.theme.app')
@section('estilos')
    {{-- ESTILOS PROPIOS DE LA PÁGINA --}}
      @include('Generacion.estilos')
@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
                             <h3>Generación automática de movimientos</h3>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-12 layout-spacing">
                            <label >Fecha inicio</label>
                            <input id="fechaInicio" class="form-control flatpickr active" type="text" placeholder="">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-12 layout-spacing">
                            <label >Fecha fin</label>
                            <input id="fechaFin" class="form-control flatpickr active" type="text" placeholder="">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 layout-spacing mt-2">
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_mostrar">Mostrar plantillas</button>
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_generar">Generar</button>
                    </div>
                </div>
                <br>
                <div id ="tabla" class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_plantilla" class="tabla table  mb-4">
                        <thead>
                            <tr>
                                <th class="text-center text-white" style="max-width:200px;background: #b9b9b9 ;text-transform: none">Número</th>
                                <th class="text-center text-white" style="background: #b9b9b9 ;text-transform: none">Nombre</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- FINI: TABLA --}}
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Generacion.scripts')
@stop
