<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SIC</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!--INICIO: INLCLUIMOS LOS ESTILOS DE LA PAGINA    -->
    @include('layouts.theme.estilos')
    <!--FIN: INLCLUIMOS LOS ESTILOS DE LA PAGINA    -->

    {{-- INICIO: PONEMOS LA SECCION DE ESTILOS PROPIOS DE CADA PAGINA--}}
    @yield('estilos')
    {{-- FIN: PONEMOS LA SECCION DE ESTILOS PROPIOS DE CADA PAGINA--}}
</head>
<body class="dashboard-analytics">

<!-- BEGIN LOADER -->
<div id="load_screen">
    <div class="loader">
        <div class="loader-content">
            <div class="spinner-grow align-self-center">
            </div>
        </div>
    </div>
</div>
<!--  END LOADER -->

<!--  BEGIN NAVBAR  -->
{{--@include('layouts.theme.header')--}}
<!--  END NAVBAR  -->
<!--  BEGIN TOPBAR  -->
@include('layouts.theme.topBar')
<!--  END TOPBAR  -->

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container sidebar-closed sbar-open" id="container">

    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->
{{--    @include('layouts.theme.sidebar')--}}
    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            @yield('content')
        </div>
        <!--  BEGIN FOOTER (ponemos el pie si la página es home) -->
        @if (Route::currentRouteName() === 'home')
            @include('layouts.theme.footer')
        @endif
        <!--  END FOOTER  -->
    </div>
    <!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->

<!--INICIO: INCLUIMOS LOS SCRIPTS    -->
@include ('layouts.theme.scripts')
<!--FIN: INCLUIMOS LOS SCRIPTS    -->
</body>
{{-- INICIO: PONEMOS LA SECCION DE SCRIPTS PROPIOS DE CADA PAGINA--}}
@yield('scripts')
{{-- FIN: PONEMOS LA SECCION DE SCRIPTS PROPIOS DE CADA PAGINA--}}
</html>
