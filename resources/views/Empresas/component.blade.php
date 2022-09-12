@extends('layouts.theme.app')
@section('estilos')

    {{-- ESTILOS PROPIOS DE LA PAGINA --}}
    @include('empresas.estilos')

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
    {{--                             <h3>Empresas</h3>--}}
                            </div>
                        </div>
                    </div>
                    <div class="float-lg-right">
                        <button class="btn btn-primary mr-2" type="button" id="btn_add">Añadir empresa</button>
                    </div>
                    <br>
                    <div class="table-responsive mb-4">
                        {{-- INICIO: TABLA --}}
                        <table id="tabla_empresas" class="tabla_datos table table-hover">
                            <thead class="bg-sgls">
                                <tr>
                                    <th class="text-center text-dark" style="display: none">id</th>
                                    <th class="text-center text-dark" style="text-transform: none">Nombre</th>
                                    <th class="text-center text-dark" style="text-transform: none">Código</th>
                                    <th class="text-center text-dark" style="text-transform: none">CIF</th>
                                    <th class="text-center text-dark" style="width: 150px;text-transform: none">Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="display: none"></th>
                                    <th style="display: none">nombre</th>
                                    <th style="display: none">codigo</th>
                                    <th style="display: none">cif</th>
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
    @include('empresas.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('empresas.scripts')
@stop
