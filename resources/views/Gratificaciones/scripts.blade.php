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
        $('#menuGerencia').addClass('active');
        $('#menuInspeccion').removeClass('active');
        $('#menuSoporte').removeClass('active');

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de las gratificaciones
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let se = $("#empleado_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir empleado",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla de las gratificaciones
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla = $('#tabla_gratificaciones').dataTable( {
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
                url: "{{route('gratificaciones.lista')}}",
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'numero'},
                {data: 'nombre'},
                {data: 'apellidos'},
                {data: 'concepto'},
                {
                    data: 'importe', render: function (data) {
                        return (new Intl.NumberFormat("de-DE", {style: "currency", currency: "EUR"}).format(data));
                    }
                },
                {
                    data: 'aplica_descuento', render: function (data) {
                        return (data==1?'Sí':'No');
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
            //Limpiamos los campos de la ventana
            limpiaCampos();
            //Mostramos la ventana modal
            $('#form').modal('show');
            //Ponemos el foco en el numero del empleado
            $('#numero').focus();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón editar (en la lista)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_gratificaciones tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la funcion para obtener los datos via Ajax
            editar(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (en la lista)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_gratificaciones tbody').on('click','.eliminar', function(){
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
                $('#imagenSeleccionada').attr('src', e.target.result);
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

    //*********************************************************/
    // EDITAR: función para preparar los datos para su edición /
    //*********************************************************/
    function editar(id){
            let url_ = "{{ route('gratificaciones.buscarRegistro') }}"
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
            let url_ = "{{ route('gratificaciones.eliminar') }}"
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
            url = "{{ route('gratificaciones.nuevo') }}"
        }
        else {
            url = "{{ route('gratificaciones.actualizar') }}"
        }

        //COMO TRATAMOS CON IMÁGENES, DEBEMOS GENERAR UN FORMDATA Y AÑADIR LOS ELEMENTOS RESTANTES DEL REGISTRO
        let data = new FormData();
        data.append('_token','{{ csrf_token() }}');
        data.append('id',$('#id_').val());
        data.append('empleado_id',$('#empleado_id').val());
        data.append('concepto',$('#concepto').val());
        data.append('importe',$('#importe').val());
        data.append('aplica_descuento',($('#aplica_descuento').prop('checked')?1:0));

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
        //Colocamos los datos obtenidos
        $('#titulo').html('Editar gratificación')
        $('#id_').val(response['id']);
        $('#empleado_id').val(response['empleado_id']).trigger('change.select2');
        $('#concepto').val(response['concepto']);
        $('#importe').val(response['importe']);
        $('#aplica_descuento').prop('checked',(response['aplica_descuento']==1 ? true : false));

        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');

    }
    //*************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos de la ventana /
    //*************************************************************/
    function limpiaCampos(){
        $('#titulo').html('Nueva gratificación')
        $('#id_').val('');
        //Cambiamos el atributo de readonly del campo numero
        $('#empleado_id').val('').trigger('change.select2');
        $('#concepto').val('');
        $('#importe').val('');
        $('#aplica_descuento').prop('checked',false);
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

        //EMPLEADO (obligatorio)
        if ($('#empleado_id').val()==''){
            toastr.error('Se tiene que seleccionar un empleado');
            ret = false;
        }
        //CONCEPTO (obligatorio)
        if ($('#concepto').val()==''){
            toastr.error('Se tiene que introducir el concepto');
            $('#concepto').focus();
            ret = false;
        }
        //IMPORTE (obligatorio)
        if ($('#importe').val()=='' || $('#importe').val()==0){
            toastr.error('Se tiene que introducir un importe');
            $('#importe').focus();
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
