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
    let tabla_listaMovimientos;                  //Tabla de la lista de los movimientos
    let estado ;
    let mensajeError;          //Albergará el posible mensaje de error en los cambios de importes
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        //Cambiamos el indicador del menú que está seleccionado
        $('#menuHome').removeClass('active');
        $('#menuAdministracion').removeClass('active');
        $('#menuContabilidad').removeClass('active');
        $('#menuGerencia').addClass('active');
        $('#menuInspeccion').removeClass('active');
        $('#menuSoporte').removeClass('active');

        //Ocultamos el DIV de la tabla y los botones hasta que se realice la selección
        $('#tabla_').hide();
        $('#btn_add').hide();
        $('#btn_print').hide();

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los estados
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let st = $("#estado").select2({
            placeholder: "Elegir estado",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los empleados
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let se = $("#empleado_id").select2({
            placeholder: "Elegir empleado",
            dropdownParent: $("#form"),
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

        //Editar el importe de todos los plazos
        $('#tabla_adelantos tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Preguntamos por el nuevo importe del aumento
            Swal.fire({
                //Para que se muestre en el modal de la lista de movimientos
                target: document.getElementById('component'),
                title: "Modificar los plazos",
                html: '<label for="importe_plazos">Nuevo importe</label>',
                inputAttributes: {
                    'id': 'importe_plazos',
                    'class': 'text-right',
                },
                input: "number",
                inputValue: Math.abs(data.importe_plazo),
                showCancelButton: true,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
                inputValidator: (value) => {
                    //Validamos el importe introducido
                    if (!validarCambioPlazos(value, data.id)) {
                        return (mensajeError);
                    }
                }
            })
                .then(resultado => {
                    //Llamamos a la grabación de los nuevos plazos si no sale cancelando
                    if (resultado.dismiss  != 'cancel') {
                        actualizarPlazos(data.id, data.empleado_id, resultado.value)

                    }
                });
        });

        //Añadir un aumento al adelanto
        $('#tabla_adelantos tbody').on('click','.aumento', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Ponemos el id del registro en el campo oculto
            $('#id_').val(data.id);
            $('#empleado_id_').val(data.empleado_id);
            //Ponemos el titulo en la ventana
            $('#titulo_aumento').html('Aumento para '+data.nombre+' '+data.apellidos);
            //Mostramos el modal de introducir una nueva cantidad incorporada al montante total solicitado
            $('#aumento').modal('show');
        });

        //Mostra la lista de los movimientos
        $('#tabla_adelantos tbody').on('click','.lista', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la función que mostrará los movimientos del adelanto seleccionado
            muestraListaMovimientos(data);               //Está dentro de SCRIPTS de los movimientos
        });

        //Borrar el registro
        $('#tabla_adelantos tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el adelanto completo!",
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
            Selección ESTADO
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('change', '#estado', function () {

            //Si no hay estado seleccionado
            if($('#estado').val() == ''){
                //Ocultamos el DIV de la tabla y los botones hasta que se realice la selección
                $('#tabla_').hide();
                $('#btn_add').hide();
                $('#btn_print').hide();
            }
            else{
                //Llamamos a la función que mostrará los adelantos según el estado seleccionado
                muestraAdelantos();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón añadir un nuevo adelanto
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_add', function () {
            //Limpiamos los campos de la ventana
            limpiaCampos();
            //Mostramos la ventana modal para el nuevo adelanto
            $('#form').modal('show');
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón imprimir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_print', function () {
            //Llamamos a la funcion de imprimir los registros
            let estado =  $('#estado').val();
            //Montamos el texto de la ruta
            let url = '{{ route ("adelantos.imprimir", ['estado' => "estxx"]) }}';
            url = url.replace('estxx',estado);
            //llamamos a la ruta
            window.location.href=url;
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón del volver (MODAL FORM)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_volver', function () {
            //Ocultamos el modal
            $('#form').modal('hide');
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón salvar (MODAL FORM)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_salvar', function () {
            //Llamamos a la función que nos valida si los campos
            if (validaCamposModal()) {
                //Grabamos el registro
                grabaRegistro();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón del volver (MODAL AUMENTO)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_volver_aumento', function () {
            //Ocultamos el modal
            $('#aumento').modal('hide');
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón salvar (MODAL AUMENTO)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_salvar_aumento', function () {
            //Llamamos a la función que nos valida si los campos
            if (validaCamposAumento()) {
                //Grabamos el registro
                grabaAumento();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Botón del volver (LISTA DE MOVIMIENTOS)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_listamovimientos_volver', function () {
            //Ocultamos el modal
            $('#listaMovimientos').modal('hide');
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        ACCIONES EN LA LISTA DE MOVIMIENTOS
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

        //Editar el importe de un adelanto
        $('#tabla_listaMovimientos tbody').on('click','.editar_plazo', function() {
            //Obtenemos el id del registro
            let data = tabla_listaMovimientos.api().row($(this).parents()).data();
            //Preguntamos por el nuevo importe del aumento
            Swal.fire({
                //Para que se muestre en el modal de la lista de movimientos
                target: document.getElementById('listaMovimientos'),
                title: "Modificar el plazo",
                html: '<label for="importe_plazo">Nuevo importe</label>',
                inputAttributes: {
                    'id': 'importe_plazo',
                    'class': 'text-right',
                },
                input: "number",
                inputValue: Math.abs(data.importe),
                showCancelButton: true,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
                inputValidator: (value) => {
                    //Validamos el importe introducido
                    if (!validarCambioPlazo(value, data.id, data.adelanto_id)) {
                        return (mensajeError);
                    }
                }
            })
            .then(resultado => {
                //Llamamos a la grabación del nuevo importe del plazo
                //Llamamos a la grabación del nuevo importe del aumento
                if (resultado.dismiss  != 'cancel') {
                    actualizarPlazo(data.id, data.adelanto_id, resultado.value)
                }
            });
        });

        //Editar el importe de un aumento
        $('#tabla_listaMovimientos tbody').on('click','.editar_aumento', function(){
            //Obtenemos el id del registro
            let data = tabla_listaMovimientos.api().row($(this).parents()).data();
            //Preguntamos por el nuevo importe del aumento
            Swal
                .fire({
                    //Para que se muestre en el modal de la lista de movimientos
                    target: document.getElementById('listaMovimientos'),
                    title: "Modificar el aumento",
                    html: '<label for="importe_aumento">Nuevo importe</label>',
                    inputAttributes: {
                        'id': 'importe_aumento',
                        'class': 'text-right',
                    },
                    input: "number",
                    inputValue: Math.abs(data.importe),
                    showCancelButton: true,
                    confirmButtonText: "Guardar",
                    cancelButtonText: "Cancelar",
                    inputValidator: (value) => {
                        //Validamos el importe introducido
                        if (!validarCambioAumento(value)) {
                            return(mensajeError);
                        }
                    }
                })
                .then(resultado => {
                    //Llamamos a la grabación del nuevo importe del aumento
                    if (resultado.dismiss  != 'cancel') {
                        actualizarAumento(data.id, data.adelanto_id, resultado.value)
                    }
                });
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón Aplazar
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_listaMovimientos tbody').on('click','.aplazar', function(){
            //Obtenemos el id del registro
            const data = tabla_listaMovimientos.api().row($(this).parents()).data();
            //Ponemos el id del registro en el campo oculto
            $('#listamovimientos_id').val(data.id);
            $('#listamovimientos_empleado_id').val(data.empleado_id);
            $('#listamovimientos_adelanto_id').val(data.adelanto_id);
            //Llamamos a la función para aplazar el plazo
            aplazarPlazo(data);
        });

    });
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // FIN: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<


    /*********************/
    /* INICIO: FUNCIONES */
    //********************/

    //********************************************************************/
    // ACTUALIZARAUMENTO: función para actualizar el aumento seleccionado
    //********************************************************************/
    function actualizarAumento(id,adelanto_id,importe){

        //Función para la edición del movimiento
        let url = "{{ route('movimientoAdelantos.actualizarAumento') }}"

        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
            'id':id,
            'adelanto_id':adelanto_id,
            'importe':importe};

        //Llamamos a AJAX
        $.ajax({
            data:  data,
            url: url,
            async: false,
            type:  'post',
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla_listaMovimientos.api().ajax.reload(null, false);
                toastr.success('Aumento modificado');
                obtenerInformacion(adelanto_id);
            }
        });
    }

    //********************************************************************/
    // ACTUALIZARPLAZO: función para actualizar el importe del plazo
    //********************************************************************/
    function actualizarPlazo(id,adelanto_id,importe){

        //Función para la edición del movimiento
        let url = "{{ route('movimientoAdelantos.actualizarPlazo') }}"

        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
            'id':id,
            'adelanto_id':adelanto_id,
            'importe':importe};

        //Llamamos a AJAX
        $.ajax({
            data:  data,
            url: url,
            async: false,
            type:  'post',
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla_listaMovimientos.api().ajax.reload(null, false);
                toastr.success('Plazo modificado');
                obtenerInformacion(adelanto_id);
            }
        });
    }

    //*******************************************************/
    // ACTUALIZARPLAZOS: función para actualizar los plazos
    //*******************************************************/
    function actualizarPlazos(adelanto_id,empleado_id,importe){

        //Fecha del dia
        const hoy = new Date()

        //Función para la creación de movimientos
        let url = "{{ route('movimientoAdelantos.creaMovimientos') }}"

        //Almacenamos los datos del formulario en un array. Pasamos como importe 0 para que el controlador
        //sepa que es una modificación de los movimientos y no es un nuevo adelanto.
        let data = { '_token': '{{ csrf_token() }}',
            'adelanto_id':adelanto_id,
            'empleado_id':empleado_id,
            'importe_plazo':importe,
            'fecha' : hoy.toISOString(),
            'importe':0};

        //Llamamos a AJAX para la grabación de los movimientos a partir del importe de los plazos
        $.ajax({
            data:  data,
            url: url,
            type:  'post',
            success:  function (response) {

                //Si ha ido bien, llamamos a AJAX para la actualización del adelanto
                let url = "{{ route('adelantos.actualizar') }}"
                //Almacenamos los datos del formulario en un array
                let data = {
                    '_token': '{{ csrf_token() }}',
                    'id': adelanto_id,
                    'importe_plazo': importe,
                };
                $.ajax({
                    data: data,
                    url: url,
                    type: 'post',
                    success: function (response) {
                        //Si todo ha ido correctamente
                        //Actualizamos los datos de la tabla
                        tabla.api().ajax.reload(null, false);
                        //Sacamos mensaje
                        toastr.success('Se han modificado los plazos');
                    },
                });
            },
        });
    }

    /****************************************************************************************************************/
    /* APLAZARPLAZO: función para aplazar el plazo seleccionado
    //***************************************************************************************************************/
    function aplazarPlazo(data){

        //obtenemos el id del adelanto para pasarlo como parámetro a AJAX
        const id = data.id;
        const adelanto_id = data.adelanto_id;
        //Obtenemos la información completa del adelanto
        const url = "{{ route('movimientoAdelantos.aplazar') }}"
        $.ajax({
            data:  {'_token': '{{ csrf_token() }}',"id":id,"adelanto_id":adelanto_id},
            url: url,
            type:  'post',
            success:  function (response) {
                //Actualizamos la tabla
                tabla_listaMovimientos.api().ajax.reload(null, false);
                //Actualizamos los datos de información
                obtenerInformacion(adelanto_id)
                //Sacamos mensaje
                toastr.success('Se ha modificado el registro');
            },
            error: function(xhr, status, error) {
                toastr.error(xhr.responseText);
            }
        });
    }

    //**********************************************************/
    // ELIMINAR: función para eliminar un registro seleccionado /
    //**********************************************************/
    // Elimina el registro seleccionado
    function eliminar(id){
        $.ajax({
            data:  {'_token': '{{ csrf_token() }}',"id":id},
            url: '{{ route('adelantos.eliminar') }}',
            type:  'post',
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla.api().ajax.reload( null, false );
                //Mostramos el mensaje de que se ha borrado el registro
                toastr.success('El registro se ha eliminado');
            },
            error: function(xhr, status, error) {
                toastr.error(xhr.responseText);
            }
        });
    }

    //*******************************************************************/
    // GRABAAUMENTO: función para gestionar la grabación de un aumento   /
    //*******************************************************************/
    function grabaAumento(){
        //Función para la grabación de un aumento
        let url = "{{ route('movimientoAdelantos.creaAumento') }}"

        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
            'adelanto_id':$('#id_').val(),
            'empleado_id':$('#empleado_id_').val(),
            'fecha_aumento':$('#fecha_aumento').val(),
            'importe_aumento':$('#importe_aumento').val(),
            'observaciones':$('#observaciones_aumento').val(),
        };
        $.ajax({
            data:  data,
            url: url,
            type:  'post',
            success:  function (response) {
                //Si se ha grabado el aumento
                //Llamamos de nuevo a una función ajax para
                //para añadir los movimientos necesarios por el aumento recibido
                let url = "{{ route('movimientoAdelantos.addMovimientos') }}"

                //Almacenamos los datos del formulario en un array y el id del adelanto insertado
                let data = { '_token': '{{ csrf_token() }}',
                    'adelanto_id':$('#id_').val(),
                    'empleado_id':$('#empleado_id_').val(),
                    'importe':$('#importe_aumento').val()};

                $.ajax({
                    data: data,
                    url: url,
                    type: 'post',
                    success: function (response) {
                        //Si todo ha ido correctamente
                        //Actualizamos los datos de la tabla
                        tabla.api().ajax.reload(null, false);
                        //Sacamos mensaje
                        toastr.success('El registro se ha insertado');
                        //Limpiamos los campos del modal del aumento
                        limpiaCamposAumento();
                        //Cerramos el formulario del aumento
                        $('#aumento').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                toastr.error(xhr.responseText);
            }
        });
    }
    //*******************************************************************/
    // GRABAREGISTRO: función para gestionar la grabación de un registro /
    //*******************************************************************/
    function grabaRegistro(){
        //Función para la grabación del registro
        let url = "{{ route('adelantos.nuevo') }}"

        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
            'empleado_id':$('#empleado_id').val(),
            'fecha':$('#fecha').val(),
            'importe':$('#importe').val(),
            'importe_plazo':$('#importe_plazo').val(),
            'observaciones':$('#observaciones').val(),
        };
        $.ajax({
            data:  data,
            url: url,
            type:  'post',
            success:  function (response) {
                //Si se ha grabado el movimiento
                //Llamamos de nuevo a una función ajax para
                //para grabar los movimientos
                let url = "{{ route('movimientoAdelantos.creaMovimientos') }}"

                //Almacenamos los datos del formulario en un array y el id del adelanto insertado
                let data = { '_token': '{{ csrf_token() }}',
                    'adelanto_id':response.id,
                    'empleado_id':$('#empleado_id').val(),
                    'fecha':$('#fecha').val(),
                    'importe':$('#importe').val(),
                    'importe_plazo':$('#importe_plazo').val()};

                $.ajax({
                    data: data,
                    url: url,
                    type: 'post',
                    success: function (response) {
                        //Si todo ha ido correctamente
                        //Actualizamos los datos de la tabla
                        tabla.api().ajax.reload(null, false);
                        //Sacamos mensaje
                        toastr.success('El registro se ha insertado');
                        //Limpiamos los campos para que se puedan seguir grabando más vacaciones
                        limpiaCampos();
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                toastr.error(xhr.responseText);
            }
        });
    }

    //*******************************************************************************/
    // GRABAPLAZOS: función para gestionar la grabación un cambio de importe de plazo
    //*******************************************************************************/
    function grabaPlazos(){

        //Fecha del dia
        const hoy = new Date()

        //Función para la grabación de un plazo

        let url = "{{ route('movimientoAdelantos.creaMovimientos') }}"

        //Almacenamos los datos del formulario en un array. Pasamos como importe 0 para que el controlador
        //sepa que es una modificación de los movimientos y no es un nuevo adelanto.
        let data = { '_token': '{{ csrf_token() }}',
            'adelanto_id':$('#plazo_id_').val(),
            'empleado_id':$('#plazo_empleado_id').val(),
            'importe_plazo':$('#plazo_importe_plazo').val(),
            'fecha' : hoy.toISOString(),
            'importe':0};

        //Llamamos a AJAX para la grabación de los movimientos a partir del importe de los plazos
        $.ajax({
            data:  data,
            url: url,
            type:  'post',
            success:  function (response) {

                //Si ha ido bien, llamamos a AJAX para la actualización del adelanto
                let url = "{{ route('adelantos.actualizar') }}"
                //Almacenamos los datos del formulario en un array
                let data = {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#plazo_id_').val(),
                    'importe_plazo': $('#plazo_importe_plazo').val(),
                    'saldo': response,
                };
                $.ajax({
                    data: data,
                    url: url,
                    type: 'post',
                    success: function (response) {
                        //Si todo ha ido correctamente
                        //Actualizamos los datos de la tabla
                        tabla.api().ajax.reload(null, false);
                        //Sacamos mensaje
                        toastr.success('Se ha modificado el registro');
                        //Limpiamos los campos del modal del plazo
                        limpiaCamposPlazo();
                        //Cerramos el formulario del plazo
                        $('#plazo').modal('hide');
                    },
                });
            },
        });
    }

    //*******************************************************/
    // IMPRIMIR: función para la impresión de las vacaciones /
    //*******************************************************/
    function imprimir(){
        let estado = $('#estado').val();
        $.ajax({
            data:  {"estado":estado},
            url: '{{ route('adelantos.imprimir') }}',
            type:  'get',
            success:  function (response) {
                //No hay acciones que tomar en esta función
            },
        });
    }

    //****************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos del formulario  /
    //***************************************************************/
    //Función para limpiar los campos de la ventana
    function limpiaCampos(){
        //Montamos el título de la ventana
        $('#titulo').html('Nuevo adelanto');
        //Limpiamos los campos de la ventana
        $('#id_').val('');
        $('#empleado_id').val('').trigger('change.select2');
        $('#fecha').val('');
        $('#importe').val('');
        $('#importe_plazo').val('');
        $('#observaciones').val('');
        //Ponemos el texto del botón 'grabar'
        $('#btn_salvar').html('Grabar');
    }

    //******************************************************************/
    // LIMPIACAMPOSAUMENTO: función para limpiar los campos del aumento /
    //******************************************************************/
    //Función para limpiar los campos de la ventana
    function limpiaCamposAumento(){
        //Montamos el título de la ventana
        $('#titulo').html('');
        //Limpiamos los campos de la ventana
        $('#id_').val('');
        $('#empleado_id_').val('').trigger('change.select2');
        $('#fecha_aumento').val('');
        $('#importe_aumento').val('');
        $('#observaciones_aumento').val('');
    }

    //**************************************************************/
    // LIMPIACAMPOSPLAZO: función para limpiar los campos del plazo /
    //**************************************************************/
    //Función para limpiar los campos de la ventana
    function limpiaCamposImporte(){
        //Limpiamos los campos de la ventana
        $('#importe_id').val('');
        $('#importe_adelanto_id').val('');
        $('#importe_tipo').val('');
        $('#importe_importe').val('');
        $('#importe_empleado_id').val('');
    }

    //********************************************************************/
    // MUESTRAADELANTOS: función para mostrar la lista de los adelantos  /
    //********************************************************************/
    function muestraAdelantos() {
        //Obtenemos los datos de la selección
        estado = $('#estado').val();
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de los ADELANTOS
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla = $('#tabla_adelantos').dataTable({
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
                url: "{{route('adelantos.lista')}}",
                data: {'estado': estado},
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'numero'},
                {data: 'nombre'},
                {data: 'apellidos'},
                {       data: 'fecha', render: function (data) {
                        return data.substr(8,2)+'-'+data.substr(5,2)+'-'+data.substr(0,4);
                    }
                },
                {data: 'estado'},
                {
                    //No mostramos lo botones en esta sección. Evaluamos el estado del adelanto
                    //y lo mostramos en la sección columnDefs
                    defaultContent: ''
                },
                {data: 'empleado_id'},
                {data: 'importe_plazo'},
                // {data: 'saldo'},
            ],
            'columnDefs': [
                {"targets": [0, 7, 8], "visible": false},                    //Id del registro, importe_plazo y saldo (oculto)
                {"targets": [1, 2, 3,4], "className": "text-center"},
                {"targets": [5], "className": "text-center","render": function (data,type,row,meta) {
                            if (data == 1 ){
                                return "<span class='shadow-none badge badge-danger'>P</span>";
                            }
                            else{
                                return "<span class='badge badge-success'>F</span>";
                            }
                    }
                },
                {"targets": [6], "className": "text-center", "render": function (data,type,row,meta) {
                            if (row.estado == 1 ){
                                return `<button class="editar btn btn-primary mb-2 mr-2" type="button" title="Modificar plazo">
                                                 @include("layouts.svg.editar")
                                        <button class="aumento btn btn-primary mb-2 mr-2" type="button" title="Añadir aumento">
                                                 @include("layouts.svg.aumento")
                                        </button>
                                        <button class="lista btn btn-primary mb-2 mr-2" type="button" title="Movimientos">
                                                @include("layouts.svg.lista")
                                        </button>
                                        <button class="eliminar btn btn-primary mb-2 mr-2" type="button" title="Eliminar adelanto">
                                                @include("layouts.svg.borrar")
                                        </button>`;
                            }
                    }
                },

            ],
            "order": [0, "desc"],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7, 10, 50],
            'pageLength': 7,
        });
        //Mostramos el DIV de la tabla y el botón de añadir vacaciones
        $('#tabla_').show();
        $('#btn_add').show();
        $('#btn_print').show();
    }

    /*******************************************************************************************************/
    /* MUESTRAINFORMACION: función para mostrar los datos obtenidos para la información de los movimientos
    //******************************************************************************************************/
    function muestraInformacion(adelanto,movimientos){

        const fecha = adelanto.fecha.substr(8,2)+'-'+adelanto.fecha.substr(5,2)+'-'+adelanto.fecha.substr(0,4);
        const importe_solicitado = Intl.NumberFormat('de-DE', {style: 'currency',currency: 'EUR', minimumFractionDigits: 2}).format(adelanto.importe);
        const importe_liquidado = Intl.NumberFormat('de-DE', {style: 'currency',currency: 'EUR', minimumFractionDigits: 2}).format(Math.abs(movimientos.liquidados_importe));
        const importe_aumentos = Intl.NumberFormat('de-DE', {style: 'currency',currency: 'EUR', minimumFractionDigits: 2}).format(Math.abs(movimientos.aumentos_importe));
        const importe_pendientes = Intl.NumberFormat('de-DE', {style: 'currency',currency: 'EUR', minimumFractionDigits: 2}).format(Math.abs(movimientos.pendientes_importe));
        const observaciones = adelanto.observaciones == null ? '' : adelanto.observaciones;
        //Colocamos los datos obtenidos
        //1a columna
        $('#listaMovimientos_fecha').html('<strong>Fecha solicitud: </strong>'+fecha);
        $('#listaMovimientos_solicitado_importe').html('<strong>Importe solicitado: </strong>'+importe_solicitado);
        $('#listaMovimientos_fecha_fin').html('<strong>Último plazo: </strong>'+movimientos.ultimo_mes+'/'+movimientos.ultimo_ano);
        //2a columna
        $('#listaMovimientos_liquidados').html('<strong>Plazos liquidados: </strong>'+movimientos.liquidados);
        $('#listaMovimientos_liquidados_importe').html('<strong>Importe liquidado: </strong>'+importe_liquidado);
        //3a columna
        $('#listaMovimientos_aumentos').html('<strong>Aumentos: </strong>'+movimientos.aumentos);
        $('#listaMovimientos_aumentos_importe').html('<strong>Importe aumentos: </strong>'+importe_aumentos);
        //4a columna
        $('#listaMovimientos_pendientes').html('<strong>Plazos pendientes: </strong>'+movimientos.pendientes);
        $('#listaMovimientos_pendientes_importe').html('<strong>Importe pendiente: </strong>'+importe_pendientes);
        $('#listaMovimientos_observaciones').html('<strong>Observaciones: </strong>'+observaciones);

    }

    /****************************************************************************************************************/
    /* MUESTRALISTAMOVIMIENTOS: función para mostrar la lista de los movimientos y obtener los datos de información */
    //***************************************************************************************************************/
    function muestraListaMovimientos(data){

        //obtenemos el id del adelanto para pasarlo como parámetro a AJAX
        let id_ = data.id;
        //Ponemos el titulo en la ventana
        $('#listamovimientos_empleado').html(data.numero + ' - ' + data.nombre+' '+data.apellidos);

        //Llamamos a la función que obtiene los datos de información
        obtenerInformacion(id_);

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de los movimientos del adelanto
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla_listaMovimientos = $('#tabla_listaMovimientos').dataTable({
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
                url: "{{route('movimientoAdelantos.lista')}}",
                data: {'adelanto_id': id_},
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'mes'},
                {data: 'ano'},
                {data: 'fecha'},
                {data: 'tipo'},
                {
                    data: 'importe', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {data:'estado'},
                {
                    //No mostramos lo botones en esta sección. Evaluamos el estado del adelanto
                    //y lo mostramos en la sección columnDefs
                    defaultContent: ' '
                },
                {data: 'created_at'},
                {data: 'adelanto_id'},

            ],
            'columnDefs': [
                {"targets": [0, 8, 9], "visible": false},        //Id del registro (oculto)
                {"targets": [1, 2, 5], "className": "text-center"},
                {"targets": [3], "className": "text-center", "render": function (data,type,row,meta) {
                        if (data!=null){
                            return data.substr(8,2)+'-'+data.substr(5,2)+'-'+data.substr(0,4);
                        }
                        else{
                            return '';
                        }
                    }
                },
                {"targets": [4], "className": "text-center", "render": function (data,type,row,meta) {
                        if (data == 'P' ){
                            return "<span class='shadow-none badge badge-primary'>Plazo</span>";
                        }
                        else{
                            return "<span class='badge badge-warning'>Aumento</span>";
                        }
                    }
                },
                {"targets": [6], "className": "text-center", "render": function (data,type,row,meta) {
                        if (data == 0 && row.tipo == "P"){
                            return "<span class='shadow-none badge badge-danger'>Pendiente</span>";
                        }
                        else if (data == 1 && row.tipo == "P"){
                            return "<span class='badge badge-success'>Liquidado</span>";
                        }
                        else{
                            return '';
                        }
                    }
                },
                {"targets": [7], "className": "text-center", "render": function (data,type,row,meta) {
                        if (row.estado != 1 && row.tipo == "P"){
                            return `<button class="editar_plazo btn btn-primary mb-2 mr-2" type="button" title="Cambiar importe">
                                        @include("layouts.svg.editar")
                                    </button>
                                    <button class="aplazar btn btn-primary mb-2 mr-2" type="button" title="Aplazar">
                                        @include("layouts.svg.aplazar")
                                    </button>`;
                        }
                        else if (row.tipo == "A"){
                            return `<button class="editar_aumento btn btn-primary mb-2 mr-2" type="button" title="Cambiar aumento">
                                        @include("layouts.svg.editar")
                                    </button>
                                    <button class="borrar_aumento btn btn-primary mb-2 mr-2" type="button" title="Eliminar aumento">
                                        @include("layouts.svg.borrar")
                                    </button>`;
                        }
                        else {
                            return '';
                        }
                    }
                },
            ],
            "order": [[4, "asc"],[8, "asc"]],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7],
            'pageLength': 5,
        });

        //Mostramos el modal de introducir una nueva cantidad incorporada al montante total solicitado
        $('#listaMovimientos').modal('show');


    }

    /****************************************************************************************************************/
    /* OBTENERINFORMACION: función para obtener los datos de información
    //***************************************************************************************************************/
    function obtenerInformacion(id){

        //obtenemos el id del adelanto para pasarlo como parámetro a AJAX
        let id_ = id;
        //Obtenemos la información completa del adelanto
        let url = "{{ route('adelantos.buscarRegistro') }}"
        //Almacenamos los datos del formulario en un array
        let datos = { 'id': id_};
        $.ajax({
            data:  datos,
            url: url,
            type:  'get',
            success:  function (adelanto) {
                //Obtenemos la información de los movimientos
                let url = "{{ route('movimientoAdelantos.obtenerInformacion') }}"
                //Almacenamos los datos del formulario en un array
                let datos = { 'adelanto_id': id_};
                $.ajax({
                    data:  datos,
                    url: url,
                    type:  'get',
                    success:  function (movimientos) {
                        //Llamamos a la función que mostrará los datos obtenidos
                        muestraInformacion(adelanto,movimientos);
                    }
                });
            },
            error: function(xhr, status, error) {
                toastr.error(xhr.responseText);
            }
        });
    }

    //************************************************************************/
    // VALIDACAMPOSMODAL: función para validar los campos de la ventana modal /
    //************************************************************************/
    //Valida los campos de la ventana MODAL
    function validaCamposModal(){
        //Variable auxiliar
        let ret = true;
        let fecha;
        let hoy;

        //EMPLEADO (Obligatorio)
        if ($('#empleado_id').val()==''){
            toastr.error('Se tiene que seleccionar un empleado');
            $('#empleado_id').focus();
            ret = false;
        }
        //FECHA (obligatorio)
        if ($('#fecha').val()==''){
            toastr.error('Se tiene que introducir una fecha');
            $('#fecha').focus();
            ret = false;
        }
        else{
            //Obtenemos la fecha introducida y la fecha actual del sistema
            fecha = moment($('#fecha').val());
            hoy = moment();
            //Comprobamos antes que la fecha sea válida
            if (fecha.isValid()){
                //Comprobamos que la fecha introducida mno debe ser menor al mes y año actual
                if (fecha.format("MM") < hoy.format("MM") || fecha.format("YYYY") < hoy.format("YYYY")){
                    toastr.error('La fecha tiene que ser igual o mayor al mes y año actual ');
                    $('#fecha').focus();
                    ret = false;
                }
            }
            else{
                toastr.error('La fecha no es válida');
                $('#fecha').focus();
                ret = false;
            }
        }

        //IMPORTE (Obligatorio y > 0)
        if ($('#importe').val()=='' || $('#importe').val()<=0){
            toastr.error('Se tiene que introducir un importe');
            $('#importe').focus();
            ret = false;
        }
        else{
            //Comprobamos que la cantidad no sea mayor a 99.999,99
            if (parseFloat($('#importe').val()>99999.99)){
                toastr.error('El importe máximo es 99.999,99€');
                $('#importe').focus();
                ret = false;
               }
        }

        //IMPORTE DE LA CUOTA (Obligatorio, > 0, < = IMPORTE)
        if ($('#importe_plazo').val()=='' || $('#importe_plazo').val()<=0){
            toastr.error('Se tiene que introducir un importe de la cuota');
            $('#importe_plazo').focus();
            ret = false;
        }
        else{
            //Comprobamos que la cantidad no sea mayor al importe solicitado
            if (parseFloat($('#importe_plazo').val()) > parseFloat($('#importe').val())){
                toastr.error('El importe de la cuota no puede ser mayor que el importe solicitado');
                $('#importe').focus();
                ret = false;
               }
        }

        //Devolvemos el valor de retorno
        return ret;
    }

    //********************************************************************************/
    // VALIDACAMPOSAUMENTO: función para validar los campos de la ventana del aumento /
    //********************************************************************************/
    //Valida los campos de la ventana MODAL
    function validaCamposAumento(){
        //Variable auxiliar
        let ret = true;
        let fecha;
        let hoy;

        //FECHA (obligatorio)
        if ($('#fecha_aumento').val()==''){
            toastr.error('Se tiene que introducir una fecha');
            $('#fecha_aumento').focus();
            ret = false;
        }
        else{
            //Obtenemos la fecha introducida y la fecha actual del sistema
            fecha = moment($('#fecha_aumento').val());
            //Comprobamos antes que la fecha sea válida
            if (!fecha.isValid()){
                toastr.error('La fecha no es válida');
                $('#fecha_aumento').focus();
                ret = false;
            }
        }

        //IMPORTE (Obligatorio y > 0)
        if ($('#importe_aumento').val()=='' || $('#importe_aumento').val()<=0){
            toastr.error('Se tiene que introducir un importe');
            $('#importe_aumento').focus();
            ret = false;
        }
        else{
            //Comprobamos que la cantidad no sea mayor a 99.999,99
            if (parseFloat($('#importe_aumento').val()>99999.99)){
                toastr.error('El importe máximo es 99.999,99€');
                $('#importe_aumento').focus();
                ret = false;
               }
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    //*****************************************************************/
    // VALIDARCAMBIOAUMENTO: función para validar la modificación del importe del aumento/
    //*****************************************************************/
    //Valida el importe de la ventana modal en función de la petición
    function validarCambioAumento(valor){
        //Variable auxiliar
        let ret = true;
        //Limpiamos el mensaje de error
        mensajeError = '';

        //IMPORTE (Obligatorio y > 0)
        if (valor=='' || valor<=0){
            mensajeError = 'Se tiene que introducir un importe';
            ret = false;
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    //**************************************************************************/
    // VALIDARCAMBIOPLAZO: función para validar el cambio de importe del plazo  /
    //**************************************************************************/
    //Valida el importe de la ventana modal en función de la petición
    function validarCambioPlazo(valor,id,adelanto_id){
        //Variable auxiliar
        let ret = true;
        let pendiente;
        //Limpiamos el mensaje de error
        mensajeError = '';

        //Nos traemos el saldo pendiente
        let url = "{{ route('movimientoAdelantos.pendiente') }}"

        $.ajax({
            data: {'id':id,'adelanto_id': adelanto_id},
            async: false,
            url: url,
            type: 'get',
            success: function (data) {
                pendiente = Math.abs(data);
            }
        });
        //IMPORTE (Obligatorio y > 0)
        if (valor=='' || valor<=0){
            mensajeError = 'Se tiene que introducir un importe';
            ret = false;
        }
        else{
            //Comprobamos que la cantidad no sea mayor al saldo pendiente, siempre que no sea un aumento
            if (parseFloat(valor) > parseFloat(pendiente)){
                mensajeError = 'El importe no puede ser mayor al saldo<br/>'+
                    ' pendiente desde la fecha seleccionada<br/>'+
                    'Saldo máximo pendiente: '+Intl.NumberFormat('de-DE', {style: 'currency',currency: 'EUR', minimumFractionDigits: 2}).format(Math.abs(pendiente));
                ret = false;
            }
        }
        //Devolvemos el valor de retorno
        return ret;
    }
    //*******************************************************************************/
    // VALIDACAMBIOPLAZOS: función para validar el cambio del importes de los plazos /
    //*******************************************************************************/
    //Valida los campos de la ventana MODAL
    function validarCambioPlazos(valor,adelanto_id){
        //Variable auxiliar
        let ret = true;
        let pendiente;
        //Limpiamos el mensaje de error
        mensajeError = '';

        //Nos traemos el saldo pendiente
        let url = "{{ route('movimientoAdelantos.pendiente') }}"

        $.ajax({
            data: {'id':null,'adelanto_id': adelanto_id},
            async: false,
            url: url,
            type: 'get',
            success: function (data) {
                pendiente = Math.abs(data);
            }
        });
        //IMPORTE (Obligatorio y > 0)
        if (valor=='' || valor<=0){
            mensajeError = 'Se tiene que introducir un importe';
            ret = false;
        }
        else{
            //Comprobamos que la cantidad no sea mayor al saldo pendiente, siempre que no sea un aumento
            if (parseFloat(valor) > parseFloat(pendiente)){
                mensajeError = 'El importe no puede ser mayor al saldo<br/>'+
                    'Saldo máximo pendiente: '+Intl.NumberFormat('de-DE', {style: 'currency',currency: 'EUR', minimumFractionDigits: 2}).format(Math.abs(pendiente));
                ret = false;
            }
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
           FIN: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
</script>

{{--***********************--}}
{{--FIN: SCRIPTS PROPIOS   --}}
{{--***********************--}}

