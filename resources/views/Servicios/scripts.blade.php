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
        $('#menuAdministracion').addClass('active');
        $('#menuContabilidad').removeClass('active');
        $('#menuGerencia').removeClass('active');
        $('#menuInspeccion').removeClass('active');
        $('#menuSoporte').removeClass('active');

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Limitamos el número de dígitos de los campos numéricos
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#copias').on('input', function () {
            if (this.value.length > 2) {
                this.value = this.value.slice(0,2);
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los delegaciones
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sd = $("#delegacion_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir delegación",
            allowClear: true
        });
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de las empresas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let se = $("#empresa_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir empresa",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los clientes
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sc = $("#cliente_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir cliente",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los pagos
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sp = $("#pago_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir tipo pago",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los tipos de tarifa
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let st = $("#tipo_tarifa").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir tipo tarifa",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de los servicios
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla = $('#tabla_servicios').dataTable( {
            'destroy': true,
            // 'processing': true,
            'responsive': true,
            'autoWidth': false,
            "dom": '<"top"l>rt<"row"<"bottom col-sm-4"i><"col-sm-4 text-center"p>>',   // Elemento de la tabla - quitamos la búsqueda general (ver DOM en la pagina oficial)
            "oLanguage": {
                "sLoadingRecords": "Por favor espera - Cargando información...",
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
                url: "{{route('servicios.lista')}}",
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'numero'},
                {data: 'nombre'},
                {data: 'cliente'},
                {data: 'empresa'},
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
                {"targets": [1,2,3,4,5,6], "className": "text-center"},
            ],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7, 10,50],
            'pageLength': 7,
            "order": [[ 1, "asc" ]],
        } );

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón añadir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_add', function () {
            //Llamamos a la funcion de nuevo registro
            nuevo();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón editar (en la lista)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_servicios tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la funcion para obtener los datos via Ajax
            editar(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (en la lista)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_servicios tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el servicio!",
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
            Control del cambio de imagen
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#imagen').change(function(){
            //Para mostrar la imagen seleccionada
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#imagenSeleccionada').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Checkbox de los servicios sin movimientos
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#sin_movimientos', function () {
            //Si se activa el switch mostramos el campo del concepto en la factura
            if ($('#sin_movimientos').prop('checked')){
                $('#div_concepto_factura').show();
            }
            else{
                //Limpiamos el campo y lo ocultamos
                $('#concepto_factura').val('');
                $('#div_concepto_factura').hide();
            }
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


    /*>>>>>>>>>>>>>>>>>>>>>>
        INICIO: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>*/

    //****************************************************************/
    // NUEVO: función para preparar la inserción de un nuevo registro /
    //****************************************************************/
    function nuevo(){
        //Limpiamos los campos de la ventana
        limpiaCampos();
        //Mostramos la ventana modal
        $('#form').modal('show');
        //Ponemos el foco en el numero del servicio
        $('#nombre').focus();
    }

    //*********************************************************/
    // EDITAR: función para preparar los datos para su edición /
    //*********************************************************/
    function editar(id){
            let url_ = "{{ route('servicios.buscarRegistro') }}"
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

    //*******************************************************/
    // ELIMINAR: función para llamar al borrado del registro /
    //*******************************************************/
    function eliminar(id){
            let url_ = "{{ route('servicios.eliminar') }}"
            let _token = '{{ csrf_token() }}';
            $.ajax({
                data:  {"_token":_token,"id":id},
                url: url_,
                type:  'post',
                success:  function (response) {
                    //Actualizamos los datos de la tabla
                    tabla.api().ajax.reload( null, false );
                    //Mostramos el mensaje de que se ha borrado el registro
                    toastr.success('El registro se ha eliminado');
                },
            });
    }

    //**************************************************/
    // GRABAREGISTRO: función la grabación del registro /
    //**************************************************/
    function grabaRegistro(){
        //Almacenamos los datos del formulario en un array
        let url;
        if ($('#id_').val()===''){
            url = "{{ route('servicios.nuevo') }}"
        }
        else {
            url = "{{ route('servicios.actualizar') }}"
        }

        //COMO TRATAMOS CON IMÁGENES, DEBEMOS GENERAR UN FORMDATA Y AÑADIR LOS ELEMENTOS RESTANTES DEL REGISTRO
        let data = new FormData();
        data.append('_token','{{ csrf_token() }}');
        data.append('id',$('#id_').val());
        data.append('numero',$('#numero').val());
        data.append('nombre',$('#nombre').val());
        data.append('nombre_reducido',$('#nombre_reducido').val());
        data.append('cliente_id',$('#cliente_id').val());
        data.append('empresa_id',$('#empresa_id').val());
        data.append('delegacion_id',$('#delegacion_id').val());
        data.append('telefono',$('#telefono').val());
        data.append('direccion',$('#direccion').val());
        data.append('latitud',$('#latitud').val());
        data.append('longitud',$('#longitud').val());
        data.append('pago_id',$('#pago_id').val());
        data.append('tipo_tarifa',$('#tipo_tarifa').val());
        data.append('importe',$('#importe').val());
        data.append('copias',$('#copias').val());
        data.append('fecha_tarifa',$('#fecha_tarifa').val());
        data.append('serie',$('#serie').val());
        data.append('sin_movimientos',($('#sin_movimientos').prop('checked')?1:0));
        data.append('concepto_factura',$('#concepto_factura').val());
        data.append('plantilla',($('#plantilla').prop('checked')?1:0));
        data.append('factura_manual',($('#factura_manual').prop('checked')?1:0));
        data.append('activo',($('#activo').prop('checked')?1:0));
        data.append('ref_cliente',$('#ref_cliente').val());
        data.append('ref_nuestra',$('#ref_nuestra').val());

        $.ajax({
            data:  data,
            url: url,
            type:  'POST',
            contentType: false,         //Necesario para el tratamiento de las imágenes
            processData: false,         //Necesario para el tratamiento de las imágenes
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla.api().ajax.reload( null, false );
                //Mostramos el mensaje de que se ha actualizado o insertado el registro
                if ($('#id_').val()==''){
                    //Grabamos la serie que habíamos obtenido
                    let serie = $('#serie').val();
                    $.ajax({
                        data: {'_token': '{{ csrf_token() }}',"serie":serie},
                        url: "{{ route('series.nuevo') }}",
                        type: 'POST',
                        success: function (response) {
                            //Sacamos mensaje
                            toastr.success('El registro se ha insertado');
                            //Limpiamos los campos para que se puedan seguir grabando más ayudas
                            limpiaCampos();
                        }
                    });
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

    //*************************************************************/
    // BUSCADIRECCION: función para hacer la llamda a GOOGLE MAPS  /
    //*************************************************************/
    function buscarDireccion(){

            let url_ = "{{ route('servicios.buscarDireccion') }}";
            //Obtenemos el campo de la ventana
            let direccion = $('#direccion').val();
            //Comprobamos si ha introducido algo en el campo
            if (direccion.length > 0){
                //Hacemos la llamada AJAX a la dirección
                $.ajax({
                    data:  {"direccion":direccion},
                    url: url_,
                    type:  'get',
                    success:  function (response) {
                        //Vemos el resultado de la respuesta
                        if (response['estado']==0){
                            //Limpiamos los campos por que la dirección no existe
                            $('#latitud').val('');
                            $('#longitud').val('');
                            //Sacamos el mensaje de error
                            toastr.error('Se tiene que introducir una dirección válida');
                        }
                        else if (response['estado']==1){
                            //Mostramos la direccion
                            $('#latitud').val(response['latitud']);
                            $('#longitud').val(response['longitud']);
                            $('#direccion').val(response['direccion']);
                        }
                    },
                });
            }
            else{
                //Sacamos el mensaje de error
                $('#latitud').val('');
                $('#longitud').val('');
                toastr.error('Se tiene que introducir una dirección');
                $('#direccion').focus();
            }
    }

    //******************************************************/
    // CARGADATOS: función para mostrar los datos obtenidos /
    //******************************************************/
    function cargaDatos(response){
        $('#titulo').html(response['numero']+' - '+response['nombre'])
        $('#id_').val(response['id']);
        $('#numero').val(response['numero']);
        $('#nombre').val(response['nombre']);
        $('#nombre_reducido').val(response['nombre_reducido']);
        $('#cliente_id').val(response['cliente_id']).trigger('change.select2');
        $('#empresa_id').val(response['empresa_id']).trigger('change.select2');
        $('#delegacion_id').val(response['delegacion_id']).trigger('change.select2');
        $('#telefono').val(response['telefono']);
        $('#direccion').val(response['direccion']);
        $('#latitud').val(response['latitud']);
        $('#longitud').val(response['longitud']);
        $('#pago_id').val(response['pago_id']).trigger('change.select2');
        $('#tipo_tarifa').val(response['tipo_tarifa']).trigger('change.select2');
        $('#importe').val(response['importe']);
        $('#copias').val(response['copias']);
        $('#fecha_tarifa').val(response['fecha_tarifa']);
        $('#serie').val(response['serie']);
        $('#sin_movimientos').prop('checked',(response['sin_movimientos']==1 ? true : false));
        //Si es servicio sin movimientos, mostramos el div y el concepto en la factura
        if (response['sin_movimientos']==1){
            $('#div_concepto_factura').show();
            $('#concepto_factura').val(response['concepto_factura']);
        }
        else{
            $('#div_concepto_factura').hide();
            $('#concepto_factura').val('');
        }
        $('#plantilla').prop('checked',(response['plantilla']==1 ? true : false));
        $('#factura_manual').prop('checked',(response['factura_manual']==1 ? true : false));
        $('#activo').prop('checked',(response['activo']==1 ? true : false));
        $('#ref_cliente').val(response['ref_cliente']);
        $('#ref_nuestra').val(response['ref_nuestra']);
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');

    }
    //*************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos de la ventana /
    //*************************************************************/
    function limpiaCampos(){
        $('#titulo').html('Nuevo servicio')
        $('#id_').val('');
        $('#numero').val('');
        $('#nombre').val('');
        $('#nombre_reducido').val('');
        $('#cliente_id').val('').trigger('change.select2');
        $('#empresa_id').val('').trigger('change.select2');
        $('#delegacion_id').val('').trigger('change.select2');
        $('#telefono').val('');
        $('#direccion').val('');
        $('#latitud').val('');
        $('#longitud').val('');
        $('#pago_id').val('').trigger('change.select2');
        $('#tipo_tarifa').val('').trigger('change.select2');
        $('#importe').val('');
        $('#copias').val('');
        $('#fecha_tarifa').val('');
        $('#serie').val('');
        $('#contrato').val('');
        $('#sin_movimientos').prop('checked',false);
        $('#concepto_factura').val('');
        $('#plantilla').prop('checked',false);
        $('#factura_manual').prop('checked',false);
        $('#activo').prop('checked',false);
        $('#ref_cliente').val('');
        $('#ref_nuestra').val('');
        //Ocultamos el div del concepto de la factura
        $('#div_concepto_factura').hide();
        //Ponemos el texto del botón 'grabar'
        $('#btn_salvar').html('Grabar');
        //Obtenemos la serie nueva y le número nuevo del servicio
        obtenerNumeroServicio();
        obtenerSerie();
    }

    //******************************************************************/
    // VALIDACAMPOSMODAL: función para validar los campos de la ventana /
    //******************************************************************/
    //Valida los campos de la ventana MODAL
    function validaCamposModal(){
        //Variable auxiliar
        let ret = true;

        //NOMBRE (obligatorio)
        if ($('#nombre').val()==''){
            toastr.error('Se tiene que introducir el nombre del servicio');
            $('#nombre').focus();
            ret = false;
        }
        //ABREVIADO (obligatorio)
        if ($('#nombre_reducido').val()==''){
            toastr.error('Se tiene que introducir el abreviado del servicio');
            $('#nombre_reducido').focus();
            ret = false;
        }
        //CLIENTE (obligatorio)
        if ($('#cliente_id').val()==''){
            toastr.error('Se tiene que seleccionar el cliente relacionado con el servicio');
            ret = false;
        }
        //EMPRESA (obligatorio)
        if ($('#empresa_id').val()==''){
            toastr.error('Se tiene que seleccionar una empresa');
            ret = false;
        }
        //DELEGACION (obligatorio)
        if ($('#delegacion_id').val()==''){
            toastr.error('Se tiene que seleccionar una delegación');
            ret = false;
        }
        //TELEFONO (obligatorio)
        if ($('#telefono').val()==''){
            toastr.error('Se tiene que introducir un teléfono');
            $('#telefono').focus();
            ret = false;
        }
        //DIRECCION (obligatorio)
        if ($('#latitud').val()==''){
            toastr.error('Se tiene que introducir una dirección');
            $('#direccion').focus();
            ret = false;
        }
        //TIPO DE PAGO (obligatorio)
        if ($('#pago_id').val()==''){
            toastr.error('Se tiene que introducir el pago a empleados');
            $('#pago_id').focus();
            ret = false;
        }
        //TIPO DE TARIFA (obligatorio)
        if ($('#tipo_tarifa').val()==''){
            toastr.error('Se tiene que introducir el tipo de tarifa');
            $('#tipo_tarifa').focus();
            ret = false;
        }
        //IMPORTE (Obligatorio y > 0)
        if ($('#importe').val()=='' || $('#importe').val()<=0){
            toastr.error('Se tiene que introducir un importe');
            $('#importe').focus();
            ret = false;
        }
        //COPIAS (Obligatorio y > 0)
        if ($('#copias').val()=='' || $('#copias').val()<=0){
            toastr.error('Se tiene que introducir el número de copias');
            $('#copias').focus();
            ret = false;
        }
        //FECHA TARIFA (Obligatorio)
        if ($('#fecha_tarifa').val()==''){
            toastr.error('Se tiene que introducir la fecha de la tarifa');
            $('#fecha_tarifa').focus();
            ret = false;
        }
        //CONCEPTO FACTURA (obligatorio si el check de sin movimientos está activado)
        if ($('#sin_movimientos').prop('checked',true) &&  $('#concepto_factura').val()==''){
            toastr.error('Se tiene que introducir el concepto de la factura');
            $('#concepto_factura').focus();
            ret = false;
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    //*******************************************************************************/
    // OBTENERNUMEROSERVICIO: función para obtener el número siguiente de servicio   /
    //*******************************************************************************/
    function obtenerNumeroServicio() {

        let url_ = "{{ route('servicios.obtenerNumeroServicio') }}"
        let numero = 0;
        $.ajax({
            url: url_,
            type:  'get',
            success:  function (response) {
                //Obtenemos el valor
                $('#numero').val(response);
            },
        });
    }

    //******************************************************/
    // OBTENERSERIE: función para obtener una nueva serie  /
    //*****************************************************/
    function obtenerSerie() {
        let url_ = "{{ route('servicios.obtenerSerie') }}"
        let serie = "";
        $.ajax({
            url: url_,
            type:  'get',
            success:  function (response) {
                //Obtenemos el valor
                $('#serie').val(response);
            },
        });
    }

    //*******************************************************/
    // FN_VALIDATEIBAN: función para validar el código IBAN  /
    //*******************************************************/
    function fn_ValidateIBAN(IBAN) {

        //Se pasa a Mayusculas
        IBAN = IBAN.toUpperCase();
        //Se quita los blancos de principio y final.
        IBAN = IBAN.trim();
        IBAN = IBAN.replace(/\s/g, ""); //Y se quita los espacios en blanco dentro de la cadena

        let letra1,letra2,num1,num2;
        let isbanaux;
        let numeroSustitucion;
        //La longitud debe ser siempre de 24 caracteres
        if (IBAN.length != 24) {
            return false;
        }

        // Se coge las primeras dos letras y se pasan a números
        letra1 = IBAN.substring(0, 1);
        letra2 = IBAN.substring(1, 2);
        num1 = getnumIBAN(letra1);
        num2 = getnumIBAN(letra2);
        //Se sustituye las letras por números.
        isbanaux = String(num1) + String(num2) + IBAN.substring(2);
        // Se mueve los 6 primeros caracteres al final de la cadena.
        isbanaux = isbanaux.substring(6) + isbanaux.substring(0,6);

        //Se calcula el resto, llamando a la función modulo97, definida más abajo
        resto = modulo97(isbanaux);
        if (resto == 1){
            return true;
        }else{
            return false;
        }
    }

    function modulo97(iban) {
        let parts = Math.ceil(iban.length/7);
        let remainer = "";

        for (var i = 1; i <= parts; i++) {
            remainer = String(parseFloat(remainer+iban.substr((i-1)*7, 7))%97);
        }

        return remainer;
    }

    function getnumIBAN(letra) {
        ls_letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return ls_letras.search(letra) + 10;
    }

    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
           FIN: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
</script>

{{--***********************--}}
{{--FIN: SCRIPTS PROPIOS   --}}
{{--***********************--}}
