{{--***********************--}}
{{--INICIO: SCRIPTS PROPIOS--}}
{{--***********************--}}
<script type="text/javascript">

    //Variables globales
    let tabla_contactos;                //Tabla de los contactos

    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón añadir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_add_contacto', function () {
            //Llamamos a la función de nuevo registro
            nuevo_contacto();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón del volver (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_volver_contacto', function () {
            //Ocultamos el modal
            $('#form_contacto').modal('hide');
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón salvar (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_salvar_contacto', function () {
            //Llamamos a la función que nos valida si los campos
            if (validaCamposModal_contacto()) {
                //Grabamos el registro
                grabaRegistro_contacto();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Pestaña de los CONTACTOS
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#icon-contactos-tab', function () {
            //Llamamos a la función que inicializa los contactos del cliente seleccionado
            inicializarTablaContactos();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón editar (en la lista de los contactos)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_contactos tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla_contactos.api().row($(this).parents()).data();
            //Llamamos a la función para obtener los datos via Ajax
            editar_contacto(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (en la lista de los clientes
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_contactos tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla_contactos.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el contacto!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    //Llamamos a la función para eliminarlo via Ajax
                    eliminar_contacto(data.id);
                }
            })
        });

    });
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // FIN: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<


    /*>>>>>>>>>>>>>>>>>>>>>>
        INICIO: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>*/

    //************************************************************************/
    // NUEVOCONTACTO: función para preparar la inserción de un nuevo registro /
    //************************************************************************/
    function nuevo_contacto(){
        //Limpiamos los campos de la ventana
        limpiaCampos_contacto();
        //Mostramos la ventana modal
        $('#form_contacto').modal('show');
        //Ponemos el foco
        $('#nombre_contacto').focus();
    }

    //*******************************************************************************/
    // EDITAR_COMTACTO: función para preparar los datos para su edición deñ contacto /
    //*******************************************************************************/
    function editar_contacto(id){
            let url_ = "{{ route('contactos.buscarRegistro') }}"
            $.ajax({
                data:  {"id":id},
                url: url_,
                type:  'get',
                success:  function (response) {
                    //Cargamos los datos obtenidos en los campos del modal
                    cargaDatos_contacto(response);
                    //Modal
                    $('#form_contacto').modal('show');
                },
            });
    }

    //*******************************************************/
    // ELIMINAR: función para llamar al borrado del registro /
    //*******************************************************/
    function eliminar_contacto(id){
            let url_ = "{{ route('contactos.eliminar') }}"
            let _token = '{{ csrf_token() }}';
            $.ajax({
                data:  {"_token":_token,"id":id},
                url: url_,
                type:  'post',
                success:  function (response) {
                    //Actualizamos los datos de la tabla
                    tabla_contactos.api().ajax.reload( null, false );
                    //Mostramos el mensaje de que se ha borrado el registro
                    toastr.success('El registro se ha eliminado');
                },
            });
    }

    //**************************************************/
    // GRABAREGISTRO: función la grabación del registro /
    //**************************************************/
    function grabaRegistro_contacto(){
        //Almacenamos los datos del formulario en un array
        let url;
        if ($('#id_contacto').val()===''){
            url = "{{ route('contactos.nuevo') }}"
        }
        else {
            url = "{{ route('contactos.actualizar') }}"
        }

        //COMO TRATAMOS CON IMÁGENES, DEBEMOS GENERAR UN FORMDATA Y AÑADIR LOS ELEMENTOS RESTANTES DEL REGISTRO
        let data = new FormData();
        data.append('_token','{{ csrf_token() }}');
        data.append('id',$('#id_contacto').val());
        data.append('cliente_id',$('#id_').val());
        data.append('nombre',$('#nombre_contacto').val());
        data.append('apellidos',$('#apellidos_contacto').val());
        data.append('cargo',$('#cargo_contacto').val());
        data.append('telefono',$('#telefono_contacto').val());
        data.append('email',$('#email_contacto').val());
        data.append('observaciones',$('#observaciones_contacto').val());

        $.ajax({
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:  data,
            url: url,
            type:  'POST',
            contentType: false,         //Necesario para el tratamiento de las imágenes
            processData: false,         //Necesario para el tratamiento de las imágenes
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla_contactos.api().ajax.reload( null, false );
                //Mostramos el mensaje de que se ha actualizado o insertado el registro
                if ($('#id_contacto').val()==''){
                    //Sacamos mensaje
                    toastr.success('El registro se ha insertado');
                    //Limpiamos los campos para que se puedan seguir grabando más ayudas
                    limpiaCampos_contacto();
                }
                else {
                    //Sacamos mensaje
                    toastr.success('El registro se ha actualizado');
                    //Ocultamos el modal
                    $('#form_contacto').modal('hide');
                }
            },
        });
    }

    //******************************************************/
    // CARGADATOS: función para mostrar los datos obtenidos /
    //******************************************************/
    function cargaDatos_contacto(response){
        $('#titulo_contacto').html('Editar contacto')
        $('#id_contacto').val(response['id']);
        $('#nombre_contacto').val(response['nombre'])
        $('#apellidos_contacto').val(response['apellidos']);
        $('#cargo_contacto').val(response['cargo']);
        $('#telefono_contacto').val(response['telefono']);
        $('#email_contacto').val(response['email']);
        $('#observaciones_contacto').val(response['observaciones']);
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar_contacto').html('Actualizar');

    }
    //*************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos de la ventana /
    //*************************************************************/
    function limpiaCampos_contacto(){
        $('#titulo_contacto').html('Nuevo contacto')
        $('#id_contacto').val('');
        $('#nombre_contacto').val('');
        $('#apellidos_contacto').val('');
        $('#cargo_contacto').val('');
        $('#telefono_contacto').val('');
        $('#email_contacto').val('');
        $('#observaciones_contacto').val('');

        //Ponemos el texto del botón 'grabar'
        $('#btn_salvar_contacto').html('Grabar');
    }

    //******************************************************************/
    // VALIDACAMPOSMODAL: función para validar los campos de la ventana /
    //******************************************************************/
    //Valida los campos de la ventana MODAL
    function validaCamposModal_contacto(){
        //Variable auxiliar
        let ret = true;

        //NOMBRE (obligatorio)
        if ($('#nombre_contacto').val()==''){
            toastr.error('Se tiene que introducir el nombre');
            $('#nombre_contacto').focus();
            ret = false;
        }
        //APELLIDOS (obligatorio)
        if ($('#apellidos_contacto').val()==''){
            toastr.error('Se tiene que introducir los apellidos');
            $('#apellidos_contacto').focus();
            ret = false;
        }
        //CARGO (obligatorio)
        if ($('#cargo_contacto').val()==''){
            toastr.error('Se tiene que introducir el cargo');
            $('#cargo_contacto').focus();
            ret = false;
        }
        //TELEFONO (obligatorio)
        if ($('#telefono_cargo').val()==''){
            toastr.error('Se tiene que introducir un teléfono');
            $('#telefono_cargo').focus();
            ret = false;
        }
        //EMAIL (Obligatorio y valido)
        if ($('#email_contacto').val()==''){
            toastr.error('Se tiene que introducir el correo electrónico');
            $('#email_contacto').focus();
            ret = false;
        }
        else{
            let re=/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/
            if(!re.exec($('#email_contacto').val())){
                toastr.error('Se tiene que introducir el correo electrónico válido');
                $('#email_contacto').focus();
                ret = false;
            }
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    //*************************************************************************************************/
    // INICIALIZARTABLACONTACTOS: función para la inicialización de la tabla de datos de los contactos
    //*************************************************************************************************/
    function inicializarTablaContactos(){

        //Obtenemos el id del cliente seleccionado
        let id = $('#id_').val();

        tabla_contactos = $('#tabla_contactos').dataTable( {
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
                url: "{{route('contactos.lista')}}",
                data: {"id": id},
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'nombre'},
                {data: 'apellidos'},
                {data: 'cargo'},
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
                {"targets": [1,2,3,4,5], "className": "text-center"},
            ],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7, 10,50],
            'pageLength': 7,
        } );
    }
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
           FIN: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
</script>
{{--***********************--}}
{{--FIN: SCRIPTS PROPIOS   --}}
{{--***********************--}}
