{{--***********************--}}
{{--INICIO: SCRIPTS PROPIOS--}}
{{--***********************--}}
<script type="text/javascript">

    //Variables globales
    let tabla_acciones;                //Tabla de las acciones

    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón añadir
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_add_accion', function () {
            //Llamamos a la función de nuevo registro
            nuevo_accion();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón del volver (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_volver_accion', function () {
            //Ocultamos el modal
            $('#form_accion').modal('hide');
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón salvar (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_salvar_accion', function () {
            //Llamamos a la función que nos valida si los campos
            if (validaCamposModal_accion()) {
                //Grabamos el registro
                grabaRegistro_accion();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Pestaña de los acciones
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#icon-acciones-tab', function () {
            //Llamamos a la función que inicializa los acciones del cliente seleccionado
            inicializarTablaAcciones();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón editar (en la lista de los acciones)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_acciones tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla_acciones.api().row($(this).parents()).data();
            //Llamamos a la función para obtener los datos via Ajax
            editar_accion(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (en la lista de los clientes
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_acciones tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla_acciones.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará la acción!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    //Llamamos a la función para eliminarlo via Ajax
                    eliminar_accion(data.id);
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
    // NUEVOaccion: función para preparar la inserción de un nuevo registro /
    //************************************************************************/
    function nuevo_accion(){
        //Limpiamos los campos de la ventana
        limpiaCampos_accion();
        //Mostramos la ventana modal
        $('#form_accion').modal('show');
        //Ponemos el foco
        $('#fecha_inicio_accion').focus();
    }

    //*******************************************************************************/
    // EDITAR_COMTACTO: función para preparar los datos para su edición deñ accion /
    //*******************************************************************************/
    function editar_accion(id){
            let url_ = "{{ route('acciones.buscarRegistro') }}"
            $.ajax({
                data:  {"id":id},
                url: url_,
                type:  'get',
                success:  function (response) {
                    //Cargamos los datos obtenidos en los campos del modal
                    cargaDatos_accion(response);
                    //Modal
                    $('#form_accion').modal('show');
                },
            });
    }

    //*******************************************************/
    // ELIMINAR: función para llamar al borrado del registro /
    //*******************************************************/
    function eliminar_accion(id){
            let url_ = "{{ route('acciones.eliminar') }}"
            let _token = '{{ csrf_token() }}';
            $.ajax({
                data:  {"_token":_token,"id":id},
                url: url_,
                type:  'post',
                success:  function (response) {
                    //Actualizamos los datos de la tabla
                    tabla_acciones.api().ajax.reload( null, false );
                    //Mostramos el mensaje de que se ha borrado el registro
                    toastr.success('El registro se ha eliminado');
                },
            });
    }

    //**************************************************/
    // GRABAREGISTRO: función la grabación del registro /
    //**************************************************/
    function grabaRegistro_accion(){
        //Almacenamos los datos del formulario en un array
        let url;
        if ($('#id_accion').val()===''){
            url = "{{ route('acciones.nuevo') }}"
        }
        else {
            url = "{{ route('acciones.actualizar') }}"
        }

        //COMO TRATAMOS CON IMÁGENES, DEBEMOS GENERAR UN FORMDATA Y AÑADIR LOS ELEMENTOS RESTANTES DEL REGISTRO
        let data = new FormData();
        data.append('_token','{{ csrf_token() }}');
        data.append('id',$('#id_accion').val());
        data.append('cliente_id',$('#id_').val());
        data.append('fecha_inicio',$('#fecha_inicio_accion').val());
        data.append('fecha_fin',$('#fecha_fin_accion').val());
        if ($('#fecha_fin_accion').val()!=''){
            //Si han introducido una fecha de fin, damos la acción por finalizada
            data.append('cerrada',1);
        }
        else{
            //Estado de la acción, abierta
            data.append('cerrada',0);
        }
        data.append('accion',$('#accion_accion').val());

        $.ajax({
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:  data,
            url: url,
            type:  'POST',
            contentType: false,         //Necesario para el tratamiento de las imágenes
            processData: false,         //Necesario para el tratamiento de las imágenes
            success:  function (response) {
                //Actualizamos los datos de la tabla
                tabla_acciones.api().ajax.reload( null, false );
                //Mostramos el mensaje de que se ha actualizado o insertado el registro
                if ($('#id_accion').val()==''){
                    //Sacamos mensaje
                    toastr.success('El registro se ha insertado');
                    //Limpiamos los campos para que se puedan seguir grabando más ayudas
                    limpiaCampos_accion();
                }
                else {
                    //Sacamos mensaje
                    toastr.success('El registro se ha actualizado');
                    //Ocultamos el modal
                    $('#form_accion').modal('hide');
                }
            },
        });
    }

    //******************************************************/
    // CARGADATOS: función para mostrar los datos obtenidos /
    //******************************************************/
    function cargaDatos_accion(response){
        $('#titulo_accion').html('Editar acción')
        $('#id_accion').val(response['id']);
        $('#fecha_inicio_accion').val(response['fecha_inicio'])
        $('#fecha_fin_accion').val(response['fecha_fin'])
        $('#accion_accion').val(response['accion']);
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar_accion').html('Actualizar');

    }
    //*************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos de la ventana /
    //*************************************************************/
    function limpiaCampos_accion(){
        $('#titulo_accion').html('Nueva acción')
        $('#id_accion').val('');
        $('#nombre_accion').val('');
        $('#apellidos_accion').val('');
        $('#cargo_accion').val('');
        $('#telefono_accion').val('');
        $('#email_accion').val('');
        $('#observaciones_accion').val('');

        //Ponemos el texto del botón 'grabar'
        $('#btn_salvar_accion').html('Grabar');
    }

    //******************************************************************/
    // VALIDACAMPOSMODAL: función para validar los campos de la ventana /
    //******************************************************************/
    //Valida los campos de la ventana MODAL
    function validaCamposModal_accion(){
        //Variable auxiliar
        let ret = true;

        //FECHA DE INICIO (obligatorio)
        if ($('#fecha_inicio_accion').val()==''){
            toastr.error('Se tiene que introducir la fecha de inicio');
            $('#fecha_inicio_accion').focus();
            ret = false;
        }
        //FECHA FIN > FECHA INICIO
        if (($('#fecha_inicio_accion').val() !='' && $('#fecha_fin_accion').val()!='') && $('#fecha_inicio_accion').val()>$('#fecha_fin_accion').val()){
            toastr.error('La fecha fin debe ser mayor o igual que la fecha de inicio');
            $('#fecha_fin_accion').focus();
            ret = false;
        }
        //ACCION (obligatorio)
        if ($('#accion_accion').val()==''){
            toastr.error('Se tiene que introducir la acción realizada');
            $('#accion_accion').focus();
            ret = false;
        }
        //Devolvemos el valor de retorno
        return ret;
    }

    //*************************************************************************************************/
    // INICIALIZARTABLAacciones: función para la inicialización de la tabla de datos de los acciones
    //*************************************************************************************************/
    function inicializarTablaAcciones(){

        //Obtenemos el id del cliente seleccionado
        let id = $('#id_').val();

        tabla_acciones = $('#tabla_acciones').dataTable( {
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
                url: "{{route('acciones.lista')}}",
                data: {"id": id},
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {
                    data: 'fecha_inicio', render: function (data) {
                        return data.substring(8, 10) + '-' + data.substring(5, 7) + '-' + data.substring(0, 4);
                    }
                },
                {
                    data: 'fecha_fin', render: function (data) {
                        if (data != null){
                            return data.substring(8, 10) + '-' + data.substring(5, 7) + '-' + data.substring(0, 4);
                        }
                        else{
                            return null;
                        }
                    }
                },
                {
                    data: 'accion', render: function (data){
                        if (data.length > 45 ){
                            return data.substr(0,44) + ' ...';
                        }
                        else{
                            return data;
                        }
                    }
                },
                {data: 'cerrada'},
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
                {"targets": [1,2,5], "className": "text-center"},
                {"targets": [4], "className": "text-center", "render": function (data,type,row,meta) {
                            if (data == 0) {
                                return "<span class='shadow-none badge badge-danger'>Pendiente</span>";
                            }
                            else {
                                return "<span class='shadow-none badge badge-success'>Cerrada</span>";
                            }
                    }
                },
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
