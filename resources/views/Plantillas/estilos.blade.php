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

    .tabla{
        table-layout: fixed;
        width: 95%;
        margin-left: 30px;
    }

    .horarios {
        position: relative;
        padding: 20px;
        border-radius: 8px;
        border: none;
        background: #575860;
        box-shadow: 0 4px 6px 0 rgb(85 85 85 / 8%), 0 1px 20px 0 rgb(0 0 0 / 7%), 0px 1px 11px 0px rgb(0 0 0 / 7%);
    }

    .rectangulo {
        display: table;
        width: 100%;
        height: 100%;
        border-radius: 8px;
        border: none;
        background: #ffffffff;
        box-shadow: 0 4px 6px 0 rgb(85 85 85 / 8%), 0 1px 20px 0 rgb(0 0 0 / 7%), 0px 1px 11px 0px rgb(0 0 0 / 7%);
    }
    .rectangulo:hover {
        background-color: #005dd4;
        /*background-color:  #a9c8f0;*/
        color: #ffecec;
        cursor: pointer;
    }
    .horario{
        font-size: 11px;
    }
    /*.seleccion{*/
    /*    display: inline-flex;*/
    /*    justify-content: space-between;*/
    /*    width: 50%;*/
    /*    !*flex-wrap: wrap;*!*/
    /*}*/
    /*.horarios{*/
    /*    display: inline-flex;*/
    /*    justify-content: space-between;*/
    /*    width: 50%;*/
    /*    !*flex-wrap: wrap;*!*/
    /*}*/
    .servicio{
        padding: 10px;
        width: 500px;
    }
    /*.botones{*/
    /*    display: inline;*/
    /*    margin-top: 10px;*/
    /*    padding: 10px;*/
    /*    width: 400px;*/
    /*}*/
</style>
{{-- FIN DE LOS ESTILOS PROPIOS DE LA PAGINA--}}
