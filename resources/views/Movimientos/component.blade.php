@extends('layouts.theme.app')
@section('estilos')
    {{-- ESTILOS PROPIOS DE LA PÁGINA --}}
    @include('Movimientos.estilos')
@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                            <h3>Movimientos</h3>--}}
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
{{--                    <div class="servicio" style="max-width: 750px">--}}
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="servicio">
                            {{-- SERVICIOS --}}
                            <label for="servicio_id"><strong>Servicio</strong></label>
                            <select class="placeholder js-states form-control" id="servicio_id" style="max-width: 750px">
                                <option value="">Servicio</option>
                                @foreach($servicios as $s)
                                    <option value="{{$s->id}}">{{$s->numero}} - {{$s->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="botones">
                            <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_copiar">Copiar movimientos</button>
                        </div>
                    </div>
                </div>
                <br>
                {{-- DIV de el grafico de carga  --}}
                <div class="d-flex justify-content-center mx-5 mt-3">
                    <div id="cargando" class="spinner-border text-info align-self-center loader-lg">Loading...</div>
{{--                    <div class="spinner-grow text-info align-self-center loader-lg" id="cargando">Loading...</div>--}}
                </div>

                {{-- DIV donde se aloja el calandario  --}}
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    {{-- Incluimos la ventana modal --}}
    @include('Movimientos.form')
    @include('Movimientos.informacion')
    @include('Movimientos.copia')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Movimientos.scripts')
@stop
