@extends('layouts.theme.app')

@section('estilos')
    {{-- Incluimos los estilos propios de la pagina --}}
    {{--    <link href="{{asset('assets/css/components/custom-carousel.css')}}" rel="stylesheet" type="text/css" />--}}
    <link href="{{asset('assets/css/components/cards/card.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
    {{--    PANELES DE GERENCIA--}}
    <div class="row mt-5 mb-5">
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/panel de control.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Panel de control</h5>
                    <p class="card-text">Cuadro de mando en tiempo real </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="{{route('gratificaciones')}}">
                    <img src="{{asset('assets/img/gratificaciones.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Gratificaciones</h5>
                    <p class="card-text">Gratificaciones mensuales</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="{{route('adelantos')}}">
                    <img src="{{asset('assets/img/adelantos.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Adelantos</h5>
                    <p class="card-text">Adelantos de nóminas</p>
                </div>
            </div>
        </div>
    </div>
    {{--    TABLAS DEL SISTEMA--}}
    <div class="row mt-4">
        <div class="col-lg-2">
            <div class="card component-card_7">
                <a href="{{'delegaciones'}}">
                    <div class="card-body">
                        <div class="icon-svg">
                            @include('layouts.svg.delegaciones')
                        </div>
                        <h5 class="card-title">Delegaciones</h5>
                        <p class="card-text">Nuestras delegaciones </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card component-card_7">
                <a href="{{'pagos'}}">
                    <div class="card-body">
                        <div class="icon-svg">
                            @include('layouts.svg.pagos')
                        </div>
                        <h5 class="card-title">Pagos</h5>
                        <p class="card-text">Pagos a los empleados </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card component-card_7">
                <a href="{{'pluses'}}">
                    <div class="card-body">
                        <div class="icon-svg">
                            @include('layouts.svg.pluses')
                        </div>
                        <h5 class="card-title">Pluses</h5>
                        <p class="card-text">Pluses en los servicios </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card component-card_7">
                <a href="{{'empresas'}}">
                    <div class="card-body">
                        <div class="icon-svg">
                            @include('layouts.svg.empresas')
                        </div>
                        <h5 class="card-title">Empresas</h5>
                        <p class="card-text">Empresas de la app </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card component-card_7">
                <a href="{{'formasPago'}}">
                    <div class="card-body">
                        <div class="icon-svg">
                            @include('layouts.svg.formaspago')
                        </div>
                        <h5 class="card-title">Formas de Pago</h5>
                        <p class="card-text">Pagos de clientes </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card component-card_7">
                <a href="{{'sectores'}}">
                    <div class="card-body">
                        <div class="icon-svg">
                            @include('layouts.svg.sectores')
                        </div>
                        <h5 class="card-title">Sectores</h5>
                        <p class="card-text">Sectores empresariales </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
