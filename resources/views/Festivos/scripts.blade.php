{{-- INICIO DATATABLES--}}
<script src="{{asset('plugins/table/datatable/datatables.js')}}"></script>
<script src="{{asset('plugins/table/datatable/custom_miscellaneous.js')}}"></script>
{{-- FIN DATATABLES--}}

{{-- INICIO SELECT 2 --}}
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('plugins/select2/custom-select2.js')}}"></script>
{{-- FIN SELECT 2 --}}

{{--***********************--}}
{{--INICIO: SCRIPTS PROPIOS--}}
{{--***********************--}}
<script type="text/javascript">

    //Variables globales
    let tabla;                  //Tabla principal de datos

    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        //Cambiamos el indicador del menú que está seleccionado
        $('#menuHome').removeClass('active');
        $('#menuAdministracion').removeClass('active');
        $('#menuContabilidad').removeClass('active');
        $('#menuGerencia').removeClass('active');
        $('#menuInspeccion').removeClass('active');
        $('#menuSoporte').addClass('active');

        //Ocultamos el DIV de la tabla hasta que se seleccione el mes y año
        //y el botón de añadir festivos
        $('#tabla_').hide();
        $('#btn_add').hide();
        $('#btn_print').hide();

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los empleados
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sa = $("#anualidad").select2({
            placeholder: "Elegir anualidad",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de las delegación. Selección simple
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sd = $("#delegacion_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir delegacion",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de las delegaciones. Selección multiple
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sdd = $("#delegaciones").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir delegación/es",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        //Editar el registro
        $('#tabla_festivos tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la función para obtener los datos via Ajax
            editar(data.id);
        });

        //Borrar el registro
        $('#tabla_festivos tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el festivo!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    //Llamamos a la función para eliminarlo via Ajax
                    eliminar(data.id);
                }
            })
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Selección MES/AÑO
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('change', '#anualidad', function () {
            //Siempre que estén relleno el mes y el año
            if ( $('#anualidad').val() != '' ){
                // Llamamos a la función que mostrará las festivos del mes y año seleccionados
                muestraFestivos();
            }
            else {
                //Ocultamos los botones y el id de la tabla
                $('#tabla_').hide();
                $('#btn_add').hide();
                $('#btn_print').hide();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón añadir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_add', function () {
            //Llamamos a la función de nuevo registro
            nuevo();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón imprimir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_print', function () {
            //Llamamos a la funcion de imprimir las festivos
            let periodo =  $('#periodo').val();
            //Montamos el texto de la ruta
            let url = '{{ route ("festivos.imprimir", ['periodo' => "perxx"]) }}';
            url = url.replace('perxx',periodo);
            //llamamos a la ruta
            window.location.href=url;
            // imprimir();
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

    });
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // FIN: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<


    /*********************/
    /* INICIO: FUNCIONES */
    //********************/

    //************************************************************/
    // MUESTRAFESTIVOS: función para mostrar la lista de las festivos /
    //************************************************************/
    function muestraFestivos(){
        //Obtenemos los el mes y el año que nos ha seleccionado el usuario
        let data_ = $('#anualidad').val();
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de las festivos
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla = $('#tabla_festivos').dataTable( {
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
                this.api().columns().every( function () {
                    let that = this;

                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                } );
            },
            'ajax': {
                url: "{{route('festivos.lista')}}",
                data: {'anualidad': data_},
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {
                    data: 'fecha', render: function (data) {
                        return data.substr(8,2)+'-'+data.substr(5,2)+'-'+data.substr(0,4);
                    }
                },
                {data: 'nombre'},
                {data: 'delegacion'},
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
                {"targets": [1,2,3,4], "className": "text-center"},
            ],
            // "order": [ 1, "asc" ],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7, 10,50],
            'pageLength': 7,
        } );
        //Mostramos el DIV de la tabla y el botón de añadir festivos
        $('#tabla_').show();
        $('#btn_add').show();
        $('#btn_print').show();
    }

    //*************************************************/
    // NUEVO: función para gestionar un nuevo registro /
    //*************************************************/
    //Preparamos los campos y mostramos la ventana modal
    function nuevo(){
        //Limpiamos los campos de la ventana
        limpiaCampos();
        //Ocultamos el div de la selección simple y mostramos el div de la selección multiple
        $('#seleccion_simple').hide();
        $('#seleccion_multiple').show();

        //Mostramos la ventana modal
        $('#form').modal('show');
    }
    //***************************************************/
    // EDITAR: función para preparar la edición registro /
    //***************************************************/
    //Localiza el registro en la BD, llama a la función de cargar los campos de la ventana y
    //muestra la ventana modal
    function editar(id){
            let url_ = "{{ route('festivos.buscarRegistro') }}"
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
                url: '{{ route('festivos.eliminar') }}',
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
            url = "{{ route('festivos.nuevo') }}"
            //Realizamos el proceso de grabación del registro tantas veces como elementos tenga
            //seleccionados en el select2 de las delegaciones
            let elementos = $('#delegaciones').val().length;
            let error_ = false;
            let data;
            for (let x=1;x<=elementos;x++){
                data = { '_token': '{{ csrf_token() }}',
                    'id':$('#id_').val(),
                    'fecha':$('#fecha').val(),
                    'delegacion_id':$('#delegaciones').val()[x-1],
                    'nombre':$('#nombre').val(),
                };

                $.ajax({
                    data:  data,
                    url: url,
                    type:  'post',
                    success: function (){
                        tabla.api().ajax.reload( null, false );
                    },
                    error:  function () {
                        error_ = true;
                    },
                });
            }
            //Si no ha habido errores, actualizamos la tabla y sacamos el mensaje
            if (!error_){
                //Sacamos mensaje
                toastr.success('Registro/s insertado/s');
                //Limpiamos los campos para que se puedan seguir grabando más festivos
                limpiaCampos();
            }
        }
        else {
            url = "{{ route('festivos.actualizar') }}"
            //Almacenamos los datos del formulario en un array
            let data = { '_token': '{{ csrf_token() }}',
                'id':$('#id_').val(),
                'fecha':$('#fecha').val(),
                'delegacion_id':$('#delegacion_id').val(),
                'nombre':$('#nombre').val(),
            };
            $.ajax({
                data:  data,
                url: url,
                type:  'post',
                success:  function (response) {
                    //Actualizamos los datos de la tabla
                    tabla.api().ajax.reload( null, false );
                        //Sacamos mensaje
                        toastr.success('El registro se ha actualizado');
                        //Ocultamos el modal
                        $('#form').modal('hide');
                },
            });
        }
    }

    //********************************************************************/
    // CARGADATOS: función para mostrar los datos obtenidos en los campos /
    //********************************************************************/
    //Funcion colocar los datos obtenidos en los campos de la ventana
    function cargaDatos(response){
        $('#titulo').html('Editar festivo del año ' + $('#anualidad').val());
        $('#id_').val(response['id']);
        $('#delegacion_id').val(response['delegacion_id']).trigger('change.select2');
        $('#fecha').val(response['fecha']);
        $('#nombre').val(response['nombre']);
        //Ocultamos el div de la selección múltiple y mostramos el div de la selección simple
        $('#seleccion_simple').show();
        $('#seleccion_multiple').hide();
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');
    }
    //************************************************************/
    // LIMPIACAMPOS: función para mostrar la lista de las festivos /
    //************************************************************/
    //Funcion para limpiar los campos de la ventana
    function limpiaCampos(){
        //Montamos el título de la ventana
        $('#titulo').html('Añadir festivos del año ' + $('#anualidad').val());
        //Limpiamos los campos de la ventana
        $('#delegacion_id').val('').trigger('change.select2');
        $('#delegaciones').val('').trigger('change.select2');
        $('#id_').val('');
        $('#fecha').val('');
        $('#nombre').val('');
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

        //DELEGACIÓN (obligatorio, si se está seleccionando un registro)
        if ($('#id_').val()!='' && $('#delegacion_id').val()==''){
            toastr.error('Se tiene que seleccionar una delegación');
            ret = false;
        }

        //Cuando es un alta con una o varias delegaciones, se tiene que haber
        //introducido al menos una delegación
        if ($('#id_').val()=='' && $('#delegaciones').val().length == 0 ){
            toastr.error('Se tiene que seleccionar al menos una delegación');
            ret = false;
        }

        //FECHA (OBLIGATORIA y que el año sea el seleccionado)
        if ($('#fecha').val()=='' || $('#fecha').val().substr(0,4)!=$('#anualidad').val()){
            toastr.error('Se tiene que seleccionar una fecha del año solicitado');
            $('#fecha').focus();
            ret = false;
        }

        //NOMBRE (OBLIGATORIO)
        if ($('#nombre').val()==''){
            toastr.error('Se tiene que introducir el nombre del festivo');
            $('#nombre').focus();
            ret = false;
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    //***************************************************/
    // IMPRIMIR: función para la impresión de las festivos /
    //***************************************************/
    function imprimir(){
        let periodo = $('#periodo').val();
        $.ajax({
            data:  {"periodo":periodo},
            url: '{{ route('festivos.imprimir') }}',
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
