@extends('layouts.theme.app')
@section('estilos')

    {{-- ESTILOS PROPIOS DE LA PAGINA --}}
    @include('Clientes.estilos')

@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                             <h3>Clientes</h3>--}}
                        </div>
                    </div>
                </div>
                <div class="float-lg-right">
                    <button class="btn btn-primary mr-2" type="button" id="btn_add">Añadir cliente</button>
                </div>
                <br>
                <div class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_clientes" class="tabla_datos table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="width: 700px;text-transform: none">Razón Social</th>
                                <th class="text-center text-dark" style="text-transform: none">CIF</th>
                                <th class="text-center text-dark" style="width: 80px;text-transform: none">Delegación</th>
                                <th class="text-center text-dark" style="width: 80px;text-transform: none">Empresa</th>
                                <th class="text-center text-dark" style="text-transform: none">Teléfono</th>
                                <th class="text-center text-dark" style="width: 150px;text-transform: none">Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th>razonsocial</th>
                                <th>cif</th>
                                <th>delegacion_id</th>
                                <th>empresa_id</th>
                                <th>telefono</th>
                                <th style="display: none"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- FIN: TABLA --}}
                </div>
            </div>
        </div>
    </div>
    {{-- Incluimos las ventanas modal --}}
    @include('Clientes.form')
    @include('Clientes.ContactosCliente.form')
    @include('Clientes.AccionesCliente.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Clientes.scripts')
    @include('Clientes.ContactosCliente.scripts')
    @include('Clientes.AccionesCliente.scripts')
@stop
