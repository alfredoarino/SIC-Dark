@extends('layouts.theme.app')

@section('estilos')
    {{-- Incluimos los estilos propios de la pagina --}}
    {{--    <link href="{{asset('assets/css/components/custom-carousel.css')}}" rel="stylesheet" type="text/css" />--}}
    <link href="{{asset('assets/css/components/cards/card.css')}}" rel="stylesheet" type="text/css" />
@stop


@section('content')
    {{--    1ER GRUPO DE PANELES --}}
    <div class="row mt-5">
        <div class="col-lg-4">
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
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="{{route('ayudas')}}">
                    <img src="{{asset('assets/img/ayudas.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Ayudas</h5>
                    <p class="card-text">Ayudas fondo social</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="{{route('vacaciones')}}">
                    <img src="{{asset('assets/img/vacaciones.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Vacaciones</h5>
                    <p class="card-text">Gestión vacaciones</p>
                </div>
            </div>
        </div>
    </div>
    {{--    2º GRUPO DE PANELES --}}
    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/prevision.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Previsión liquidación</h5>
                    <p class="card-text">Cálculo provisional </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/kilometraje.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Kilometraje</h5>
                    <p class="card-text">Cálculo del kilometraje</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/liquidacion.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Liquidación</h5>
                    <p class="card-text">Proceso de liquidación</p>
                </div>
            </div>
        </div>
    </div>
@endsection
