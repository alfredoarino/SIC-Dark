@extends('layouts.theme.app')
@section('estilos')
    {{-- ESTILOS PROPIOS DE LA PÁGINA --}}
      @include('Ayudas.estilos')
@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                             <h3>Ayudas</h3>--}}
                        </div>
                    </div>
                </div>
                <br>
                <div class="seleccion" >
                    <div class="periodo">
                        <label for="periodo"><strong>Periodo</strong></label>
                        <input type="month" class="form-control" id="periodo">
                    </div>
                    <div></div>
                    <div class="botones">
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_print">Imprimir ayudas</button>
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_add">Añadir ayudas</button>
                    </div>
                </div>
                <br>
                <div id ="tabla_" class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_ayudas" class="tabla_datos table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="width: 80px;text-transform: none">Número</th>
                                <th class="text-center text-dark" style="text-transform: none">Nombre</th>
                                <th class="text-center text-dark" style="text-transform: none">Apellidos</th>
                                <th class="text-center text-dark" style="text-transform: none">Gas.</th>
                                <th class="text-center text-dark" style="text-transform: none">Juz.</th>
                                <th class="text-center text-dark" style="text-transform: none">B.enf.</th>
                                <th class="text-center text-dark" style="text-transform: none">B.acc.</th>
                                <th class="text-center text-dark" style="text-transform: none">Ins.</th>
                                <th class="text-center text-dark" style="text-transform: none">Min.</th>
                                <th class="text-center text-dark" style="text-transform: none">Otros</th>
                                <th class="text-center text-dark" style="text-transform: none;width: 150px">Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th>numero</th>
                                <th>nombre</th>
                                <th>apellidos</th>
                                <th>Ga</th>
                                <th>Ju</th>
                                <th>Be</th>
                                <th>Ba</th>
                                <th>In</th>
                                <th>Mi</th>
                                <th>Ot</th>
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
    @include('Ayudas.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Ayudas.scripts')
@stop
