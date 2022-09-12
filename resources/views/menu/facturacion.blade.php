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
                <a href="{{route('clientes')}}">
                    <img src="{{asset('assets/img/clientes.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text">Gestión de clientes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="{{route('servicios')}}">
                    <img src="{{asset('assets/img/servicios.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Servicios</h5>
                    <p class="card-text">Gestión de los servicios</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/facturas.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Facturas</h5>
                    <p class="card-text">Gestión y control de facturas</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/cobros.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Cobros</h5>
                    <p class="card-text">Gestión y control de cobros</p>
                </div>
            </div>
        </div>
    </div>
    {{--    2º GRUPO DE PANELES --}}
    <div class="row mt-4">
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/talones.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Talones</h5>
                    <p class="card-text">Gestión de talones </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/facturacion.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Facturación</h5>
                    <p class="card-text">Proceso de facturación mensual</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card component-card_2">
                <a href="#">
                    <img src="{{asset('assets/img/cierre.jpeg')}}" class="card-img-top" alt="widget-card-2">
                </a>
                <div class="card-body">
                    <h5 class="card-title">Cierre</h5>
                    <p class="card-text">Proceso de cierre mensual</p>
                </div>
            </div>
        </div>
    </div>
@endsection
