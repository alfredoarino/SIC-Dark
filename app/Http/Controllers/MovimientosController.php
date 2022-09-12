<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

//Modelos utilizados
use App\Movimiento;
use App\Servicio;
use App\Empleado;
use App\Plus;

// Controlador para acceder a los movimientos

class MovimientosController extends Controller
{
    //Funcion que nos mostrará la vista principal de los movimientos
    public function index()
    {
            //Montamos la busqueda de los servicios
            $servicios = Servicio::select('servicios.*')
                ->orderBy('servicios.numero', 'asc')->get();

            //Montamos la busqueda de los empleados
            $empleados = Empleado::select('empleados.*')
                ->orderBy('empleados.numero', 'asc')->get();

        //Seleccionamos todos los pluses
        $pluses = Plus::all();

        //Montamos las migas de pan
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => "inspeccion", 'name' => "Inspección"],
                        ['link' => "", 'name' => "Movimientos"]];
        //Renderizamos la vista de los movimientos
        return view('Movimientos.component',['servicios'=>$servicios,'empleados'=>$empleados,'pluses'=>$pluses,'breadcrumbs'=>$breadcrumbs]);
    }

    // ****************************************************************
    // ****** BUSCARREGISTRO: Localiza el registro que nos pasan ******
    // ****************************************************************
    public function buscarRegistro(Request $request){
        $registro = Movimiento::join('empleados as e', 'e.id', 'movimientos.empleado_id')
            ->select('movimientos.*',
                'e.id as empleado_id',
                'e.numero as numero',
                'e.nombre as nombre',
                'e.apellidos as apellidos')
            ->where('movimientos.id','=',$request->id)->first();   //Localizamos solo un registro
        return response()->json($registro);
    }

    // *********************************************************************
    // ****** COPIARMOVIMIENTO: Copia los movimientos de una fecha  *******
    // *********************************************************************
    public function copiarMovimiento(Request $request){

        //Borramos los servicios que estén entre las fechas seleccionadas como destino
        $borrados = Movimiento::where('servicio_id',$request->servicio_id)
                                   ->whereIn('fecha_entrada',$request->fechaDestino)->delete();

        //Seleccionamos los servicios que estén dentro de la fecha de origen
        $movimientos = Movimiento::where('servicio_id',$request->servicio_id)
                                   ->where('fecha_entrada',$request->fechaOrigen)->get();

        //Nos recorremos los movimientos seleccionados
        foreach ($movimientos as $movimiento){
            //Nos recorremos las fecha de destino para generar cada uno de los servicios
            foreach ($request->fechaDestino as $fecha){

                //Inicializamos las fechas
                $fecha_entrada = Carbon::createFromDate($fecha);
                $fecha_salida = Carbon::createFromDate($fecha);

                //Servicio que se inicia un dia y finaliza al día siguiente
                if ($movimiento->hora_entrada > $movimiento->hora_salida){
                    //Añadimos un día a la fecha de inicio
                    $fecha_salida = $fecha_salida->addDay();
                }

                //Creamos el servicio con la nueva fecha
                $registro =  Movimiento::create([
                    'servicio_id' => $request->servicio_id,
                    'empleado_id' => $movimiento->empleado_id,
                    'fecha_entrada' => $fecha_entrada,
                    'fecha_salida' => $fecha_salida,
                    'hora_entrada' => $movimiento->hora_entrada,
                    'hora_salida' => $movimiento->hora_salida,
                    'horas_dia' => $movimiento->horas_dia,
                    'horas_resto' => $movimiento->horas_resto,
                    'plus_id' => $movimiento->plus_id,
                    'estado' => 1,
                    'facturado' => 0,
                    'usuario' => auth()->user()->email,
                ]);
                //Obtenemos el id del último registro insertado
                $ultimo_registro = Movimiento::latest('id')->first();
                $id=$ultimo_registro->id;
                //Llamamos al procedimiento de búsqueda de errores
                $errores = $this->BuscaErrores($movimiento->empleado_id,$fecha_entrada,$movimiento->hora_entrada,
                                               $movimiento->hora_salida,$id);
            }
        }
    }

    //Procesamos la petición de los movimientos semanales seleccionados
    public function movimientosSemanales(Request $request){
        $info = DB::table('movimientos')
            ->join('empleados as e', 'e.id', 'movimientos.empleado_id')
            ->select('movimientos.*',
                            'e.numero as numero',
                            'e.nombre as nombre',
                            'e.apellidos as apellidos')
            ->where('servicio_id','=',$request->servicio_id)
            ->where(DB::Raw('WEEKOFYEAR(fecha_entrada)'),'=',$request->semana)
            ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
            ->whereNull('movimientos.deleted_at')
            ->orderBy('movimientos.fecha_entrada')
            ->orderBy('movimientos.hora_entrada')->get();
        return response()->json($info);
    }
    //Procesamos la petición de los movimientos mensuales seleccionados
    public function movimientosMensuales(Request $request){
        $info = DB::table('movimientos')
            ->join('empleados as e', 'e.id', 'movimientos.empleado_id')
            ->select('movimientos.*',
                'e.numero as numero',
                'e.nombre as nombre',
                'e.apellidos as apellidos')
            ->where('servicio_id','=',$request->servicio_id)
            ->where(DB::Raw('MONTH(fecha_entrada)'),'=',$request->mes)
            ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
            ->whereNull('movimientos.deleted_at')
            ->orderBy('movimientos.fecha_entrada')
            ->orderBy('movimientos.hora_entrada')->get();
        return response()->json($info);
    }

    // ***********************************************************************
    // ****** ELIMINARMOVIMIENTO: Elimina el movimniento seleccionado  *******
    // ***********************************************************************
    public function eliminarMovimiento(Request $request){

        //Localizamos el registro
        $registro = Movimiento::find($request->id);
        //Comprobamos si tiene algún conflicto
        if ($registro->servicio_conflicto != null){
            //Localizamos el registro que tiene el conflicto
            $conflicto = Movimiento::find($registro->servicio_conflicto);
            //Actualizamos el registro con el usuario que lo ha realizado y eliminamos el conflicto
            $conflicto->update([
                'usuario' => auth()->user()->email,
                'servicio_conflicto' => null,
            ]);
        }
        //Actualizamos el registro con el usuario que lo ha realizado
        $registro->update([
            'usuario' => auth()->user()->email,
        ]);
        //Eliminamos el registro
        $registro->delete();
    }

    //Consultamos los datos del servicio en el mes/semana que nos hayan pasado
    public function informacionServicio(Request $request){
        //Obtenemos el nombre del servicio que nos han pasado
        $servicio = Servicio::join('empresas as e', 'e.id', 'servicios.empresa_id')
            ->select('servicios.*','e.nombre as empresa')
            ->where('servicios.id','=',$request->servicio_id)->first();

        //Si solicitan información de una semana
        if ($request->tipo === "S") {
            //Registros
            $movimientos = DB::table('movimientos')
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('WEEKOFYEAR(fecha_entrada)'),'=',$request->semana)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNull('movimientos.deleted_at')->get();
            $numMovimientos = $movimientos->count();
            //Empleados
            $empleados = DB::table('movimientos')
                ->distinct()
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('WEEKOFYEAR(fecha_entrada)'),'=',$request->semana)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNull('movimientos.deleted_at')->get(['empleado_id']);
            $numEmpleados = $empleados->count();

            //Horas realizadas
            $horas = DB::table('movimientos')
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('WEEKOFYEAR(fecha_entrada)'),'=',$request->semana)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNull('movimientos.deleted_at')->get();
            $numHoras = $horas->sum(['horas_dia']+['horas_resto']);

            //Número de movimientos con conflicto
            $conflictos = DB::table('movimientos')
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('WEEKOFYEAR(fecha_entrada)'),'=',$request->semana)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNotNull('movimientos.servicio_conflicto')
                ->whereNull('movimientos.deleted_at')->get();
            $numConflictos = $conflictos->count();
        }
        else{
            //Si solicitan información un mes completo
            //Registros
            $movimientos = DB::table('movimientos')
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('MONTH(fecha_entrada)'),'=',$request->mes)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNull('movimientos.deleted_at')->get();
            $numMovimientos = $movimientos->count();
            //Empleados
            $empleados = DB::table('movimientos')
                ->distinct()
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('MONTH(fecha_entrada)'),'=',$request->mes)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNull('movimientos.deleted_at')->get(['empleado_id']);
            $numEmpleados = $empleados->count();

            //Horas realizadas
            $horas = DB::table('movimientos')
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('MONTH(fecha_entrada)'),'=',$request->mes)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNull('movimientos.deleted_at')->get();
            $numHoras = $horas->sum(['horas_dia']+['horas_resto']);

            //Número de movimientos con conflicto
            $conflictos = DB::table('movimientos')
                ->where('servicio_id','=',$request->servicio_id)
                ->where(DB::Raw('MONTH(fecha_entrada)'),'=',$request->mes)
                ->where(DB::Raw('YEAR(fecha_entrada)'),'=',$request->ano)
                ->whereNotNull('movimientos.servicio_conflicto')
                ->whereNull('movimientos.deleted_at')->get();
            $numConflictos = $conflictos->count();
        }
        //Creamos un array para luego convertirlo en JSON y devolverlo
        $respuesta = array('numero'=> $servicio->numero,'nombre'=>$servicio->nombre,'empresa'=>$servicio->empresa,'registros'  => $numMovimientos,
                           'empleados' => $numEmpleados,'horas' => $numHoras,'conflictos'=>$numConflictos);
        return response()->json($respuesta);
    }

    // **************************************
    // ****** CARGA DE LOS MOVIMIENTOS ******
    // **************************************
    public function cargaMovimientos(Request $request)
    {
        $servicio_id = $request->servicio_id;
        //Inicializamos el array de los movimientos seleccionados
        $seleccion_movimientos = [];

        //Accedemos a los movimientos entre las fechas determinas por lo siguiente:
        //12 meses atras desde la fecha actual
        //3 meses adelante desde la fecha actual
        //Calculamos las fechas
        $date = date("Y-m-d");            //fecha actual
        $fecha = getdate();                     //fecha actual para saber el día del mes
        $dias = ($fecha['mday'] - 1)*-1;        //Obtenemos los días que debemos restar para que esté en el día uno del mes en curso
        //la fecha de inicio será la resta de los días que llevemos del mes y a esa fecha le restamos 1 año
        $fecha_inicio = date('Y-m-d',strtotime(date("Y-m-d",strtotime($date. $dias ." days"))."-1 years"));
        //La fecha final será la resta de días para inicio de mes, la suma de 4 meses y le restamos un día para que quede en el final de mes
        $fecha_fin = date('Y-m-d',strtotime(date("Y-m-d",strtotime(date("Y-m-d",strtotime($date. $dias ." days"))."+4 months"))."-1 day"));

        //Buscamos los datos según el servicio, las fechas seleccionadas, ordenado por fecha, hora de entrada, hora de salida
        $movs = Movimiento::leftjoin('empleados as e', 'e.id', 'movimientos.empleado_id')
            ->select('movimientos.*','e.id as id_empleado','e.numero as numero_empleado','e.nombre as nombre','e.apellidos as apellidos')
            ->where('movimientos.servicio_id','=',$servicio_id)
            ->where('movimientos.fecha_entrada','>=',$fecha_inicio)
            ->where('movimientos.fecha_entrada','<=',$fecha_fin)
            ->orderBy('movimientos.fecha_entrada','desc')
            ->orderBy('movimientos.hora_entrada','desc')
            ->orderBy('movimientos.hora_salida','desc')->get();
//                            ->orderBy('movimientos.hora_salida','desc')->paginate(1000000);

        //Recorremos los datos obtenidos
        foreach($movs as $m){
            //Preguntamos si tiene algún conflicto
            if (!is_null($m->servicio_conflicto)){
                //Cambiamos el color del evento a rojo
                $color = "#FF0000";
            }
            else{
                //Color normal
                $color = "#29D200";
                //$color = "#00efe7";
            }
            array_push($seleccion_movimientos,
                ['id' => $m->id,
                    'overlap' => $m->numero_empleado,
                    'title' => $m->numero_empleado .' '. strtolower($m->nombre),
                    'start' => $m->fecha_entrada.'T'.$m->hora_entrada,
                    'end' => $m->fecha_salida.'T'.$m->hora_salida,
                    'allow' => $m->id_empleado,
                    'constraint' => $m->nombre . ' ' . $m->apellidos,
                    'allDay' => false,
                    'plus' => $m->plus_id,
                    'color' => $color,
//            'setExtendedProp' => ('plus',$m->plus_id),
                    'editable' =>true]);
        };

        //Devolvemos el array con los movimientos seleccionados
        return response()->json($seleccion_movimientos);
    }

    // **************************************
    // ****** GRABACIÓN DEL MOVIMIENTO ******
    // **************************************
    public function grabarMovimiento(Request $request){

        //Obtenemos los datos del request
        $servicio_id = $request->servicio_id;
        $id = $request->id;
        $fecha_entrada = $request->fecha_entrada;
        $empleado_id = $request->empleado_id;
        $hora_entrada = $request->hora_entrada;
        $hora_salida = $request->hora_salida;
        $plus = $request->plus;

        //Llamamos antes a la función que nos devuelve las horas del día y las horas resto
        $horas = $this->CalculaHoras($hora_entrada,$hora_salida);

        //Si la hora de entrada es mayor que la hora de salida, la fecha de salida será un día mas
        if ($hora_entrada > $hora_salida) {
            $fecha_salida = $this->SumaDias(1,$fecha_entrada);
        }
        else{
            //La fecha de salida es en el mismo día
            $fecha_salida = $fecha_entrada;
        }

        //Si el registro está informado, será una modificación
        if ($id != ""){
            //Localizamos el registro para su actualización
            $record = Movimiento::find($id);
            $record->update([
                'empleado_id' => $empleado_id,
                'fecha_entrada' => $fecha_entrada,
                'fecha_salida' => $fecha_salida,
                'hora_entrada' => $hora_entrada,
                'hora_salida' => $hora_salida,
                'horas_dia' => $horas[0],
                'horas_resto' => $horas[1],
                'plus_id' => $plus,
                'estado' => 1,
                'usuario' => auth()->user()->email
            ]);
        }
        else{
            //Se trata de un alta de un movimiento nuevo
            $record =  Movimiento::create([
                'servicio_id' => $servicio_id,
                'empleado_id' => $empleado_id,
                'fecha_entrada' => $fecha_entrada,
                'fecha_salida' => $fecha_salida,
                'hora_entrada' => $hora_entrada,
                'hora_salida' => $hora_salida,
                'horas_dia' => $horas[0],
                'horas_resto' => $horas[1],
                'plus_id' => $plus,
                'estado' => 1,
                'usuario' => auth()->user()->email
            ]);
            //Obtenemos el id del último registro insertado
            $ultimo_registro = Movimiento::latest('id')->first();
            $id=$ultimo_registro->id;
        }
        //Llamamos al procedimiento de búsqueda de errores
        $errores = $this->BuscaErrores($empleado_id,$fecha_entrada,$hora_entrada,$hora_salida,$id);
    }

    // ************************************************************************
    // ****** OBTENER LAS HORAS DEL DÍA Y LAS HORAS RESTO DEL MOVIMIENTO ******
    // ************************************************************************
    public function CalculaHoras($hora_entrada,$hora_salida){
        //Función para el caluclo de las horas del día y las horas resto

        //Quitamos los dos puntos del formato de la hora
        $hora_entrada = str_replace(':','',$hora_entrada);
        $hora_salida = str_replace(':','',$hora_salida);

        //Pasamos las horas a decimal
        $horas_1 = substr($hora_entrada,0,2);
        $minutos_1 = substr($hora_entrada,2,2)/60;
        $h1 = $horas_1+$minutos_1;

        $horas_2 = substr($hora_salida,0,2);
        $minutos_2 = substr($hora_salida,2,2)/60;
        $h2 = $horas_2+$minutos_2;

        //Comprobamos que hora es mayor
        if ($h1 > $h2){
            $horas_dia = 24.00 - $h1;
            $horas_resto = $h2;
        }
        else if ($h1 < $h2) {
            $horas_dia = $h2 - $h1;
            $horas_resto = 0;
        }
        else{
            //Servicio de 24 horas (es poco probable pero se ha de tener en cuenta)
            $horas_dia = 24;
            $horas_resto = 0;
        }
        //retornonamos un array con los dos elementos obtenidos
        return [$horas_dia,$horas_resto];
    }

    // ****************************************************
    // ****** SUMA O RESTA DÍAS A UNA FECHA CONCRETA ******
    // ****************************************************
    public function SumaDias($dias, $fecha){
        //Función para la suma o resta de días
        $fecha_actual = date('Y-m-d',strtotime($fecha));

        //Aplicamos la cantidad de días que necesitamos saber
        if ($dias>0){
            $fecha_resultante = date("Y-m-d",strtotime($fecha_actual."+".$dias." days"));
        }
        else{
            $fecha_resultante = date("Y-m-d",strtotime($fecha_actual.$dias." days"));
        }
        //Retornamos el valor
        return $fecha_resultante;
    }

    // ************************************************************
    // ****** BUSCA LOS DATOS DEL EMPLEADO QUE VAN TECLEANDO ******
    // ************************************************************
    public function BuscaEmpleado (Request $request)
    {
        //Funcion para la obtención de los datos del empleado

        //Obtememos el número del empleado que nos han pasado
        $numero = $request->numero;

        //Buscamos los datos del empleado que nos pasan
        $empleado = Empleado::select('empleados.*')
            ->where('numero','=',$numero)
            ->where('activo','=',1)->get();

        //Si obtenemos algún registro, devolvemos los datos del empleado
        if (count($empleado)>0){
            return response()->json($empleado);
        }
    }

    // *******************************************************************************************************
    // ****** BUSCA Y CALCULA LAS HORAS DE LOS MOVIMIENTOS DE LOS EMPLEADOS ENTRE UNAS FECHAS CONCRETAS ******
    // *******************************************************************************************************
    public function informacion(Request $request){

        //Obtenemos los datos que nos ha pasado la petición
        $servicio_id = $request->servicio_id;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        //Obtenemos los movimientos del servicio seleccionado entre las fechas pasadas
        $registros = Movimiento::join('empleados', 'empleados.id','=','movimientos.empleado_id')
            ->where('movimientos.servicio_id','=',$servicio_id)
            ->where('movimientos.fecha_entrada','>=',$fecha_inicio)
            ->where('movimientos.fecha_entrada','<=',$fecha_fin)
            ->select('movimientos.empleado_id','empleados.numero','empleados.nombre','empleados.apellidos',DB::raw('SUM(movimientos.horas_dia) as h_dia, SUM(movimientos.horas_resto) as h_resto'))
            ->groupBy('movimientos.empleado_id')
            ->groupBy('empleados.numero')
            ->groupBy('empleados.nombre')
            ->groupBy('empleados.apellidos')->get();
        //Devolvemos los datos obtenidos
        return response()->json($registros);
    }
    // *******************************************************************************************
    // ****** BUSCA LOS MOVIMIENTOS SELECCIONADOS QUE TENGAN CONFLICTOS CON OTROS SERVICIOS ******
    // *******************************************************************************************
    public function conflictos(Request $request){

        //Inicializamos el array de conflictos que será devuelto
        $conflictos= [];

        //Obtenemos los datos que nos ha pasado la petición
        $servicio_id = $request->servicio_id;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        //Obtenemos los movimientos del servicio seleccionado entre las fechas pasadas y que el campo de servicio_conflicto esté informado
        //Unimos la tabla de movimientos con la tabla de servicios y empleados
        $conflicto_1 = Movimiento::join('empleados', 'empleados.id','=','movimientos.empleado_id')
            ->where('movimientos.servicio_id','=',$servicio_id)
            ->where('movimientos.fecha_entrada','>=',$fecha_inicio)
            ->where('movimientos.fecha_entrada','<=',$fecha_fin)
            ->where('movimientos.servicio_conflicto','<>',null)
            ->select('empleados.numero','movimientos.fecha_entrada','movimientos.hora_entrada','movimientos.hora_salida','movimientos.servicio_conflicto')
            ->orderBy('movimientos.fecha_entrada')
            ->orderBy('movimientos.hora_entrada')->get();

        //Nos recorremos los registros para ir buscando los  conflictos con los otros movimientos
        foreach ($conflicto_1 as $c1){
            //Por cada elemento encontrado localizamos el conflicto
            $conflicto_2 = Movimiento::join('servicios', 'servicios.id','=','movimientos.servicio_id')
                ->where('movimientos.id','=',$c1->servicio_conflicto)
                ->select('servicios.numero','movimientos.fecha_entrada','movimientos.hora_entrada','movimientos.hora_salida')
                ->orderBy('movimientos.fecha_entrada')
                ->orderBy('movimientos.hora_entrada')->first();
            //Añadimos los dos registros encontrados en el array de retorno
            array_push($conflictos,
                ['empleado' => $c1->numero,
                    'fecha_1' => $c1->fecha_entrada,
                    'he_1' => $c1->hora_entrada,
                    'hs_1' => $c1->hora_salida,
                    'servicio' => $conflicto_2->numero,
                    'fecha_2' => $conflicto_2->fecha_entrada,
                    'he_2' => $conflicto_2->hora_entrada,
                    'hs_2' => $conflicto_2->hora_salida]);
        };

        //Devolvemos los datos obtenidos
        return response()->json($conflictos);
    }

    // **********************************************************
    // ****** BUSCA LOS POSIBLES ERRORES ENTRE MOVIMIENTOS ******
    // **********************************************************
    public function BuscaErrores($empleado,$fecha,$he,$hs,$id)
    {

        //Declaracion de variables
        $respuesta = array();

        //Inicializamos el array
        $respuesta[0] = false;          //Indicador de conflicto
        $respuesta[1] = $id;            //Id del actual registro procesado
        $respuesta[2] = "";
        $respuesta[3] = "";
        $respuesta[4] = "";
        $respuesta[5] = "";
        $respuesta[6] = "";

        //Añadimos los segundos a la hora de entrada/salida para la comparacion con los campos de la base de datos
        $he = $he .":00";
        $hs = $hs .":00";

        //Solo se busca errores si el empleado es diferente a 999 empleado sin asignar
        if ($empleado!=999)
        {
            //Modificamos el formato de la fecha
            $fecha = date('Y-m-d', strtotime($fecha));
            //Obtenemos la fecha anterior
            $fechaant = strtotime('-1 day', strtotime($fecha));
            $fechaant = date('Y-m-d', $fechaant);
            //Obtenemos la fecha siguiente
            $fechasig = strtotime('+1 day', strtotime($fecha));
            $fechasig = date('Y-m-d', $fechasig);

            //SELECCIONAMOS LOS REGISTROS DE MOVIMIENTOS ACOTANDO DESDE LA FECHA ANTERIOR HASTA LA FECHA SIGUIENTE DE LA FECHA SELECCIONADA
            //Si el id viene informado significa que es una modificación de registro, por lo que debemos excluir el registro propio
            if ($id != "") {
                $movimientos = Movimiento::where('empleado_id','=',$empleado)
                    ->where('fecha_entrada','>=',$fechaant)
                    ->where('fecha_entrada','<=',$fechasig)
                    ->where('id','<>',$id)
                    ->orderBy('fecha_entrada')
                    ->orderBy('hora_entrada')->get();
                //$SelectSQL = "SELECT * FROM tblmovimientos WHERE id_Empleado = " . $empleado . " AND (dteFecha>='" . $fechaant . "' AND dteFecha <='" . $fechasig . "') AND id <>" . $id . " AND intEstado = 1 ORDER BY dteFecha,timHE";
            }
            else {
                $movimientos = Movimiento::where('empleado_id','=',$empleado)
                    ->where('fecha_entrada','>=',$fechaant)
                    ->where('fecha_entrada','<=',$fechasig)
                    ->orderBy('fecha_entrada')
                    ->orderBy('hora_entrada')->get();
                //$SelectSQL = "SELECT * FROM tblmovimientos WHERE id_Empleado = " . $empleado . " AND (dteFecha>='" . $fechaant . "' AND dteFecha <='" . $fechasig . "') AND intEstado = 1 ORDER BY dteFecha,timHE";
            }

            //Nos recorremos los datos obtenidos
            foreach ($movimientos as $obj){
                //********************* FECHA ANTERIOR ************************
                if ($obj->fecha_entrada == $fechaant) {
                    //ver cuadro en documentación
                    if (($he < $hs and $obj->hora_entrada > $obj->hora_salida and $obj->hora_salida > $he) or ($he < $hs and $obj->hora_entrada == $obj->hora_salida and $obj->hora_salida > $he) or ($he > $hs and $obj->hora_entrada > $obj->hora_salida and $obj->hora_salida > $he)
                        or ($he > $hs and $obj->hora_entrada == $obj->hora_salida and $obj->hora_salida > $he) or ($he == $hs and $obj->hora_entrada > $obj->hora_salida and $obj->hora_salida > $he) or ($he == $hs and $obj->hora_entrada == $obj->hora_salida and $obj->hora_salida > $he)) {
                        $respuesta[0] = true;
                        $respuesta[1] = $id;
                        $respuesta[2] = $obj->id;
                        $respuesta[3] = $obj->servicio_id;
                        $respuesta[4] = $obj->fecha_entrada;
                        $respuesta[5] = $obj->hora_entrada;
                        $respuesta[6] = $obj->hora_salida;
                    }
                } //********************* FECHA ACTUAL ************************
                elseif ($obj->fecha_entrada == $fecha) {
                    //Buscamos qué hora de entrada es menor de las dos
                    if ($he < $obj->hora_entrada) //Es menor la hora que nos pasan
                    {
                        //Ver el cuadro en la documentación
                        if (($he < $hs and $obj->hora_entrada < $obj->hora_salida and $hs > $obj->hora_entrada) or
                            ($he < $hs and $obj->hora_entrada > $obj->hora_salida and $hs > $obj->hora_entrada) or
                            ($he < $hs and $obj->hora_entrada == $obj->hora_salida and $hs > $obj->hora_entrada) or
                            ($he > $hs and $obj->hora_entrada < $obj->hora_salida and $hs > $obj->hora_entrada) or
                            ($he > $hs and $obj->hora_entrada > $obj->hora_salida) or
                            ($he > $hs and $obj->hora_entrada == $obj->hora_salida and $hs > $obj->hora_entrada) or
                            ($he == $hs and $obj->hora_entrada < $obj->hora_salida and $hs > $obj->hora_entrada) or
                            ($he == $hs and $obj->hora_entrada > $obj->hora_salida) or
                            ($he == $hs and $obj->hora_entrada == $obj->hora_salida)) {
                            $respuesta[0] = true;
                            $respuesta[1] = $id;
                            $respuesta[2] = $obj->id;
                            $respuesta[3] = $obj->servicio_id;
                            $respuesta[4] = $obj->fecha_entrada;
                            $respuesta[5] = $obj->hora_entrada;
                            $respuesta[6] = $obj->hora_salida;
                        }
                    } elseif ($obj->hora_entrada < $he) //Es menor la hora de la base de datos
                    {
                        //Ver el cuadro en la documentación
                        if (($he < $hs and $obj->hora_entrada < $obj->hora_salida and $obj->hora_salida > $he) or
                            ($he < $hs and $obj->hora_entrada > $obj->hora_salida and $obj->hora_salida > $he) or
                            ($he < $hs and $obj->hora_entrada == $obj->hora_salida and $obj->hora_salida > $he) or
                            ($he > $hs and $obj->hora_entrada < $obj->hora_salida and $obj->hora_salida > $he) or
                            ($he > $hs and $obj->hora_entrada > $obj->hora_salida) or
                            ($he > $hs and $obj->hora_entrada == $obj->hora_salida and $obj->hora_salida > $he) or
                            ($he == $hs and $obj->hora_entrada < $obj->hora_salida and $obj->hora_salida > $he) or
                            ($he == $hs and $obj->hora_entrada > $obj->hora_salida) or
                            ($he == $hs and $obj->hora_entrada == $obj->hora_salida)) {
                            $respuesta[0] = true;
                            $respuesta[1] = $id;
                            $respuesta[2] = $obj->id;
                            $respuesta[3] = $obj->servicio_id;
                            $respuesta[4] = $obj->fecha_entrada;
                            $respuesta[5] = $obj->hora_entrada;
                            $respuesta[6] = $obj->hora_salida;
                        }
                    } else        //Cuando son iguales la hora de entrada en las horas, enviada y en la base de datos
                    {
                        //Siempre será un error si es un servicio de 24 en el mismo dia
                        $respuesta[0] = true;
                        $respuesta[1] = $id;
                        $respuesta[2] = $obj->id;
                        $respuesta[3] = $obj->servicio_id;
                        $respuesta[4] = $obj->fecha_entrada;
                        $respuesta[5] = $obj->hora_entrada;
                        $respuesta[6] = $obj->hora_salida;
                    }

                } //********************* FECHA SIGUIENTE ************************
                elseif ($obj->fecha_entrada == $fechasig) {
                    //ver cuadro en la documentación
                    if (($he > $hs and $obj->hora_entrada < $obj->hora_salida and $hs > $obj->hora_entrada) or
                        ($he > $hs and $obj->hora_entrada > $obj->hora_salida and $hs > $obj->hora_entrada) or
                        ($he > $hs and $obj->hora_entrada == $obj->hora_salida and $hs > $obj->hora_entrada) or
                        ($he == $hs and $obj->hora_entrada < $obj->hora_salida and $hs > $obj->hora_entrada) or
                        ($he == $hs and $obj->hora_entrada > $obj->hora_salida and $hs > $obj->hora_entrada) or
                        ($he == $hs and $obj->hora_entrada == $obj->hora_salida and $hs > $obj->hora_entrada)) {
                        $respuesta[0] = true;
                        $respuesta[1] = $id;
                        $respuesta[2] = $obj->id;
                        $respuesta[3] = $obj->servicio_id;
                        $respuesta[4] = $obj->fecha_entrada;
                        $respuesta[5] = $obj->hora_entrada;
                        $respuesta[6] = $obj->hora_salida;
                    }
                }
            }
            //Si hay conflicto, grabamos los id en cada uno de los dos registros
            if ($respuesta[0] == true) {
                //Actualizamos el registro que se ha insertado
                $record = Movimiento::find($id);       //Localizamos el registro para su actualización
                $record->update([
                    'servicio_conflicto' => $respuesta[2],
                    'usuario' => auth()->user()->email
                ]);

                //Actualizamos el registro que se ha encontrado el conflicto
                $record = Movimiento::find($respuesta[2]);       //Localizamos el registro para su actualización
                $record->update([
                    'servicio_conflicto' => $id,
                    'usuario' => auth()->user()->email
                ]);
            }
            else {
                //Como no hay conflicto,vemos si tenía con otro registro
                $record = Movimiento::where('id','=',$id)->get();           //Obtenemos el registro actual
                //Recorremos el resultado
                foreach ($record as $r){
                    //Comprobamos si el campo del conflicto está lleno
                    if (!is_null($r->servicio_conflicto) or $r->servicio_conflicto != 0){
                        $servicio_conflicto = $r->servicio_conflicto;       //Nos quedamos con el id del servicio que tiene el conflicto
                        //Actualizamos el registro actual , quitando el conflicto
                        $conflicto = Movimiento::find($id);       //Localizamos el registro para su actualización
                        $conflicto->update([
                            'servicio_conflicto' => null,
                            'usuario' => auth()->user()->email
                        ]);
                        //Actualizamos el con el conflicto , quitando el conflicto
                        $conflicto = Movimiento::find($servicio_conflicto);       //Localizamos el registro para su actualización
                        $conflicto->update([
                            'servicio_conflicto' => null,
                            'usuario' => auth()->user()->email
                        ]);
                    }
                }
            }
        }
        //Devolvemos el valor de control de conflictos
        return $respuesta;
    }

}
