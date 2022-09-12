{{-- INICIO FULL CALENDAR --}}
<script type="text/javascript" src="{{ asset('plugins/fullcalendar/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/fullcalendar/main.js') }}"></script>
{{-- FIN FULL CALENDAR --}}

{{-- INICIO SELECT 2 --}}
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('plugins/select2/custom-select2.js')}}"></script>
{{-- FIN SELECT 2 --}}

{{-- INICIO FLATPICK --}}
<script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script>
{{-- FIN FLATPICK --}}

{{-- INICIO CLEAVE --}}
<script src="{{asset('assets/js/cleave.min.js')}}"></script>
{{-- FIN CLEAVE --}}

{{-- INICIO DE LOS SCRIPTS PROPIOS DEL MODULO--}}
<script type="text/javascript">

    let calendar;                       //variable global del script para el control calendar
    let url;                            //variable global de las URL
    let data;                           //variable global para los datos
    let arrDiasDestino= new Array();    //array de los dias de destino
    let arrFechaDestino= new Array();   //array de los dias de destino

    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // INICIO: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/
    $(document).ready(function() {

        //Ocultamos el botón de copia de servicios
        $('#btn_copiar').hide();

        //Ocultamos el DIV del calendario y de la carga
        $('#calendar').hide();
        $('#cargando').hide();

        //Cambiamos el indicador del menú que está seleccionado
        $('#menuHome').removeClass('active');
        $('#menuAdministracion').removeClass('active');
        $('#menuContabilidad').removeClass('active');
        $('#menuGerencia').removeClass('active');
        $('#menuInspeccion').addClass('active');
        $('#menuSoporte').removeClass('active');

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicializamos los calendarios para las copias
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
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
        });
        $('.flatpickr-multiple').flatpickr({
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
              mode: "multiple",
              onClose: function(selectedDates, dateStr, instance){
                  //Comprobamos que se ha seleccionado alguna fecha
                  if (dateStr !=''){
                      //Pasamos el contenido de las fechas seleccionadas a un array
                      arrDiasDestino = dateStr.split(",");
                      //Nos recorremos el array para cambiar el formato de la fecha
                      arrDiasDestino.forEach(function (fecha){
                          arrFechaDestino.push(fecha.trim().substr(6,4) + "/" + fecha.trim().substr(3,2) + "/" + fecha.trim().substr(0,2));
                      })
                  }
            },
            onOpen: function(selectedDates, dateStr, instance){
                selectedDates = [];
                dateStr = "";
                this.clear();
            },
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los servicios
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let ss = $("#servicio_id").select2({
            placeholder: "Elegir servicio",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        Inicialización de la combo de los pluses
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        let sp = $("#plus_id").select2({
            dropdownParent: $("#form"),
            placeholder: "Elegir plus",
            allowClear: true
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Selección del SERVICIO
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('change', '#servicio_id', function () {

            //Si no hay estado seleccionado
            if($('#servicio_id').val() == ''){
                //Ocultamos el calendario
                $('#calendar').hide();
                //Ocultamos el botón de copia de servicios
                $('#btn_copiar').hide();
            }
            else{
                //Llamamos a la función que mostrará los movimientos según el servicio seleccionado
                muestraCalendario($('#servicio_id').val());
                //Mostramos el DIV del calendario
                $('#calendar').show();
                //Mostramos el botón de copia de servicios
                $('#btn_copiar').show();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                  Control de las acciones pulsadas
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            CAMPO numero del empleado
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('keyup', '#numero_empleado', function () {
            //Llamamos a la función que nos busca el empleado
            buscarEmpleado();
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón salvar (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_salvar', function () {
            //Llamamos a la función que nos valida si los campos
            if (validaCamposModal()) {
                //Grabamos el registro
                grabarMovimiento();
            }
        });

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón eliminar (MODAL FORM)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_eliminar', function () {
            //Pedimos la conformidad de la copio
            swal({
                title: 'Eliminar movimiento',
                text: '¿Borramos el movimiento seleccionado?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#949494',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                preConfirm: false
            }).then(function(result) {
                if (result.value) {
                    //Llamamos a la función que llamará a AJAX para el borrado del movimiento
                    eliminarMovimiento();
                    //Cerramos la ventana de confirmación
                    swal.close()
                }
            })
        })


        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón salvar (MODAL COPIA)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_copia_salvar', function () {
            if (validaFechasModal()){
                //Pedimos la conformidad de la copio
                swal({
                        title: 'Copiar movimientos',
                        text: '¿Copiamos los servicios en la fechas seleccionadas?. Todos los servicios que estén en las fechas seleccionadas, serán sustituidos.',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#949494',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar',
                        preConfirm: false
                    }).then(function(result) {
                        if (result.value) {
                            //Llamamos a la función que llamará a AJAX para copiar los movimientos
                            copiarMovimientos();
                            //Cerramos la ventana de confirmación
                            swal.close()
                        }
                })
            }
        })

        /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            Botón copiar movimientos (MODAL)
        >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
        $(document).on('click', '#btn_copiar', function () {
            // //Limpiamops los campos de la ventana modal de copia
            $('#fechaOrigen').val('');
            $('#fechaDestino').val('');
            //Mostramos la ventana modal de copia
            $('#copia').modal('show');
        });

    });
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<
    // FIN: CUANDO EL DOCUMENTO ESTÁ LISTO
    /*>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<*/

    {{--***************************--}}
    {{-- FUNCION MUESTRACALENDARIO --}}
    {{--***************************--}}
    /* Nos muestra el calendario con la selección de los movimientos del servicio seleccionado */
    function muestraCalendario(servicio){

        // document.addEventListener('DOMContentLoaded', function() {

        let calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
        // $('#calendar').fullCalendar({
            //Tamaño
            contentHeight: 'auto',
            //Para mantener el horario en un solo día
            nextDayThreshold: '23:59:59',
            //Vista inicial
            initialView: 'dayGridMonth',
            //Los botones que irán en la cabecera
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listWeek'
            },
            //Formato de la hora para mostrarla
            eventTimeFormat: { // like '14:30'
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },
            //Muestra las semanas, el texto y si podemos navegar desde el link de la semana
            weekNumbers:true,
            weekText:"S ",
            navLinks:true,
            //Muestra la hora final del movimiento
            displayEventEnd: true,
            //No mostrará el calendario hasta que no se haya cargado toda la información
            loading: function (bool) {
                if (bool == true){
                    //Mostramos el div de carga
                    $('#cargando').show();
                }
                else{
                    $('#cargando').hide();
                    calendar.render();
                }
            },
            //CARGAMOS LOS MOVIMIENTOS
            events: function(info, successCallback, failureCallback) {
            // events: function(start, end, timezone, callback) {
                jQuery.ajax({
                    url: "{{ route('movimientos.cargaMovimientos') }}",
                    type: 'get',
                    dataType: 'json',
                    data: {'servicio_id': servicio},
                    success: function(doc) {
                        let events = [];
                        if(doc.length > 0){
                            doc.forEach( function (r) {
                                events.push({
                                    id: r.id,
                                    overlap: r.overlap,
                                    title: r.title,
                                    start: r.start,
                                    end: r.end,
                                    allow: r.allow,
                                    constraint: r.constraint,
                                    allDay: r.allDay,
                                    plus: r.plus,
                                    'color' : r.color
                                });
                            });
                        }
                        // callback(events);
                        successCallback(events);
                        //return(events);
                    }
                });
            },


            //***** ACCIONES SOBRE LOS DÍAS/MOVIMIENTO ******
            //Cuando pulsan en la semana, mostraremos los datos almacenados en esa semana
            navLinkWeekClick: function(weekStart, jsEvent) {
                //Llamamos al procedimiento de búsqueda de los datos de la semana seleccionada
                Informacion('S',weekStart);
            },
            //Cuando pulsan en el día, mostraremos los datos almacenados en ese día
            navLinkDayClick: function(date, jsEvent) {
                //Llamamos al procedimiento de búsqueda de los datos del día seleccionado
                Informacion('D',date);
            },
            //Click sobre uno de los movimientos introducidos
            eventClick: function(info) {
                //localizamos el registro a partir del id del registro
                buscarRegistro(info.event.startStr,info.event.id)   //Le pasamos como parámetro el id del movimiento
            },
            //Click sobre uno de los días para insertar un nuevo movimiento
            dateClick: function (info){
                //Ponemos la fecha en la cebecera de la venatana modal
                document.getElementById('dia').value = CabeceraDia(info.dateStr);
                //Limpiamos los campos de la ventana modal
                $('#numero_empleado').val('');
                $('#nombre_empleado').val('');
                $('#hora_entrada').val('');
                $('#hora_salida').val('');
                $('#id').val('');
                $('#empleado_id').val('');
                //Ponemos la fecha del evento en el campo oculto
                $('#fecha_movimiento').val(info.dateStr) ;
                //Plus
                $('#plus_id').val('').trigger('change.select2');
                //Ocultamos el botón de eliminar el movimiento
                document.getElementById('btn_eliminar').style.display = 'none';
                //Mostramos la ventana modal
                $('#form').modal('show');
            },
            //Color de los puntos de los eventos (movimientos)
            //eventColor: '#00efe7'
        });

        //Poner las fechas en castellano y poner como el lunes como el primer dia de la semana
        calendar.setOption('locale','es');
        calendar.setOption('firstDay','1');
        //Mostramos el calendario
        // calendar.render();
        // });

    }


    {{-- **************************************************************** --}}
    {{-- OBTENCIÓN Y MUESTRA DE LOS DATOS DEL/DE LOS DIA/S SELECCIONADO/S --}}
    {{-- **************************************************************** --}}

    function Informacion (tipo, weekStart){

        let fechainicio='';
        let fechafin='';
        let hayconflictos = false;     //Inicializamos el indicador de conflictos
        let servicio_id = '';
        let titulo = '';

            //Eliminamos todas las filas de las tablas de la ventana modal
        $('#tabla_informacion>tbody tr').each(function() {
            $(this).remove();
        });
        $('#tabla_conflictos>tbody tr').each(function() {
            $(this).remove();
        });


        //Obtenemos la fecha de inicio y la fecha final
        fechainicio = SumaFecha(0,weekStart.toISOString()); //Sumamos 0 días a la fecha que nos piden para que la combierta a fecha actual
        //Si la selección ha sido de una semana
        if (tipo == "S"){
            //Sumamos los días para que sea una semana
            fechafin = SumaFecha(6,fechainicio);
        }
        else{
            //Si es un sólo día, igualamos la fecha de inicio con la fecha fin
            fechafin = fechainicio;
        }
        servicio_id = $('#servicio_id').val();
        //Preparamos el texto de la cabecera de la ventana modal
        if (tipo == "S"){
            titulo = "Semana del " + parseInt(fechainicio.substr(8,2)) + "-" + parseInt(fechainicio.substr(5,2)) + "-" + fechainicio.substr(0,4);
            titulo = titulo + " al " + parseInt(fechafin.substr(8,2)) + "-" + parseInt(fechafin.substr(5,2)) + "-" + fechafin.substr(0,4);
        }
        else{
            titulo = "Información del día " + parseInt(fechainicio.substr(8,2)) + "-" + parseInt(fechainicio.substr(5,2)) + "-" + fechainicio.substr(0,4);
        }
        //Asignamos el titulo a la cabecera de la ventana modal
        $("#fechas_saleccionada").val(titulo);

        //Montamos el texto de la ruta
        url = "{{ route ('movimientos.conflictos')}}";
        data = {'servicio_id':servicio_id,'fecha_inicio':fechainicio,'fecha_fin':fechafin};

        //Llamamos a Ajax para obtener los posibles conflictos entre movimientos
        $.ajax({
            type: 'get',
            url: url,
            data: data,
            success: function (response){
                //Preguntamos si tiene información
                if (response.length > 0 ) {
                    //Ponemos el indicador que hay conflictos
                    hayconflictos = true;
                    //Nos recorremos los elementos obtenidos
                    response.forEach(function (elemento, indice) {
                        //Ponemos los formatos de fechas
                        fecha_1 = elemento.fecha_1.substr(8,2)+"-"+elemento.fecha_1.substr(5,2)+"-"+elemento.fecha_1.substr(0,4);
                        fecha_2 = elemento.fecha_2.substr(8,2)+"-"+elemento.fecha_2.substr(5,2)+"-"+elemento.fecha_2.substr(0,4);
                        $('#tabla_conflictos>tbody').append("<tr><td class='text-center'>"+elemento.empleado+"</td><td>"+fecha_1+"</td><td>"+elemento.he_1.substr(0,5)+
                            "</td><td>"+elemento.hs_1.substr(0,5)+"</td><td class='text-center'>"+elemento.servicio+"</td><td>"+fecha_2+
                            "</td><td>"+elemento.he_2.substr(0,5)+"</td><td>"+elemento.hs_2.substr(0,5)+"</td></tr>")
                    });
                }
            },
            error: function (){
                //Mostramos mensaje de error
                toastr['error']('No se ha podido obtener los conflictos entre esas fechas', "Error!");
            }
        });

        //Montamos el texto de la ruta
        url = '{{route("movimientos.informacion")}}';
        data = { '_token': '{{ csrf_token() }}',
            'servicio_id':servicio_id,
            'fecha_inicio':fechainicio,
            'fecha_fin':fechafin};
        {{--url = '{{ route ("informacion", ['id_servicio'=>":id_servicio",'fecha_inicio'=>":fecha_inicio",'fecha_fin'=>":fecha_fin"])}}';--}}
        {{--url = url.replace(':id_servicio',id_servicio);--}}
        {{--url = url.replace(':fecha_inicio',fechainicio);--}}
        {{--url = url.replace(':fecha_fin',fechafin);--}}

        //Llamamos a Ajax para comprobar si existe el empleado y si existe, obtenemos sus datos
        $.ajax({
            type: 'get',
            url: url,
            data: data,
            success: function (response){
                //Preguntamos si tiene información
                if (response.length > 0 ) {
                    //Inicializamos el total de las horas
                    horas_totales = 0;
                    //Nos recorremos los elementos obtenidos
                    response.forEach(function (elemento, indice) {
                        //Convertimos las horas obtenidas
                        horas_empleado = parseFloat(elemento.h_dia) + parseFloat(elemento.h_resto);
                        //Acumulamos las horas de los empleados
                        horas_totales = horas_totales + horas_empleado;
                        $('#tabla_informacion>tbody').append("<tr><td class='text-center'>"+elemento.numero+"</td><td>"+elemento.nombre+"</td><td>"+elemento.apellidos+"</td><td class='text-right'>"+horas_empleado+"</td></tr>")
                    });
                    //Mostramos el total de las horas de la selección realizada
                    $('#tabla_informacion>tbody').append("<tr><td class='text-center'>Horas Totales</td><td></td><td></td><td class='text-right'>"+horas_totales+"</td></tr>")

                    //Si hay conflictos, mostramos el div que contiene la tabla
                    if (hayconflictos){
                        $('#id_tabla_conflictos').show();
                    }
                    else{
                        //Ocultamos el div
                        $('#id_tabla_conflictos').hide();
                    }
                    //Mostramos la ventana modal de la información
                    $('#informacion').modal('show');
                }
            },
            error: function (){
                //Mostramos mensaje de error
                toastr['error']('No se ha podido obtener la información seleccionada', "Error!");
            }
        });
    }

    {{-- *************************************** --}}
    {{-- CONFIRMACIÓN DEL BORRADO DEL MOVIMIENTO --}}
    {{-- *************************************** --}}
    function Confirm() {
        let me = this
        swal({
                title: 'Eliminar movimiento',
                text: '¿Eliminamos el movimiento seleccionado?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#949494',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: false
            },
            function () {
                //Llamamos a la funcion para la preparacion del borrado del movimiento
                EliminaMovimiento()
                //Cerramos la ventana de confirmación
                swal.close()
            })
    }
    {{--********************--}}
    {{--BUSCAR UN MOVIMIENTO--}}
    {{--********************--}}
    function buscarRegistro(fecha,id){

        //Preparamos los datos para llamar a la función Ajax
        let url = "{{ route('movimientos.buscarRegistro') }}"
        let data = { 'id': id};

        //Llamamos a Ajax para comprobar si existe el registro del movimiento
        $.ajax({
            type: 'get',
            url: url,
            data: data,
            success: function (response){
                //Preguntamos si tiene información
                if (Object.keys(response).length > 0 ){
                    //Llamamos a la función que muestra los datos y abre la ventana modal
                    mostrarMovimiento(fecha,response);
                }
                else{
                    //Si no existe el registro, sacamos mensaje de advertencia
                    toastr['error']('El movimiento ya no existe. Pulsa F5 para refrescar la vista', "Error!");
                }
            },
            error: function (){
                //Mostramos mensaje de error
                toastr['error']('No se ha podido obtener los datos del movmiento', "Error!");
            }
        });
    }
    {{--**********************--}}
    {{--MUESTRA UN MOVIMIENTO--}}
    {{--**********************--}}
    function mostrarMovimiento(fecha,datos){
        //Ponemos la fecha en la cebecera de la venatana modal
        $('#dia').val(CabeceraDia(fecha.substr(0,10)));
        //Colocamos los datos en la ventana modal
        $('#numero_empleado').val(datos.numero);
        $('#nombre_empleado').val(datos.nombre + " " +datos.apellidos);
        $('#hora_entrada').val(datos.hora_entrada.substr(0,5));
        $('#hora_salida').val(datos.hora_salida.substr(0,5));
        $('#id').val(datos.id);
        $('#empleado_id').val(datos.empleado_id);
        $('#plus_id').val(datos.plus_id != null ? datos.plus_id : '').trigger('change.select2');
        $('#fecha_movimiento').val(datos.fecha_entrada);
        //Mostramos el botón de eliminar el movimiento
        document.getElementById('btn_eliminar').style.display = 'block';
        //Mostramos la ventana modal
        $('#form').modal('show');
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

    {{-- ******************************************** --}}
    {{-- VALIDACIÓN DE LOS CAMPOS DE LA VENTANA MODAL --}}
    {{-- ******************************************** --}}
    function validaCamposModal(){
        //Comprobamos que los campos introducidos son correctos
        let ret = true;
        //EMPLEADO
        //Debe estar informado el campo que se muestra el nombre. De esta manera nos aseguramos que es válido
        if ($('#nombre_empleado').val()==''){
            //Mostramos mensaje de error
            toastr.error('Se tiene que introducir un empleado');
            $('#numero').focus();
            ret = false;
        }
        //HORA DE ENTRADA
        //Debe estar informada
        if ($('#hora_entrada').val() == ''){
            //Mostramos mensaje de error
            toastr.error('Se tiene que introducir una hora de entrada');
            $('#hora_entrada').focus();
            ret = false;
        }
        //HORA DE SALIDA
        //Debe estar informada
        if ($('#hora_salida').val() == ''){
            //Mostramos mensaje de error
            toastr.error('Se tiene que introducir una hora de salida');
            $('#hora_salida').focus();
            ret = false;
        }
        return ret;
    }
    {{-- ******************************************** --}}
    {{-- VALIDACIÓN DE LAS FECHAS DE LA VENTANA MODAL --}}
    {{-- ******************************************** --}}
    function validaFechasModal(){
        //Comprobamos que los campos introducidos son correctos
        let ret = true;
        //FECHA(S) DESTINO
        //Debe estar informada
        if (arrDiasDestino.length==0){
            //Mostramos mensaje de error
            toastr.error('Se tiene que introducir al menos una fecha destino');
            $('#fechaDestino').focus();
            ret = false;
        }
        //FECHA DE ORIGEN
        //Debe estar informado el campo
        if ($('#fechaOrigen').val()==''){
            //Mostramos mensaje de error
            toastr.error('Se tiene que introducir una fecha de origen');
            $('#fechaOrigen').focus();
            ret = false;
        }
        return ret;
    }
    {{-- ************************************************** --}}
    {{-- COPIAR LOS MOVIMIENTOS EN LAS FECHAS SELECCIONADAS --}}
    {{-- ************************************************** --}}
    function copiarMovimientos(){

        //Seleccionamos la ruta
        let url = "{{ route('movimientos.copiarMovimiento') }}"

        //Cambiamos el formato de la fecha
        let fechaOrigen = $('#fechaOrigen').val().substr(6,4)+'-'+$('#fechaOrigen').val().substr(3,2)+'-'+$('#fechaOrigen').val().substr(0,2);

        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
            'servicio_id' : $('#servicio_id').val(),
            'fechaOrigen':fechaOrigen,
            'fechaDestino':arrFechaDestino,
        };

        //Llamamos a AJAX pasándole los datos
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function () {
                //Cerramos la ventana modal
                $("#copia").modal('toggle');
                //Refrescamos los datos del calendario
                calendar.refetchEvents();
                    toastr['success']("Se ha copia los movimientos en las fechas seleccionadas");
            },
            error: function () {
                //Mostramos mensaje de error
                toastr['error']('No se ha podido copiar los movimientos', "Error!");
            }
        });


    }

    {{-- ************************ --}}
    {{-- GRABACIÓN DEL MOVIMIENTO --}}
    {{-- ************************ --}}
    function grabarMovimiento() {
        //alta o modificación del movimiento
        //Preparamos los campos
        let id = $('#id').val();                                                //Id del registro
        let servicio_id = $('#servicio_id').val();                              //Id del servicio
        let empleado_id = $('#empleado_id').val();                              //Id del empleado
        let fecha_entrada = $('#fecha_movimiento').val().substr(0, 10);         //Fecha del movimiento
        let hora_entrada = $('#hora_entrada').val();                            //Hora de entrada
        let hora_salida = $('#hora_salida').val();                              //Hora de salida
        let plus = $('#plus_id').val();                                         //Plus

        //Seleccionamos la ruta
        let url = "{{ route('movimientos.grabarMovimiento') }}"

        //Almacenamos los datos del formulario en un array
        let data = { '_token': '{{ csrf_token() }}',
            'id':id,
            'servicio_id':servicio_id,
            'empleado_id':empleado_id,
            'fecha_entrada':fecha_entrada,
            'hora_entrada':hora_entrada,
            'hora_salida':hora_salida,
            'plus':plus,
        };

        //Llamamos a Ajax para comprobar si existe el empleado y si existe, obtenemos sus datos
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function () {
                //Cerramos la ventana modal
                $("#form").modal('toggle');
                //Refrescamos los datos del calendario
                calendar.refetchEvents();

                //Mostramos mensaje informativo según la acción realizada
                if (id == '') {
                    //Se trata de una alta
                    toastr['success']('Se ha insertado el movimiento en el día ' + parseInt(fecha_entrada.substr(8, 2)), "Alta");
                } else {
                    //Modificación del registro
                    toastr['success']('Se ha modificado el movimiento del día ' + parseInt(fecha_entrada.substr(8, 2)), "Modificado");
                }
            },
            error: function () {
                //Mostramos mensaje de error
                toastr['error']('No se ha podido modificar/insertar el movimiento', "Error!");
            }
        });
    }
    {{-- ************************ --}}
    {{-- BORRADO DEL MOVIMIENTO --}}
    {{-- ************************ --}}
    function eliminarMovimiento(){
        //Función para eliminar el movimiento seleccionado

        //Seleccionamos la ruta
        let url = "{{ route('movimientos.eliminarMovimiento') }}"

        //Almacenamos los datos
        let data = { '_token': '{{ csrf_token() }}',
            'id' : $('#id').val(),
        };

        //Llamamos a Ajax para comprobar si existe el empleado y si existe, obtenemos sus datos
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (){
                //Cerramos la ventana modal
                $("#form").modal('toggle');
                //Refrescamos los datos del calendario
                calendar.refetchEvents();

                //Mostramos mensaje informtivo según la acción realizada
                toastr['success']('Se ha eliminado el movimiento', "Eliminar" );
            },
            error: function (){
                //Mostramos mensaje de error
                toastr['error']('No se ha podido eliminar el movimiento', "Error!");
            }
        });
    }
    {{-- ******************************* --}}
    {{-- SUMA DÍAS EN UNA FECHA CONCRETA --}}
    {{-- ******************************* --}}
    SumaFecha = function (d,Fecha){
    fec = new Date(Fecha);
    anno=fec.getFullYear();
    mes= fec.getMonth()+1;
    dia= fec.getDate();
    tiempo = fec.getTime();
    milisegundos = parseInt(d*24*60*60*1000);
    total = fec.setTime(tiempo+milisegundos);
    anno=fec.getFullYear();
    mes= fec.getMonth()+1;
    dia= fec.getDate();
    mes = (mes < 10) ? ("0" + mes) : mes;
    dia = (dia < 10) ? ("0" + dia) : dia;
    return (anno+"-"+mes+'-'+dia);
}

    {{-- *************************** --}}
    {{-- MUESTRA LA CABECERA DEL DÍA --}}
    {{-- *************************** --}}
    CabeceraDia = function (Fecha){
    let dias = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado']
    let meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre']
    fec = new Date(Fecha);
    dia= fec.getDay();
    mes = fec.getMonth();
        return (dias[dia]+' '+ parseInt(Fecha.substr(8,2))+' de '+meses[mes]+' '+fec.getFullYear());
    }
</script>
{{-- FIN DE LOS SCRIPTS PROPIOS DEL MODULO--}}
