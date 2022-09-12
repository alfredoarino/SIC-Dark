<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{asset('assets/css/loader.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('assets/js/loader.js')}}"></script>

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
<link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/structure.css')}}" rel="stylesheet" type="text/css" class="structure" />
<link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->

{{-- Incluimos los estilos del TOASTR--}}
@toastr_css

<!-- BEGIN SWEET ALERT -->
<link href="{{asset('plugins/animate/animate.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
<!-- END SWEET ALERT -->

<!-- BEGIN MAILBOX (información del registro y del servicio)-->
<link href="{{asset('assets/css/apps/mailbox.css')}}" rel="stylesheet" type="text/css" />
<!-- END MAILBOX (información del registro y del servicio)-->

<!-- BREADCRUMBS (migas de pan)-->
<link href="{{asset('assets/css/elements/breadcrumb.css')}}" rel="stylesheet" type="text/css" />
<!-- END BREADCRUMBS (migas de pan)-->

{{-- INICIO: Estilos propios del proyecto--}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
    aside{
        display: none !important;
    }
    .page-item.active .page-link{
        z-index: 3;
        color: #fff;
        background-color: #3b3f5c;
        border-color: #3b3f5c;
    }
    @media (max-width: 480px) {
        .mtmobile {
            margin-bottom: 20px!important;
        }
        .mbmobile {
            margin-bottom: 10px!important;
        }
        .hideonsm {
            display: none!important;
        }
        .inblock {
            display: block;
        }
    }

    /*!* Ancho de la SIDEBAR*!*/
    /*.sidebar-wrapper #compactSidebar{*/
    /*!* Anchura   *!*/
    /*    width: 55px;!important;*/
    /*}*/
    /*.sidebar-wrapper #compactSidebar .menu-categories a.menu-toggle{*/
    /*    height: 50px;*/
    /*}*/
    /*    !* Color del SIDEBAR *!*/
    /*.sidebar-theme #compactSidebar {*/
    /*    background: #aaaab1 !important;*/
    /*    !*background: #58585c !important;*!*/
    /*}*/

    /* Color del HEADER - Colapse */
    .header-container .sidebarCollapse {
        color: #dedcdc!important;
    }
    /* usuario logeado*/
    .navbar .navbar-item .nav-item.user-profile-dropdown .dropdown-menu .user-profile-section {
        padding: 16px 14px;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        margin-right: -12px;
        margin-left: -12px;
        background: #3765ee!important;
        margin-top: -1px;
        background-image: linear-gradient(to top, #5776cb 0%, #3c56ab 52%, #2a39a4 100%);
    }

    /* Color de los círculos de la paginación    */
    .page-item.active .page-link {
        background-color: #191e3a !important;
    }
    /* Color del texto del número de página*/
    /*div.dataTables_wrapper div.dataTables_info {*/
    /*    color: #191e3a !important;*/
    /*}*/

    /* Cambiamos el color de los botones*/
    .btn-primary {
        color: #FFFFFF !important;
        background-color: #3751e3 !important;
        border-color: #3751e3 !important;
    }
    .btn-outline-primary {
        color: #000000 !important;
        background-color: #e5e1e1 !important;
        border-color: #e5e1e1 !important;
    }
    .btn-danger {
        color: #fff !important;
        background-color: #d68d96;
        border-color: #D68D96;
    }
</style>
{{-- FIN: Estilos propios del proyecto--}}
