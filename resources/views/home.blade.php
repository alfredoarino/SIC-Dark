@extends('layouts.theme.app')

@section('estilos')
    {{-- Incluimos los estilos propios de la pagina --}}
{{--    <link href="{{asset('assets/css/components/custom-carousel.css')}}" rel="stylesheet" type="text/css" />--}}
    <link href="{{asset('assets/css/components/cards/card.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
    <div class="row mt-5">
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('gestion')}}">
                    <img src="{{asset('assets/img/gestion.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Gestión</h5>
                    <p class="card-text">Módulo de gestión de la empresa y control de datos del sistema </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('facturacion')}}">
                    <img src="{{asset('assets/img/facturacion.jpg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Facturación</h5>
                    <p class="card-text">Módulo para el control de facturas, cobros, clientes y servicios.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('rrhh')}}">
                    <img src="{{asset('assets/img/rrhh.jpg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">RRHH</h5>
                    <p class="card-text">Módulo para la gestión de empleados, vacaciones y liquidación</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('inspeccion')}}">
                    <img src="{{asset('assets/img/inspeccion.jpg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Inspección</h5>
                    <p class="card-text">Módulo para la gestión de los trabajos realizados</p>
                </div>
            </div>
        </div>

@endsection
