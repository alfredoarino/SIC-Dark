@extends('layouts.theme.app')

@section('estilos')
    {{-- Incluimos los estilos propios de la pagina --}}
    {{--    <link href="{{asset('assets/css/components/custom-carousel.css')}}" rel="stylesheet" type="text/css" />--}}
    <link href="{{asset('assets/css/components/cards/card.css')}}" rel="stylesheet" type="text/css" />
@stop


@section('content')
    {{--    1ER GRUPO DE PANELES --}}
    <div class="row mt-5">
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('plantillas')}}">
                    <img src="{{asset('assets/img/plantillas.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Plantillas</h5>
                    <p class="card-text">Plantillas de trabajos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/generacion.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Generación</h5>
                    <p class="card-text">Generación automática</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('movimientos')}}">
                    <img src="{{asset('assets/img/movimientos.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Movimientos</h5>
                    <p class="card-text">Movimientos mensuales</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/cuadrantes.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Cuadrantes</h5>
                    <p class="card-text">Impresión de cuadrantes</p>
                </div>
            </div>
        </div>
    </div>
    {{--    2º GRUPO DE PANELES --}}
    <div class="row mt-4">
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('servicios')}}">
                    <img src="{{asset('assets/img/servicios.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Servicios</h5>
                    <p class="card-text">Gestión de servicios</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('empleados')}}">
                    <img src="{{asset('assets/img/empleados.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Empleados</h5>
                    <p class="card-text">Gestión de empleados</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/vestuario.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Vestuario</h5>
                    <p class="card-text">Control del vestuario</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/vales.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Vales</h5>
                    <p class="card-text">Vales de uniformidad</p>
                </div>
            </div>
        </div>
    </div>
@endsection
