{{-- INICIO DATATABLES--}}
<script src="{{asset('plugins/table/datatable/datatables.js')}}"></script>
<script src="{{asset('plugins/table/datatable/custom_miscellaneous.js')}}"></script>
{{-- FIN DATATABLES--}}

{{-- INICIO FLATPICK --}}
<script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script>
{{-- FIN FLATPICK --}}

{{-- INICIO MOMENT JS --}}
<script src="{{asset('plugins/fullcalendar/moment.min.js')}}"></script>
{{-- FIN MOMENT JS --}}

{{--***********************--}}
{{--INICIO: SCRIPTS PROPIOS--}}
{{--***********************--}}
<script type="text/javascript">

    //Variables globales
    let tabla;                                              //Tabla principal de datos

    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        //Cambiamos el indicador del menú que está seleccionado
        $('#menuHome').removeClass('active');
        $('#menuAdministracion').removeClass('active');
        $('#menuContabilidad').removeClass('active');
        $('#menuGerencia').removeClass('active');
        $('#menuInspeccion').addClass('active');
        $('#menuSoporte').removeClass('active');

        //Ocultamos el div de la tabla y el boton de generacion
        $('#tabla').hide();
        $('#btn_generar').hide();

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
          Inicializamos los calendarios
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $('.flatpickr').flatpickr({
            dateFormat: "d-m-Y",
            weekNumbers: true,
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                },
                months: {
                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                    longhand: ['Enero', 'Febrero', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                },
            },
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                //Ocultamos el div de la tabla y el boton de generacion
                $('#tabla').hide();
                $('#btn_generar').hide();
            },
        });
    })
        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        //********
        //Botón de mostrar las plantillas sin servicios
        //********
        $('#btn_mostrar').on('click', function() {
            //Validamos las fechas introducidas
            if (validaFechas()){
                //Llamamos a la función que cargará las plantillas
                cargarPlantillas();
                //Mostramos el DIV de la tabla
                $('#tabla').show();
            }
        })

    {{--/*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<--}}
    {{--// FIN: CUANDO EL DOCUMENTO ESTÁ LISTO--}}
    {{--/*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<--}}


    {{--/*********************/--}}
    {{--/* INICIO: FUNCIONES */--}}
    {{--//********************/--}}

    //********************************************************************/
    // VALIDAFECHAS: función para comprobar las fechas introducidas    /
    //********************************************************************/
    function validaFechas() {
        //Inicializamos el valor de retorno
        let ret = true;
        //Vemos si las dos fechas están introducidas
        if ($('#fechaInicio').val()=='') {
            //Mostramos el mensaje de error
            toastr.error('Se tiene que introducir una fecha de inicio');
            ret = false;
        }
        if ($('#fechaFin').val()=='') {
            //Mostramos el mensaje de error
            toastr.error('Se tiene que introducir una fecha fin');
            ret = false;
        }
        if ($('#fechaInicio').val() > $('#fechaFin').val()) {
            //Mostramos el mensaje de error
            toastr.error('La fecha fin debe ser igual o mayor que la fecha inicio');
            ret = false;
        }
        //Devolvemos el valor de retorno
        return ret;
    }
    //****************************************************************************************************/
    // CARGARPLANTILLAS: función para mostrar las plantillas definidas que no tengan servicios asignados  /
    //****************************************************************************************************/
    function cargarPlantillas(){

        //Convertimos las fechas que viene en formato d-m-Y a Y-m-d
        let fechaInicio = $('#fechaInicio').val().substr(6,4)+'-'+$('#fechaInicio').val().substr(3,2)+'-'+$('#fechaInicio').val().substr(0,2)
        let fechaFin = $('#fechaFin').val().substr(6,4)+'-'+$('#fechaFin').val().substr(3,2)+'-'+$('#fechaFin').val().substr(0,2)
        //pagina
        let url = "{{ route('generacion.cargarPlantillas') }}"

        //Almacenamos los datos del formulario en un array
        let data = {'fechaInicio':fechaInicio,
                    'fechaFin':fechaFin,
        };
        $.ajax({
            data:  data,
            url: url,
            async: false,           //Lo ponemos en síncrono para que no haga nada hasta que no lo grabe
            type:  'get',
            success:  function (response) {
                        //Si hay datos mostramos
                        if (response.length >0){
                            //Pedimos confirmación de la realización del proceso
                            swal({
                                title: 'Estás seguro/a?',
                                text: "¿Realizamos el proceso de generación de los movimientos?",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Generar',
                                padding: '2em'
                            }).then(function(result) {
                                if (result.value) {
                                    //Llamamos a la función para la generación de los movimientos
                                    procesarGeneracion();
                                }
                            })
                        }
            },
        });

    }


    {{--//********************************************************************/--}}
    {{--// MUESTRAVACACIONES: función para mostrar la lista de las vacaciones /--}}
    {{--//********************************************************************/--}}
    {{--function muestraPlantilla() {--}}
    {{--    //Variables--}}
    {{--    let columna;--}}
    {{--    let filaVacia;--}}
    {{--    let texto;--}}
    {{--    let filaColumna;--}}
    {{--    //Comprobamos si se ha seleccionado un año y un empleado--}}
    {{--    if ($('#servicio_id').val() != '') {--}}
    {{--        //Obtenemos los datos de la selección--}}
    {{--        servicio_id = $('#servicio_id').val();--}}

    {{--        //Mostramos los DIV de la tabla y el de introducción de horarios--}}
    {{--        $('#tabla').show();--}}
    {{--        $('.horarios').show();--}}

    {{--        //Llamamos a AJAX para obtener la plantilla del servicio seleccionado--}}
    {{--        let url = "{{ route('plantillas.lista') }}"--}}
    {{--        $.ajax({--}}
    {{--            data:  {"servicio_id":servicio_id},--}}
    {{--            url: url,--}}
    {{--            type:  'get',--}}
    {{--            success:  function (response) {--}}
    {{--                //Limpiamos el contenido de la tabla--}}
    {{--                $("#tabla_plantilla tr>td").remove();--}}

    {{--                //Inicializamos los indices de las columnas y filas--}}
    {{--                arrFilasporColumnas= [0,0,0,0,0,0,0,0,0];--}}
    {{--                totalFilas = 0;--}}

    {{--                //Comprobamos que hay datos de la empresa seleccionada--}}
    {{--                if (response.length > 0){--}}
    {{--                    //Procesamos los datos obtenidos--}}
    {{--                    response.forEach (function(elemento,index) {--}}
    {{--                        //Localizamos el elemento en el array de los dias para saber qué columna es--}}
    {{--                        columna = $.inArray(elemento['dia'], arrDias);--}}
    {{--                        //Comprobamos si las lineas que tiene esa columna es igual que las filas totales--}}
    {{--                        if (arrFilasporColumnas[columna] == totalFilas) {--}}
    {{--                            //Sumamos uno al contador de filas totales--}}
    {{--                            totalFilas = totalFilas + 1;--}}
    {{--                            //Sumamos una fila al contador de la columna en cuestion--}}
    {{--                            arrFilasporColumnas[columna] = arrFilasporColumnas[columna] + 1;--}}
    {{--                            //Generamos una fila nueva vacía con lo id de cada una de las columnas--}}
    {{--                            filaVacia = "";--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '0" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '1" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '2" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '3" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '4" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '5" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '6" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '7" onclick="editar(this.id)"></div></td>';--}}
    {{--                            filaVacia = filaVacia + '<td class="text-center"><div class="rectangulo"><a class= "horario" name="' + totalFilas.toString() + '8" onclick="editar(this.id)"></div></td>';--}}
    {{--                            //Añadimos la fila--}}
    {{--                            document.getElementById("tabla_plantilla").insertRow(-1).innerHTML = filaVacia;--}}
    {{--                        } else {--}}
    {{--                            //Sumamos una fila al contador de la columna en cuestión--}}
    {{--                            arrFilasporColumnas[columna] = arrFilasporColumnas[columna] + 1;--}}
    {{--                        }--}}
    {{--                        //Colocamos el horario y numero de efectivos en la columna/fila que corresponde--}}
    {{--                        filaColumna = arrFilasporColumnas[columna].toString() + columna.toString();--}}
    {{--                        texto = '<strong>' + elemento['hora_entrada'].substr(0,5) + ' - ';--}}
    {{--                        texto = texto + elemento['hora_salida'].substr(0,5) + ' ';--}}
    {{--                        texto = texto + '[' + elemento['efectivos'] + '] ' + '</strong>';--}}

    {{--                        $("a[name='" + filaColumna+"']").attr('id',elemento['id']);--}}
    {{--                        $("a[name='" + filaColumna+"']").html(texto);--}}

    {{--                    });--}}
    {{--                }--}}
    {{--            },--}}
    {{--        });--}}
    {{--    }--}}
    {{--    else{--}}
    {{--        //Ocultamos el DIV de la tabla y la entrada de datos--}}
    {{--        $('#tabla').hide();--}}
    {{--        $('.horarios').hide();--}}
    {{--    }--}}
    {{--}--}}

    {{--//******************************************************/--}}
    {{--// EDITAR: función para editar el registro seleccionado /--}}
    {{--//******************************************************/--}}
    {{--// Editar el registro que ha seleccionado el usuario--}}
    {{--function editar(id_){--}}
    {{--        $.ajax({--}}
    {{--            data:  {"id":id_},--}}
    {{--            url: '{{ route('plantillas.buscarRegistro') }}',--}}
    {{--            type:  'get',--}}
    {{--            success:  function (response) {--}}
    {{--                //Colocamos el id en la variable global--}}
    {{--                id = id_;--}}
    {{--                //Lamamos a la función que nos carga los datos en los campos--}}
    {{--                cargaDatos(response);--}}
    {{--                //Debloqueamos los campos--}}
    {{--                bloquearCampos(false);--}}
    {{--                //Mostramos el botón de guardar, deshacer y eliminar--}}
    {{--                $('#btn_add').hide();--}}
    {{--                $('#btn_grabar').show();--}}
    {{--                $('#btn_deshacer').show();--}}
    {{--                $('#btn_eliminar').show();--}}
    {{--                //Ponemos el foco en el campo de la hora de entrada--}}
    {{--                $('#hora_entrada').focus();--}}
    {{--            },--}}
    {{--        });--}}
    {{--}--}}
    {{--//**********************************************************/--}}
    {{--// ELIMINAR: función para eliminar un registro seleccionado /--}}
    {{--//**********************************************************/--}}
    {{--// Elimina el registro seleccionado--}}
    {{--function eliminar(id){--}}
    {{--        $.ajax({--}}
    {{--            data:  {'_token': '{{ csrf_token() }}',"id":id},--}}
    {{--            url: '{{ route('plantillas.eliminar') }}',--}}
    {{--            type:  'post',--}}
    {{--            success:  function (response) {--}}
    {{--                //Cargamos de nuevo la tabla de los datos--}}
    {{--                muestraPlantilla()--}}
    {{--                //Mostramos el botón de añadir y ocultamos los demás--}}
    {{--                $('#btn_add').show();--}}
    {{--                $('#btn_grabar').hide();--}}
    {{--                $('#btn_deshacer').hide();--}}
    {{--                $('#btn_eliminar').hide();--}}
    {{--                //Limpiamos los campos--}}
    {{--                limpiaCampos();--}}
    {{--                //Bloqueamos los campos--}}
    {{--                bloquearCampos(true);--}}
    {{--                //Limpiamos el campo del id--}}
    {{--                id = "";--}}
    {{--                //Mostramos el mensaje de que se ha borrado el registro--}}
    {{--                toastr.success('El registro se ha eliminado');--}}
    {{--            },--}}
    {{--        });--}}
    {{--}--}}
    {{--//*******************************************************************/--}}
    {{--// GRABAREGISTRO: función para gestionar la grabación de un registro /--}}
    {{--//*******************************************************************/--}}
    {{--function grabaRegistro(){--}}
    {{--    //Función para la grabación del registro (update o insert, en función de lo solicitado--}}
    {{--    let url;--}}
    {{--    url = "{{ route('plantillas.grabarRegistro') }}"--}}

    {{--    //Almacenamos los datos del formulario en un array--}}
    {{--    let data = { '_token': '{{ csrf_token() }}',--}}
    {{--                'id':id,--}}
    {{--                'servicio_id':servicio_id,--}}
    {{--                'hora_entrada':$('#hora_entrada').val(),--}}
    {{--                'hora_salida':$('#hora_salida').val(),--}}
    {{--                'dia':$('#dia').val(),--}}
    {{--                'efectivos':$('#efectivos').val(),--}}
    {{--    };--}}
    {{--    $.ajax({--}}
    {{--        data:  data,--}}
    {{--        url: url,--}}
    {{--        async: false,           //Lo ponemos en síncrono para que no haga nada hasta que no lo grabe--}}
    {{--        type:  'post',--}}
    {{--        success:  function (response) {--}}
    {{--            //Dependiendo de la acción, sacamos el mensaje--}}
    {{--            if (id!=""){--}}
    {{--                toastr.success('El horario se ha modificado');--}}
    {{--            }else{--}}
    {{--                toastr.success('Se ha insertado el nuevo horario');--}}
    {{--            }--}}
    {{--        },--}}
    {{--    });--}}

    {{--}--}}

    {{--//********************************************************************/--}}
    {{--// CARGADATOS: función para mostrar los datos obtenidos en los campos /--}}
    {{--//********************************************************************/--}}
    {{--//Función colocar los datos obtenidos en los campos de la ventana--}}
    {{--function cargaDatos(response){--}}
    {{--    $('#hora_entrada').val(response['hora_entrada']);--}}
    {{--    $('#hora_salida').val(response['hora_salida']);--}}
    {{--    $('#efectivos').val(response['efectivos']);--}}
    {{--    $('#dia').val(response['dia']).trigger('change.select2');--}}
    {{--}--}}

    {{--//****************************************************************/--}}
    {{--// BLOQUEARCAMPOS: función para bloquear/desbloquear los campos /--}}
    {{--//***************************************************************/--}}
    {{--//Función para bloquear/desbloquear los campos de la ventana--}}
    {{--function bloquearCampos(bloqueado) {--}}
    {{--    $("#hora_entrada").prop("disabled", bloqueado);--}}
    {{--    $("#hora_salida").prop("disabled", bloqueado);--}}
    {{--    $("#dia").prop("disabled", bloqueado);--}}
    {{--    $("#efectivos").prop("disabled", bloqueado);--}}
    {{--}--}}

    {{--//**********************
    ******************************************/--}}
    {{--// LIMPIACAMPOS: función para mostrar la lista de las vacaciones /--}}
    {{--//***************************************************************/--}}
    {{--//Función para limpiar los campos de la ventana--}}
    {{--function limpiaCampos(){--}}
    {{--    //Limpiamos los campos de la ventana--}}
    {{--    $('#hora_entrada').val('');--}}
    {{--    $('#hora_salida').val('');--}}
    {{--    $('#efectivos').val('');--}}
    {{--    $('#dia').val('').trigger('change.select2');--}}
    {{--}--}}

    {{--//*************************************************/--}}
    {{--// VALIDACAMPOS: función para validar los campos  /--}}
    {{--//************************************************/--}}
    {{--//Valida los campos de la plantilla horaria--}}
    {{--function validaCampos(){--}}
    {{--    //Variable auxiliar--}}
    {{--    let ret = true;--}}

    {{--    //HORA DE ENTRADA--}}
    {{--    //Debe estar informada--}}
    {{--    if ($('#hora_entrada').val() == ''){--}}
    {{--        //Mostramos mensaje de error--}}
    {{--        toastr.error('Se tiene que introducir una hora de entrada');--}}
    {{--        $('#hora_entrada').focus();--}}
    {{--        ret = false;--}}
    {{--    }--}}

    {{--    //HORA DE SALIDA--}}
    {{--    //Debe estar informada--}}
    {{--    if ($('#hora_salida').val() == ''){--}}
    {{--        //Mostramos mensaje de error--}}
    {{--        toastr.error('Se tiene que introducir una hora de salida');--}}
    {{--        $('#hora_salida').focus();--}}
    {{--        ret = false;--}}
    {{--    }--}}

    {{--    //DIA DE LA SEMANA--}}
    {{--    //Debe estar informada--}}
    {{--    if ($('#dia').val() == ''){--}}
    {{--        //Mostramos mensaje de error--}}
    {{--        toastr.error('Se tiene que seleccionar un día');--}}
    {{--        $('#dia').focus();--}}
    {{--        ret = false;--}}
    {{--    }--}}

    {{--    //NUMERO DE EFECTIVOS--}}
    {{--    //Tiene que ser mayor que 0--}}
    {{--    if ($('#efectivos').val() <= 0 ){--}}
    {{--        //Mostramos mensaje de error--}}
    {{--        toastr.error('Se tiene que introducir al menos 1 efectivo');--}}
    {{--        $('#efectivos').focus();--}}
    {{--        ret = false;--}}
    {{--    }--}}
    {{--    //Devolvemos el valor de retorno--}}
    {{--    return ret;--}}
    {{--}--}}
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
           FIN: FUNCIONES
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
</script>
{{--***********************--}}
{{--FIN: SCRIPTS PROPIOS   --}}
{{--***********************--}}
