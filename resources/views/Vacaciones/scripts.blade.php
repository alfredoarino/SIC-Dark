{{-- INICIO DATATABLES--}}
<script src="{{asset('plugins/table/datatable/datatables.js')}}"></script>
<script src="{{asset('plugins/table/datatable/custom_miscellaneous.js')}}"></script>
{{-- FIN DATATABLES--}}

{{-- INICIO SELECT 2 --}}
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('plugins/select2/custom-select2.js')}}"></script>
{{-- FIN SELECT 2 --}}

{{-- INICIO MOMENT JS --}}
<script src="{{asset('plugins/fullcalendar/moment.min.js')}}"></script>
{{-- FIN MOMENT JS --}}

{{--***********************--}}
{{--INICIO: SCRIPTS PROPIOS--}}
{{--***********************--}}
<script type="text/javascript">

    //Variables globales
    let tabla;                  //Tabla principal de datos
    let anualidad ;
    let empleado_id;

    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        // //Cambiamos el indicador del menú que está seleccionado
        // $('#menuHome').removeClass('active');
        // $('#menuAdministracion').addClass('active');
        // $('#menuContabilidad').removeClass('active');
        // $('#menuGerencia').removeClass('active');
        // $('#menuInspeccion').removeClass('active');
        // $('#menuSoporte').removeClass('active');

        //Ocultamos el DIV de la tabla hasta que se seleccione el mes y año
        //y el botón de añadir vacaciones
        $('#tabla_').hide();
        $('#btn_add').hide();
        // $('#btn_print').hide();

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los empleados
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let se = $("#empleado_id").select2({
            placeholder: "Elegir empleado",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los años
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sa = $("#anualidad").select2({
            placeholder: "Elegir anualidad",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        //Editar el registro
        $('#tabla_vacaciones tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la función para obtener los datos via Ajax
            editar(data.id);
        });

        //Borrar el registro
        $('#tabla_vacaciones tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el periodo de vacaciones!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    //Llamamos a la funcion para eliminarlo via Ajax
                    eliminar(data.id);
                }
            })
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Selección EMPLEADO
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('change', '#empleado_id', function () {
            //Llamamos a la función que mostrará las vacaciones si se ha seleccionado el empleado
            muestraVacaciones();
        });
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Selección ANUALIDAD
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('change', '#anualidad', function () {
            //Llamamos a la función que mostrará las vacaciones si se ha seleccionado el empleado
            muestraVacaciones();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón añadir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_add', function () {
            //Llamamos a la funcion de nuevo registro
            nuevo();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón imprimir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_print', function () {
            //Llamamos a la funcion de imprimir los registros
            let periodo =  $('#periodo').val();
            //Montamos el texto de la ruta
            let url = '{{ route ("vacaciones.imprimir", ['periodo' => "perxx"]) }}';
            url = url.replace('perxx',periodo);
            //llamamos a la ruta
            window.location.href=url;
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón del volver (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_volver', function () {
            //Ocultamos el modal
            $('#form').modal('hide');
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón salvar (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_salvar', function () {
            //Llamamos a la función que nos valida si los campos
            if (validaCamposModal()) {
                //Grabamos el registro
                grabaRegistro();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Cambios de la fecha de inicio y fin
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('change', ['#fecha_inicio','#fecha_fin'], function () {
            //Llamamos a la función que mostrará las vacaciones si se ha seleccionado el empleado
            calculaDias();
        });

    });
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // FIN: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<


    /*********************/
    /* INICIO: FUNCIONES */
    //********************/

    //********************************************************************/
    // MUESTRAVACACIONES: función para mostrar la lista de las vacaciones /
    //********************************************************************/
    function muestraVacaciones() {
        //Comprobamos si se ha seleccionado un año y un empleado
        if ($('#anualidad').val() != '' && $('#empleado_id').val() != '') {
            //Obtenemos los datos de la selección
            anualidad = $('#anualidad').val();
            empleado_id = $('#empleado_id').val();
            /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Inicialización de la tabla de las VACACIONES
            >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            tabla = $('#tabla_vacaciones').dataTable({
                'destroy': true,
                'processing': true,
                'responsive': true,
                'autoWidth': false,
                "dom": '<"top"l>rt<"row"<"bottom col-sm-4"i><"col-sm-4 text-center"p>>',   // Elemento de la tabla - quitamos la búsqueda general (ver DOM en la pagina oficial)
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                    "sLengthMenu": "Filas :  _MENU_",
                },
                initComplete: function () {
                    // Para los campos de búsquedas
                    this.api().columns().every(function () {
                        let that = this;

                        $('input', this.footer()).on('keyup change clear', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
                },
                'ajax': {
                    url: "{{route('vacaciones.lista')}}",
                    data: {'anualidad': anualidad,'empleado_id':empleado_id},
                    type: "get",
                    dataSrc: ""
                },
                'columns': [
                    {data: 'id'},
                    {data: 'numero'},
                    {data: 'nombre'},
                    {data: 'apellidos'},
                    {
                        data: 'fecha_inicio', render: function (data) {
                            return data.substr(8,2)+'-'+data.substr(5,2)+'-'+data.substr(0,4);
                        }
                    },
                    {
                        data: 'fecha_fin', render: function (data) {
                            return data.substr(8,2)+'-'+data.substr(5,2)+'-'+data.substr(0,4);
                        }
                    },
                    {data: 'dias'},
                    {
                        defaultContent: `<button class="editar btn btn-primary mb-2 mr-2" type="button">
                                                @include("layouts.svg.editar")
                                         </button>
                                         <button class="eliminar btn btn-primary mb-2 mr-2" type="button">
                                                @include("layouts.svg.borrar")
                                         </button>`
                    },
                ],
                'columnDefs': [
                    {"targets": 0, "visible": false},        //Id del registro (oculto)
                    {"targets": [1, 2, 3, 4, 5, 6], "className": "text-center"},
                ],
                "order": [4, "asc"],
                'stripeClasses': [],
                'lengthMenu': [3, 5],
                'pageLength': 3,
            });
            //Mostramos el DIV de la tabla y el botón de añadir vacaciones
            $('#tabla_').show();
            $('#btn_add').show();
            $('#btn_print').show();
        }
        else{
            //si la anualidad no está vacía, dejamos el botón de imprimir las vacaciones
            if ($('#anualidad').val()==''){
                $('#btn_print').hide();
            }
            else{
                $('#btn_print').show();
            }
            //Ocultamos el DIV de la tabla y el botón de añadir vacaciones
            $('#tabla_').hide();
            $('#btn_add').hide();
            // $('#btn_print').hide();
        }
    }

    //*************************************************/
    // NUEVO: función para gestionar un nuevo registro /
    //*************************************************/
    //Preparamos los campos y mostramos la ventana modal
    function nuevo(){
        //Limpiamos los campos de la ventana
        limpiaCampos();
        //Mostramos la ventana modal
        $('#form').modal('show');
    }
    //***************************************************/
    // EDITAR: función para preparar la edición registro /
    //***************************************************/
    //Localiza el registro en la BD, llama a la función de cargar los campos de la ventana y
    //muestra la ventana modal
    function editar(id){
            let url_ = "{{ route('vacaciones.buscarRegistro') }}"
            $.ajax({
                data:  {"id":id},
                url: url_,
                type:  'get',
                success:  function (response) {
                    //Cargamos los datos obtenidos en los campos del modal
                    cargaDatos(response);
                    //Mostramos la ventana modal
                    $('#form').modal('show');
                },
            });
    }
    //**********************************************************/
    // ELIMINAR: función para eliminar un registro seleccionado /
    //**********************************************************/
    // Elimina el registro seleccionado
    function eliminar(id){
            $.ajax({
                data:  {'_token': '{{ csrf_token() }}',"id":id},
                url: '{{ route('vacaciones.eliminar') }}',
                type:  'post',
                success:  function (response) {
                    //Actualizamos los datos de la tabla
                    tabla.api().ajax.reload( null, false );
                    //Mostramos el mensaje de que se ha borrado el registro
                    toastr.success('El registro se ha eliminado');
                },
            });
    }
    //*******************************************************************/
    // GRABAREGISTRO: función para gestionar la grabación de un registro /
    //*******************************************************************/
    function grabaRegistro(){
        //Función para la grabación del registro (update o insert, en función de lo solicitado
        let url;
        if ($('#id_').val()===''){
            url = "{{ route('vacaciones.nuevo') }}"
        }
        else {
            url = "{{ route('vacaciones.actualizar') }}"
        }
        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
                    'id':$('#id_').val(),
                    'anualidad':anualidad,
                    'empleado_id':empleado_id,
                    'fecha_inicio':$('#fecha_inicio').val(),
                    'fecha_fin':$('#fecha_fin').val(),
                    'dias':$('#dias').val(),
        };
        $.ajax({
            data:  data,
            url: url,
            type:  'post',
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla.api().ajax.reload( null, false );
                //Mostramos el mensaje de que se ha actualizado o insertado el registro
                if ($('#id_').val()==''){
                    //Sacamos mensaje
                    toastr.success('El registro se ha insertado');
                    //Limpiamos los campos para que se puedan seguir grabando más vacaciones
                    limpiaCampos();
                }
                else {
                    //Sacamos mensaje
                    toastr.success('El registro se ha actualizado');
                    //Ocultamos el modal
                    $('#form').modal('hide');
                }
            },
        });
    }

    //********************************************************************/
    // CARGADATOS: función para mostrar los datos obtenidos en los campos /
    //********************************************************************/
    //Función colocar los datos obtenidos en los campos de la ventana
    function cargaDatos(response){
        $('#id_').val(response['id']);
        $('#empleado_id').val(response['empleado_id']).trigger('change.select2');
        $('#fecha_inicio').val(response['fecha_inicio']);
        $('#fecha_fin').val(response['fecha_fin']);
        $('#dias').val(response['dias']);
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');

    }
    //****************************************************************/
    // LIMPIACAMPOS: función para mostrar la lista de las vacaciones /
    //***************************************************************/
    //Funcion para limpiar los campos de la ventana
    function limpiaCampos(){
        //Montamos el título de la ventana
        let data = $('empleado_id').select2('data');
        $('#titulo').html('Añadir vacaciones de la anualidad '+$('#anualidad').val());
        //Limpiamos los campos de la ventana
        $('#id_').val('');
        $('#fecha_inicio').val('');
        $('#fecha_fin').val('');
        $('#dias').val('');
        //Ponemos el texto del botón 'grabar'
        $('#btn_salvar').html('Grabar');
    }

    //************************************************************************/
    // VALIDACAMPOSMODAL: función para validar los campos de la ventana modal /
    //************************************************************************/
    //Valida los campos de la ventana MODAL
    function validaCamposModal(){
        //Variable auxiliar
        let ret = true;
        let fecha_inicio;
        let fecha_fin;
        let anualidad = $('#anualidad').val();

        //FECHA_INICIO (obligatorio)
        if ($('#fecha_inicio').val()==''){
            toastr.error('Se tiene que introducir una fecha de inicio');
            $('#fecha_inicio').focus();
            ret = false;
        }
        else{
            fecha_inicio = moment($('#fecha_inicio').val());
            //Vemos si la fecha es valida
            if (fecha_inicio.isValid()){
                //Comprobamos si la fecha esta dentro de la anualidad seleccionada
                if (! fecha_inicio.isBetween(anualidad+'-01-01',anualidad+'-12-31')){
                    toastr.error('La fecha tiene que ser de la anualidad '+anualidad);
                    $('#fecha_inicio').focus();
                    ret = false;
                }
            }
            else{
                toastr.error('La fecha no es válida');
                $('#fecha_inicio').focus();
                ret = false;
            }
        }
        //FECHA_FIN (obligatorio)
        if ($('#fecha_fin').val()==''){
            toastr.error('Se tiene que introducir una fecha final');
            $('#fecha_fin').focus();
            ret = false;
        }
        else{
            fecha_fin = moment($('#fecha_fin').val());
            //Vemos si la fecha es valida
            if (!fecha_fin.isValid()){
                toastr.error('La fecha no es válida');
                $('#fecha_fin').focus();
                ret = false;
            }
        }
        //Comprobamos que hay más de un día de vacaciones solicitadas
        //comprobando así que la fecha de inicio es menor que la fecha final
        if ( ret && $('#dias').val() <= 0){
            toastr.error('la fecha de inicio tiene que ser menor o igual que la fecha final');
            $('#fecha_fin').focus();
            ret = false;
        }

        //Devolvemos el valor de retorno
        return ret;
    }

    //************************************************************/
    // CALCULADIAS: función para calcular los días seleccionados  /
    //************************************************************/
    function calculaDias() {
        //Obtenemos las fechas
        let dias = 0;
        //Comprobamos si tienen datos las dos fechas
        if ($('#fecha_inicio').val() != '' && $('#fecha_fin').val() != ''){
            let fecha_inicio = moment($('#fecha_inicio').val());
            let fecha_fin = moment($('#fecha_fin').val());
            //Calculamos los días de vacaciones y añadimos uno porque siempre es ambos inclusive
            dias = fecha_fin.diff(fecha_inicio,'days')+1;
        }
        else{
            dias = '';
        }
        //Mostramos el total en el campo de salida
        $('#dias').val(dias);
    }

    //*******************************************************/
    // IMPRIMIR: función para la impresión de las vacaciones /
    //*******************************************************/
    function imprimir(){
        let periodo = $('#periodo').val();
        $.ajax({
            data:  {"periodo":periodo},
            url: '{{ route('vacaciones.imprimir') }}',
            type:  'get',
            success:  function (response) {
                //No hay acciones que tomar en esta función
            },
        });
    }
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
           FIN: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
</script>
{{--***********************--}}
{{--FIN: SCRIPTS PROPIOS   --}}
{{--***********************--}}
