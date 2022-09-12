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
    let meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    let mes = 0;
    let ano = 0;
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        //Cambiamos el indicador del menú que está seleccionado
        $('#menuHome').removeClass('active');
        $('#menuAdministracion').addClass('active');
        $('#menuContabilidad').removeClass('active');
        $('#menuGerencia').removeClass('active');
        $('#menuInspeccion').removeClass('active');
        $('#menuSoporte').removeClass('active');

        //Ocultamos el DIV de la tabla hasta que se seleccione el mes y año
        //y el botón de añadir ayudas
        $('#tabla_').hide();
        $('#btn_add').hide();
        $('#btn_print').hide();

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los empleados
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        // let sd = $("#empleado_id").select2({
        //     dropdownParent: $("#form"),
        //     placeholder: "Elegir empleado",
        //     allowClear: true
        // });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        //Editar el registro
        $('#tabla_ayudas tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la función para obtener los datos via Ajax
            editar(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            CAMPO numero del empleado
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('keyup', '#numero_empleado', function () {
            //Llamamos a la función que nos busca el empleado
            buscarEmpleado();
        });

        //Borrar el registro
        $('#tabla_ayudas tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará la ayuda!",
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
            Selección MES/AÑO
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('change', '#periodo', function () {
            //Desglosamos el mes / año
            mes = $('#periodo').val().substr(5,2);
            ano = $('#periodo').val().substr(0,4);
            //Siempre que estén relleno el mes y el año
            if (mes != '' && ano != ''){
                // Llamamos a la función que mostrará las ayudas del mes y año seleccionados
                muestraAyudas();
            }
            else {
                //Ocultamos los botones y el id de la tabla
                $('#tabla_').hide();
                $('#btn_add').hide();
                $('#btn_print').hide();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Cambio de cualquier importe para actualizar el importe total
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('keyup', ['#gasolina','#juzgados','#baja_enfermedad',
                                  '#baja_accidente','#inspecciones','#minusvalia','#otros'], function () {
            //Llamamos a la función que mostrará las ayudas del mes y año seleccionados
            actualizaTotal();
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
            //Llamamos a la funcion de imprimir las ayudas
            let periodo =  $('#periodo').val();
            //Montamos el texto de la ruta
            let url = '{{ route ("ayudas.imprimir", ['periodo' => "perxx"]) }}';
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
    // MUESTRAAYUDAS: función para mostrar la lista de las ayudas /
    //************************************************************/
    function muestraAyudas(){
        //Obtenemos los el mes y el año que nos ha seleccionado el usuario
        let data_ = $('#periodo').val();
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de las AYUDAS
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla = $('#tabla_ayudas').dataTable( {
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
                url: "{{route('ayudas.lista')}}",
                data: {'periodo': data_},
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'numero'},
                {data: 'nombre'},
                {data: 'apellidos'},
                {
                    data: 'gasolina', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {
                    data: 'juzgados', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {
                    data: 'baja_enfermedad', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {
                    data: 'baja_accidente', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {
                    data: 'inspecciones', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {
                    data: 'minusvalia', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {
                    data: 'otros', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
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
                {"targets": [1,2,3,4,5,6,7,8,9,10,11], "className": "text-center"},
            ],
            "order": [ 1, "asc" ],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7, 10,50],
            'pageLength': 7,
        } );
        //Mostramos el DIV de la tabla y el botón de añadir ayudas
        $('#tabla_').show();
        $('#btn_add').show();
        $('#btn_print').show();
    }

    // ****************************
    // BUSCAR UN NUMERO DE EMPLEADO
    // ****************************
    function buscarEmpleado(){

        //Si el campo contiene información
        if ($('#numero_empleado').val().length >0){
            //Preparamos los datos para llamar a la función Ajax
            let url = "{{ route('empleados.buscarEmpleado') }}"
            let data = { 'numero': $('#numero_empleado').val()};

            //Llamamos a Ajax para comprobar si existe el empleado y si existe, obtenemos sus datos
            $.ajax({
                type: 'get',
                url: url,
                data: data,
                success: function (response){
                    //Preguntamos si tiene información
                    if (Object.keys(response).length > 0 ){
                        //Mostramos el nombre y apellidos del empleado
                        $('#nombre_empleado').val(response.nombre + ' '+ response.apellidos);
                        $('#empleado_id').val(response.id);
                    }
                    else{
                        //Si no existe el empleado, limpiamos los campos del nombre y del id del empleado
                        $('#nombre_empleado').val("");
                        $('#empleado_id').val("");
                    }
                },
                error: function (){
                    //Mostramos mensaje de error
                    toastr['error']('No se ha podido obtener los datos del empleado', "Error!");
                }
            });
        }
        else{
            //Si no han pasado datos para buscar, limpiamos el campo del nombre y su id
            $('#nombre_empleado').val("");
            $('#empleado_id').val("");
        }
    }
    // ****************************
    // BUSCAR UN ID DE EMPLEADO
    // ****************************
    function buscarRegistro(id){

        //Preparamos los datos para llamar a la función Ajax
        let url = "{{ route('empleados.buscarRegistro') }}"
        let data = { 'id': id};

        //Llamamos a Ajax para comprobar si existe el empleado y si existe, obtenemos sus datos
        $.ajax({
            type: 'get',
            url: url,
            data: data,
            success: function (response){
                //Preguntamos si tiene información
                if (Object.keys(response).length > 0 ){
                    //Mostramos el nombre y apellidos del empleado
                    $('#nombre_empleado').val(response.nombre + ' '+ response.apellidos);
                    $('#empleado_id').val(response.id);
                    $('#numero_empleado').val(response.numero);
                }
                else{
                    //Si no existe el empleado, limpiamos los campos del nombre y del id del empleado
                    $('#nombre_empleado').val("");
                    $('#empleado_id').val("");
                    $('#numero_empleado').val("");
                }
            },
            error: function (){
                //Mostramos mensaje de error
                toastr['error']('No se ha podido obtener los datos del empleado', "Error!");
            }
        });
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
        //Ponemos el foco en el numero del empleado
        $('#numero_empleado').focus();
    }
    //***************************************************/
    // EDITAR: función para preparar la edición registro /
    //***************************************************/
    //Localiza el registro en la BD, llama a la función de cargar los campos de la ventana y
    //muestra la ventana modal
    function editar(id){
            let url_ = "{{ route('ayudas.buscarRegistro') }}"
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
                url: '{{ route('ayudas.eliminar') }}',
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
            url = "{{ route('ayudas.nuevo') }}"
        }
        else {
            url = "{{ route('ayudas.actualizar') }}"
        }
        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
                    'id':$('#id_').val(),
                    'mes':mes,
                    'ano':ano,
                    'empleado_id':$('#empleado_id').val(),
                    'gasolina':$('#gasolina').val(),
                    'juzgados':$('#juzgados').val(),
                    'baja_enfermedad':$('#baja_enfermedad').val(),
                    'baja_accidente':$('#baja_accidente').val(),
                    'inspecciones':$('#inspecciones').val(),
                    'minusvalia':$('#minusvalia').val(),
                    'otros':$('#otros').val(),
        };
        $.ajax({
            data:  data,
            url: url,
            type:  'post',
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla.api().ajax.reload( null, false );
                //Mostramos el mensaje de que se ha actualizado o insertado el registro
                if (data.id === ''){
                    //Sacamos mensaje
                    toastr.success('El registro se ha insertado');
                }
                else {
                    //Sacamos mensaje
                    toastr.success('El registro se ha actualizado');
                    //Ocultamos el modal
                    $('#form').modal('hide');
                }
            },
        });
        //Limpiamos los campos para que se puedan seguir grabando más ayudas
        limpiaCampos();
        //Ponemos el foco en el número del empleado
        $('#numero_empleado').focus();
    }

    //********************************************************************/
    // CARGADATOS: función para mostrar los datos obtenidos en los campos /
    //********************************************************************/
    //Funcion colocar los datos obtenidos en los campos de la ventana
    function cargaDatos(response){
        $('#id_').val(response['id']);
        //Llamamos a la busqueda del empleado por id
        buscarRegistro(response['empleado_id']);
        $('#gasolina').val(response['gasolina']);
        $('#juzgados').val(response['juzgados']);
        $('#baja_enfermedad').val(response['baja_enfermedad']);
        $('#baja_accidente').val(response['baja_accidente']);
        $('#inspecciones').val(response['inspecciones']);
        $('#minusvalia').val(response['minusvalia']);
        $('#otros').val(response['otros']);
        //Llamamos a la función que nos calcula el total de la ayudas del empleado
        actualizaTotal();
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');

    }
    //************************************************************/
    // LIMPIACAMPOS: función para mostrar la lista de las ayudas /
    //************************************************************/
    //Funcion para limpiar los campos de la ventana
    function limpiaCampos(){
        //Montamos el título de la ventana
        $('#titulo').html('Ayudas del mes de '+meses[$('#periodo').val().substr(5,2) -1 ] + ' de ' + $('#periodo').val().substr(0,4));
        //Limpiamos los campos de la ventana
        // $('#empleado_id').val('').trigger('change.select2');
        $('#numero_empleado').val('');
        $('#nombre_empleado').val('');
        $('#id_').val('');
        $('#gasolina').val('');
        $('#juzgados').val('');
        $('#baja_enfermedad').val('');
        $('#baja_accidente').val('');
        $('#inspecciones').val('');
        $('#minusvalia').val('');
        $('#otros').val('');
        $('#total').html('');
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

        //EMPLEADO (obligatorio)
        if ($('#empleado_id').val()==''){
            toastr.error('Se tiene que seleccionar un empleado');
            ret = false;
        }

        //TOTAL (ha de tener algo)
        if ($('#total').html()=='' || $('#total').html()=='€'){
            toastr.error('No se puede grabar una ayuda sin importe');
            ret = false;
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    //*************************************************************************/
    // ACTUALIZATOTAL: función para actualizar el importe total de las ayudas  /
    //*************************************************************************/
    function actualizaTotal() {
        let total = 0;
        total = parseFloat(total) + ($('#gasolina').val() != '' ? parseFloat($('#gasolina').val()) : 0);
        total = parseFloat(total) + ($('#juzgados').val() != '' ? parseFloat($('#juzgados').val()) : 0);
        total = parseFloat(total) + ($('#baja_enfermedad').val() != '' ? parseFloat($('#baja_enfermedad').val()) : 0);
        total = parseFloat(total) + ($('#baja_accidente').val() != '' ? parseFloat($('#baja_accidente').val()) : 0);
        total = parseFloat(total) + ($('#inspecciones').val() != '' ? parseFloat($('#inspecciones').val()) : 0);
        total = parseFloat(total) + ($('#minusvalia').val() != '' ? parseFloat($('#minusvalia').val()) : 0);
        total = parseFloat(total) + ($('#otros').val() != '' ? parseFloat($('#otros').val()) : 0);
        //Mostramos el total en el campo de salida simpre que tenga importe
        if (total>0){
            // $('#total').html('<strong>'+total.toLocaleString("es-ES")+"€"+'</strong>');
            $('#total').html('<strong>'+new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(total)+'</strong>');
        }else{
            $('#total').html('');
        }
        // $('#total').html(total+"€");
    }

    //***************************************************/
    // IMPRIMIR: función para la impresión de las ayudas /
    //***************************************************/
    function imprimir(){
        // Abrir nuevo tab
        var win = window.open(url, '_blank');
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();

        let periodo = $('#periodo').val();

        $.ajax({
            data:  {"periodo":periodo},
            url: '{{ route('ayudas.imprimir') }}',
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
