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

        // //Cambiamos el indicador del menú que está seleccionado
        // $('#menuHome').removeClass('active');
        // $('#menuAdministracion').addClass('active');
        // $('#menuContabilidad').removeClass('active');
        // $('#menuGerencia').removeClass('active');
        // $('#menuInspeccion').removeClass('active');
        // $('#menuSoporte').removeClass('active');

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
        Inicialización de la combo de los convenios
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sc = $("#convenio_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir convenio",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de los empleados
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla = $('#tabla_empleados').dataTable( {
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
                url: "{{route('empleados.lista')}}",
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'numero'},
                {data: 'nombre'},
                {data: 'apellidos'},
                {data: 'dni'},
                {data: 'telefono'},
                {data: 'tip'},
                {
                    data: 'activo', render: function (data) {
                        return (data==1?'Sí':'No');
                    }
                },
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
                {"targets": [1,2,3,4,5,6,7,8], "className": "text-center"},
            ],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7, 10,50],
            'pageLength': 7,
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
        $('#tabla_empleados tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la funcion para obtener los datos via Ajax
            editar(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (en la lista)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_empleados tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el empleado!",
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
            Checkbox cobro por transferencia
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#cobro_transferencia', function () {
            //Si se activa el switch activamos el campo de la cuenta bancaria
            if ($('#cobro_transferencia').prop('checked')){
                $('#cuenta_bancaria').prop('readonly',false);
            }
            else{
                //Limpiamos el campo y lo ponemos como solo de lectura
                $('#cuenta_bancaria').val('');
                $('#cuenta_bancaria').prop('readonly',true);
            }
        });
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Control del cambio de imagen
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#imagen').change(function(){
            //Para mostrar la imagen seleccionada
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#imagenSeleccionada').prop('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
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
        //Ponemos el foco en el numero del empleado
        $('#numero').focus();
    }

    //*********************************************************/
    // EDITAR: función para preparar los datos para su edición /
    //*********************************************************/
    function editar(id){
            let url_ = "{{ route('empleados.buscarRegistro') }}"
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
            let url_ = "{{ route('empleados.eliminar') }}"
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
            url = "{{ route('empleados.nuevo') }}"
        }
        else {
            url = "{{ route('empleados.actualizar') }}"
        }

        //COMO TRATAMOS CON IMÁGENES, DEBEMOS GENERAR UN FORMDATA Y AÑADIR LOS ELEMENTOS RESTANTES DEL REGISTRO
        let data = new FormData();
        data.append('_token','{{ csrf_token() }}');
        data.append('id',$('#id_').val());
        data.append('numero',$('#numero').val());
        data.append('nombre',$('#nombre').val());
        data.append('apellidos',$('#apellidos').val());
        data.append('dni',$('#dni').val());
        data.append('delegacion_id',$('#delegacion_id').val());
        data.append('empresa_id',$('#empresa_id').val());
        data.append('convenio_id',$('#convenio_id').val());
        data.append('telefono',$('#telefono').val());
        data.append('telefono2',$('#telefono2').val());
        data.append('direccion',$('#direccion').val());
        data.append('latitud',$('#latitud').val());
        data.append('longitud',$('#longitud').val());
        data.append('fecha_alta',$('#fecha_alta').val());
        data.append('fecha_nacimiento',$('#fecha_nacimiento').val());
        data.append('email',$('#email').val());
        data.append('tip',$('#tip').val());
        data.append('operativo',($('#operativo').prop('checked')?1:0));
        data.append('licencia_arma',($('#licencia_arma').prop('checked')?1:0));
        data.append('vehiculo',($('#vehiculo_propio').prop('checked')?1:0));
        data.append('cobro_transferencia',($('#cobro_transferencia').prop('checked')?1:0));
        data.append('cuenta_bancaria',$('#cuenta_bancaria').val());
        data.append('activo',($('#activo').prop('checked')?1:0));
        //Obtenemos el fichero que se haya insertado en el campo
        data.append('imagen',$('#imagen').prop('files')[0]);

        $.ajax({
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')},
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
                    //Sacamos mensaje
                    toastr.success('El registro se ha insertado');
                    //Limpiamos los campos para que se puedan seguir grabando más ayudas
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

    //*************************************************************/
    // BUSCADIRECCION: función para hacer la llamda a GOOGLE MAPS  /
    //*************************************************************/
    function buscarDireccion(){

            let url_ = "{{ route('empleados.buscarDireccion') }}";
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
        $('#titulo').html(response['numero']+' - '+response['nombre']+' '+response['apellidos'])
        $('#id_').val(response['id']);
        $('#numero').val(response['numero']);
        //Cambiamos el atributo de readonly del campo numero
        $('#numero').prop('readonly',true);
        $('#nombre').val(response['nombre']);
        $('#apellidos').val(response['apellidos']);
        $('#dni').val(response['dni']);
        $('#delegacion_id').val(response['delegacion_id']).trigger('change.select2');
        $('#empresa_id').val(response['empresa_id']).trigger('change.select2');
        $('#convenio_id').val(response['convenio_id']).trigger('change.select2');
        $('#telefono').val(response['telefono']);
        $('#telefono2').val(response['telefono2']);
        $('#direccion').val(response['direccion']);
        $('#latitud').val(response['latitud']);
        $('#longitud').val(response['longitud']);
        $('#fecha_alta').val(response['fecha_alta']);
        $('#fecha_nacimiento').val(response['fecha_nacimiento']);
        $('#email').val(response['email']);
        $('#tip').val(response['tip']);
        $('#cuenta_bancaria').val(response['cuenta_bancaria']);
        $('#activo').prop('checked',(response['activo']==1 ? true : false));
        $('#operativo').prop('checked',(response['operativo']==1 ? true : false));
        $('#licencia_arma').prop('checked',(response['licencia_arma']==1 ? true : false));
        $('#vehiculo_propio').prop('checked',(response['vehiculo']==1 ? true : false));
        $('#cobro_transferencia').prop('checked',(response['cobro_transferencia']==1 ? true : false));
        //Si hay imagen guardada, la mostramos
        if (response['imagen']!= null){
            $('#imagenSeleccionada').prop('src','/fotos/'+response['imagen']);
        }
        else{
            //Si no hay foto, limpiamos
            $('#imagenSeleccionada').prop('src','');
        }
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');

    }
    //*************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos de la ventana /
    //*************************************************************/
    function limpiaCampos(){
        $('#titulo').html('Nuevo empleado')
        $('#id_').val('');
        $('#numero').val('');
        //Cambiamos el atributo de readonly del campo numero
        $('#numero').prop('readonly',false);
        $('#nombre').val('');
        $('#apellidos').val('');
        $('#dni').val('');
        $('#delegacion_id').val('').trigger('change.select2');
        $('#empresa_id').val('').trigger('change.select2');
        $('#convenio_id').val('').trigger('change.select2');
        $('#telefono').val('');
        $('#telefono2').val('');
        $('#direccion').val('');
        $('#latitud').val('');
        $('#longitud').val('');
        $('#fecha_alta').val('');
        $('#fecha_nacimiento').val('');
        $('#email').val('');
        $('#tip').val('');
        $('#cuenta_bancaria').val('');
        $('#activo').prop('checked',false);
        $('#operativo').prop('checked',false);
        $('#licencia_arma').prop('checked',false);
        $('#vehiculo_propio').prop('checked',false);
        $('#cobro_transferencia').prop('checked',false);
        // Limpiamos la imagen que pudiese haber
        $('#imagenSeleccionada').prop('src', '');
        $('#imagen').val('');
        //Ponemos el texto del botón 'grabar'
        $('#btn_salvar').html('Grabar');
    }

    //******************************************************************/
    // VALIDACAMPOSMODAL: función para validar los campos de la ventana /
    //******************************************************************/
    //Valida los campos de la ventana MODAL
    function validaCamposModal(){
        //Variable auxiliar
        let ret = true;

        //Comprobamos que si se trata de un alta
        if ($('#id_').val()=='') {
            //Validamos que el numero del empleado  introducido no esté activo en la BD
            let url_ = "{{route('empleados.buscarEmpleado')}}";
            //Obtenemos el campo de la ventana
            let numero = $('#numero').val();
            //Comprobamos si ha introducido algo en el campo y que sea numérico
            if (numero.length > 0 && Number.isInteger(parseInt(numero))) {
                //Hacemos la llamada AJAX a la dirección
                $.ajax({
                    data: {"numero": numero},
                    url: url_,
                    type: 'get',
                    success: function (response) {
                        //Vemos el resultado de la respuesta
                        if (response.id != undefined) {
                            //Si ha encontrado algún empleado con ese número y está activo
                            //Sacamos el mensaje de error
                            toastr.error('El número de empleado ya existe y está activo');
                            $('#numero').focus();
                            ret = false;
                        }
                    },
                });
            }
            else {
                toastr.error('Se tiene que introducir un número de empleado');
                $('#numero').focus();
                ret = false;
            }
        }
        //Validamos el resto de los campos de la ventana
        //NOMBRE (obligatorio)
        if ($('#nombre').val()==''){
            toastr.error('Se tiene que introducir el nombre del empleado');
            $('#nombre').focus();
            ret = false;
        }
        //APELLIDOS (obligatorio)
        if ($('#apellidos').val()==''){
            toastr.error('Se tiene que introducir los apellidos del empleado');
            $('#apellidos').focus();
            ret = false;
        }
        //DNI (obligatorio)
        if ($('#dni').val()==''){
            toastr.error('Se tiene que introducir el DNI del empleado');
            $('#dni').focus();
            ret = false;
        }
        //Validamos que el DNI o NIE es correcto
        else{
            if (validarDniNie($('#dni').val()) == false) {
                toastr.error('DNI o NIE incorrecto');
                $('#dni').focus();
                ret = false;
            }
        }
        //DELEGACION (obligatorio)
        if ($('#delegacion_id').val()==''){
            toastr.error('Se tiene que seleccionar una delegación');
            ret = false;
        }
        //EMPRESA (obligatorio)
        if ($('#empresa_id').val()==''){
            toastr.error('Se tiene que seleccionar una empresa');
            ret = false;
        }
        //CONVENIO (obligatorio)
        if ($('#covenio_id').val()==''){
            toastr.error('Se tiene que seleccionar un convenio');
            ret = false;
        }
        //TELEFONO (obligatorio)
        if ($('#telefono').val()=='' && $('#telefono2').val()==''){
            toastr.error('Se tiene que introducir al menos un teléfono');
            $('#telefono').focus();
            ret = false;
        }
        //DIRECCION (obligatorio)
        if ($('#latitud').val()==''){
            toastr.error('Se tiene que introducir una dirección');
            $('#direccion').focus();
            ret = false;
        }
        //FECHA DE ALTA (Obligatorio)
        if ($('#fecha_alta').val()==''){
            toastr.error('Se tiene que introducir la fecha de alta');
            $('#fecha_alta').focus();
            ret = false;
        }
        //FECHA DE NACIMIENTO (Obligatorio)
        if ($('#fecha_nacimiento').val()==''){
            toastr.error('Se tiene que introducir la fecha de nacimiento');
            $('#fecha_nacimiento').focus();
            ret = false;
        }
        //EMAIL (Obligatorio y valido)
        if ($('#email').val()==''){
            toastr.error('Se tiene que introducir el correo electrónico del empleado');
            $('#email').focus();
            ret = false;
        }
        else{
            let re=/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/
            if(!re.exec($('#email').val())){
                toastr.error('Se tiene que introducir el correo electrónico válido');
                $('#email').focus();
                ret = false;
            }
        }
        //CUENTA BANCARIA (obligatoria y valida)
        if ($('#cobro_transferencia').prop('checked') && $('#cuenta_bancaria').val() == ''){
            toastr.error('Se tiene que introducir una cuenta bancaria')
            $('#cuenta_bancaria').focus();
            ret = false;
        }
        else if ($('#cuenta_bancaria').val() != ''){
            //Validamos que la cuenta bancaria sea valida
            if (!fn_ValidateIBAN($('#cuenta_bancaria').val())){
                toastr.error('Cuenta bancaria incorrecta')
                $('#cuenta_bancaria').focus();
                ret = false;
            }
        }
        //Devolvemos el valor de retorno
        return ret;
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

    //****************************************************************/
    // VALIDARDNINIE: función para validar el DNBI o NIE introducido  /
    //****************************************************************/
    const validarDniNie = (value) => {
        let number, dni, letter;
        let expresion_regular_dni = /^[XYZ]?\d{5,8}[A-Z]$/;
        value = value.toUpperCase();
        if (expresion_regular_dni.test(value) === true) {
            number = value.substr(0, value.length - 1);
            number = number.replace('X', 0);
            number = number.replace('Y', 1);
            number = number.replace('Z', 2);
            dni = value.substr(value.length - 1, 1);
            number = number % 23;
            letter = 'TRWAGMYFPDXBNJZSQVHLCKET';
            letter = letter.substring(number, number + 1);
            if (letter != dni) {
                return false;
            } else {
                return true;
            }
        }else{
            return false;
        }
    }
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
           FIN: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
</script>

{{--***********************--}}
{{--FIN: SCRIPTS PROPIOS   --}}
{{--***********************--}}
