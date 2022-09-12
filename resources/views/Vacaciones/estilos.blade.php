<!-- BEGIN STYLES TABLES -->
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/custom_dt_miscellaneous.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/dt-global_style.css')}}">
<!-- END STYLES TABLES -->

<!-- BEGIN STYLES TEXTO MODAL -->
<link rel="stylesheet" type="text/css" href="{{asset('plugins/editors/quill/quill.snow.css')}}">
<link href="{{asset('assets/css/apps/todolist.css')}}" rel="stylesheet" type="text/css" />
<!-- END STYLES TEXTO MODAL -->

<!-- BEGIN STYLES SELECT 2 -->
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- END STYLES SELECT 2 -->

{{-- INICIO DE LOS ESTILOS PROPIOS DE LA PAGINA--}}
<style>
    {{-- INICIO: COLOCACIÓN DE LA SELECCION DE MES/AÑO Y EL BOTÓN DE AÑADIR AYUDAS--}}
    /*#contenedor {*/
    /*display: table;*/
    /*width: 100%;*/
    /*}*/

    /*#contenedor > div {*/
    /*display: table-cell;*/
    /*width: 45%;*/
    /*}*/

    /*#contenedor > div:nth-child(2) {*/
    /*width: 10%;*/
    /*}*/

    .seleccion{
        display: inline-flex;
        justify-content: space-between;
        width: 100%;
        /*flex-wrap: wrap;*/
    }
    .anualidad{
        padding: 10px;
        width: 200px;
    }
    .empleado{
        padding: 10px;
        width: 500px;
    }
    .botones{
        display: inline;
        margin-top: 10px;
        padding: 10px;
        width: 400px;
    }
    {{-- FIN: COLOCACIÓN DE LA SELECCION DE MES/AÑO Y EL BOTÓN DE AÑADIR AYUDAS--}}
</style>
{{-- FIN DE LOS ESTILOS PROPIOS DE LA PAGINA--}}
