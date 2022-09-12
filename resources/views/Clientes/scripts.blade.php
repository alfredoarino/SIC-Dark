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
    let tabla_clientes;                 //Tabla principal de los clientes

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
        Inicialización de la combo de los sectores
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let ss = $("#sector_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir sector",
            allowClear: true
        });
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>****
        Inicialización de la combo de las formas de pago
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*****/
        let sf = $("#forma_pago_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir la forma de pago",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de datos de los clientes
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla_clientes = $('#tabla_clientes').dataTable( {
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
                url: "{{route('clientes.lista')}}",
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'razonsocial'},
                {data: 'cif'},
                {data: 'delegacion'},
                {data: 'empresa'},
                {data: 'telefono'},
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
        } );

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón añadir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_add', function () {
            //Llamamos a la función de nuevo registro
            nuevo();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón editar (en la lista de los clientes)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_clientes tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla_clientes.api().row($(this).parents()).data();
            //Llamamos a la función para obtener los datos via Ajax
            editar(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (en la lista de los clientes
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_clientes tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla_clientes.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el cliente!",
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
        //Deshabilitamos las pestañas de contactos y acciones
        $('#icon-contactos-tab').addClass('disabled');
        $('#icon-acciones-tab').addClass('disabled');
        //Activamos la pestaña de los datos
        $('#icon-datos-tab').click();
        //Mostramos la ventana modal
        $('#form').modal('show');
        //Ponemos el foco en el numero del empleado
        $('#razonsocial').focus();
    }

    //*********************************************************/
    // EDITAR: función para preparar los datos para su edición /
    //*********************************************************/
    function editar(id){
            let url_ = "{{ route('clientes.buscarRegistro') }}"
            $.ajax({
                data:  {"id":id},
                url: url_,
                type:  'get',
                success:  function (response) {
                    //Cargamos los datos obtenidos en los campos del modal
                    cargaDatos(response);
                    //Habilitamos las pestañas de contactos y acciones
                    $('#icon-contactos-tab').removeClass('disabled');
                    $('#icon-acciones-tab').removeClass('disabled');
                    //Activamos la pestaña de los datos generales y mostramos la ventana modal
                    //Pestaña
                    $('#icon-datos-tab').click();
                    //Modal
                    $('#form').modal('show');
                },
            });
    }
    //*******************************************************/
    // ELIMINAR: función para llamar al borrado del registro /
    //*******************************************************/
    function eliminar(id){
            let url_ = "{{ route('clientes.eliminar') }}"
            let _token = '{{ csrf_token() }}';
            $.ajax({
                data:  {"_token":_token,"id":id},
                url: url_,
                type:  'post',
                success:  function (response) {
                    //Actualizamos los datos de la tabla
                    tabla_clientes.api().ajax.reload( null, false );
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
            url = "{{ route('clientes.nuevo') }}"
        }
        else {
            url = "{{ route('clientes.actualizar') }}"
        }

        //COMO TRATAMOS CON IMÁGENES, DEBEMOS GENERAR UN FORMDATA Y AÑADIR LOS ELEMENTOS RESTANTES DEL REGISTRO
        let data = new FormData();
        data.append('_token','{{ csrf_token() }}');
        data.append('id',$('#id_').val());
        data.append('razonsocial',$('#razonsocial').val());
        data.append('cif',$('#cif').val());
        data.append('direccion',$('#direccion').val());
        data.append('poblacion',$('#poblacion').val());
        data.append('provincia',$('#provincia').val());
        data.append('cp',$('#cp').val());
        data.append('email',$('#email').val());
        data.append('telefono',$('#telefono').val());
        data.append('cuentacontable',$('#cuentacontable').val());
        data.append('delegacion_id',$('#delegacion_id').val());
        data.append('empresa_id',$('#empresa_id').val());
        data.append('sector_id',$('#sector_id').val());
        data.append('forma_pago_id',$('#forma_pago_id').val());
        data.append('facturas_conjuntas',($('#facturas_conjuntas').prop('checked')?1:0));
        data.append('factura_electronica',($('#factura_electronica').prop('checked')?1:0));
        data.append('observaciones',$('#observaciones').val());

        $.ajax({
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:  data,
            url: url,
            type:  'POST',
            contentType: false,         //Necesario para el tratamiento de las imágenes
            processData: false,         //Necesario para el tratamiento de las imágenes
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla_clientes.api().ajax.reload( null, false );
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

    //******************************************************/
    // CARGADATOS: función para mostrar los datos obtenidos /
    //******************************************************/
    function cargaDatos(response){
        $('#titulo').html(response['razonsocial'])
        $('#razonsocial').val(response['razonsocial'])
        $('#id_').val(response['id']);
        $('#cif').val(response['cif']);
        $('#direccion').val(response['direccion']);
        $('#poblacion').val(response['poblacion']);
        $('#provincia').val(response['provincia']);
        $('#cp').val(response['cp']);
        $('#email').val(response['email']);
        $('#telefono').val(response['telefono']);
        $('#cuentacontable').val(response['cuentacontable']);
        $('#delegacion_id').val(response['delegacion_id']).trigger('change.select2');
        $('#empresa_id').val(response['empresa_id']).trigger('change.select2');
        $('#sector_id').val(response['sector_id']).trigger('change.select2');
        $('#forma_pago_id').val(response['forma_pago_id']).trigger('change.select2');
        $('#facturas_conjuntas').prop('checked',(response['facturas_conjuntas']==1 ? true : false));
        $('#factura_electronica').prop('checked',(response['factura_electronica']==1 ? true : false));
        $('#observaciones').val(response['observaciones']);
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');

    }
    //*************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos de la ventana /
    //*************************************************************/
    function limpiaCampos(){
        $('#titulo').html('Nuevo cliente')
        $('#razonsocial').val('');
        $('#id_').val('');
        $('#cif').val('');
        $('#direccion').val('');
        $('#poblacion').val('');
        $('#provincia').val('');
        $('#cp').val('');
        $('#email').val('');
        $('#telefono').val('');
        $('#cuentacontable').val('');
        $('#delegacion_id').val('').trigger('change.select2');
        $('#empresa_id').val('').trigger('change.select2');
        $('#sector_id').val('').trigger('change.select2');
        $('#forma_pago_id').val('').trigger('change.select2');
        $('#facturas_conjuntas').prop('checked',false);
        $('#factura_electronica').prop('checked',false);
        $('#observaciones').val('');

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

        //RAZON SOCIAL (obligatorio)
        if ($('#razonsocial').val()==''){
            toastr.error('Se tiene que introducir la razón social');
            $('#razonsocial').focus();
            ret = false;
        }
        //CIF (obligatorio)
        if ($('#cif').val()==''){
            toastr.error('Se tiene que introducir el CIF del cliente');
            $('#cif').focus();
            ret = false;
        }
        //TELÉFONO (obligatorio)
        if ($('#telefono').val()==''){
            toastr.error('Se tiene que introducir un teléfono');
            $('#telefono').focus();
            ret = false;
        }
        //DIRECCION (obligatorio)
        if ($('#direccion').val()==''){
            toastr.error('Se tiene que introducir una dirección');
            $('#direccion').focus();
            ret = false;
        }
        //POBLACION (obligatorio)
        if ($('#poblacion').val()==''){
            toastr.error('Se tiene que introducir una población');
            $('#poblacion').focus();
            ret = false;
        }
        //PROVINCIA (obligatorio)
        if ($('#provincia').val()==''){
            toastr.error('Se tiene que introducir una provincia');
            $('#provincia').focus();
            ret = false;
        }
        //CP (obligatorio)
        if ($('#cp').val()==''){
            toastr.error('Se tiene que introducir un código postal');
            $('#cp').focus();
            ret = false;
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
        //SECTOR (obligatorio)
        if ($('#sector_id').val()==''){
            toastr.error('Se tiene que seleccionar un sector');
            ret = false;
        }
        //FORMA DE PAGO (obligatorio)
        if ($('#forma_pago_id').val()==''){
            toastr.error('Se tiene que seleccionar una forma de pago');
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
        //CUENTA CONTABLE (obligatoria)
        if ($('#cuentacontable').val()==''){
            toastr.error('Se tiene que introducir una cuenta contable');
            $('#cuentacontable').focus();
            ret = false;
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
