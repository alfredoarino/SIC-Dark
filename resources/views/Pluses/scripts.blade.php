{{-- INICIO DATATABLES--}}
<script src="{{asset('plugins/table/datatable/datatables.js')}}"></script>
<script src="{{asset('plugins/table/datatable/custom_miscellaneous.js')}}"></script>
{{-- FIN DATATABLES--}}

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

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la tabla
        >>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        tabla = $('#tabla_pluses').dataTable( {
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
                url: "{{route('pluses.lista')}}",
                type: "get",
                dataSrc: ""
            },
            'columns': [
                {data: 'id'},
                {data: 'nombre'},
                {
                    data: 'importe', render: function (data) {
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
                }
            ],
            'columnDefs': [
                {"targets": 0, "visible": false},        //Id del registro (oculto)
                {"targets": [1,2], "className": "text-center"},
            ],
            'stripeClasses': [],
            'lengthMenu': [3, 5, 7, 10,50],
            'pageLength': 5,
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
            Botón editar (en la lista)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_pluses tbody').on('click','.editar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            //Llamamos a la función para obtener los datos via Ajax
            editar(data.id);
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (en la lista)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('#tabla_pluses tbody').on('click','.eliminar', function(){
            //Obtenemos el id del registro
            let data = tabla.api().row($(this).parents()).data();
            swal({
                title: 'Estás seguro/a?',
                text: "Se eliminará el plus!",
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
        //Mostramos la ventana modal
        $('#form').modal('show');
        //Ponemos el foco
        $('#nombreº').focus();
    }

    //*********************************************************/
    // EDITAR: función para preparar los datos para su edición /
    //*********************************************************/
    function editar(id){
            let url_ = "{{ route('pluses.buscarRegistro') }}"
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
            let url_ = "{{ route('pluses.eliminar') }}"
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
            url = "{{ route('pluses.nuevo') }}"
        }
        else {
            url = "{{ route('pluses.actualizar') }}"
        }

        //COMO TRATAMOS CON IMÁGENES, DEBEMOS GENERAR UN FORMDATA Y AÑADIR LOS ELEMENTOS RESTANTES DEL REGISTRO
        let data = new FormData();
        data.append('_token','{{ csrf_token() }}');
        data.append('id',$('#id_').val());
        data.append('nombre',$('#nombre').val());
        data.append('importe',$('#importe').val());

        $.ajax({
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
        $('#id_').val(response['id']);
        $('#titulo').html(response['nombre']);
        $('#nombre').val(response['nombre']);
        $('#importe').val(response['importe']);
        //Ponemos el texto del botón 'actualizar'
        $('#btn_salvar').html('Actualizar');

    }
    //*************************************************************/
    // LIMPIACAMPOS: función para limpiar los campos de la ventana /
    //*************************************************************/
    function limpiaCampos(){
        $('#id_').val('');
        $('#titulo').html('Nuevo plus')
        $('#nombre').val('');
        $('#importe').val('');
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

        //NOMBRE (obligatorio)
        if ($('#nombre').val()==''){
            toastr.error('Se tiene que introducir el nombre del pago');
            $('#nombre').focus();
            ret = false;
        }
        //IMPORTE (obligatorio)
        if ($('#importe').val()==''){
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
