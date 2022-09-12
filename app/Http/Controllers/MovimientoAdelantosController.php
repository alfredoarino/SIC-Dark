<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//Tablas usadas
use App\MovimientoAdelantos;
use App\Adelanto;



class MovimientoAdelantosController extends Controller
{
    //************************************************************************************
    // CREAMOVIMIENTOS: función para crear los movimientos (plazos) del adelanto insertado
    //************************************************************************************
    public function creaMovimientos(Request $request)
    {

        //Comprobamos si nos pasan el importe del adelanto:
        //- Si nos pasan el importe del adelanto quire decir que es un nuevo adelanto. En este caso sólo almacenamos este
        //importe en la variable.
        //-Si el importe que nos pasan es 0, significa que es una modificación de plazos. Para ello obtendremos este importe
        //de los movimientos que sean plazo y que estén pendientes. Posteriormente borraremos estos movimientos para generar los nuevos
        if ($request->importe == 0){
            //Localizamos el registro del adelanto
            $adelanto = Adelanto::where('id',$request->adelanto_id)->first();
            //Obtenemos el importe solicitado
            $importe = $adelanto->importe;
            //Obtenemos el importe de los movimientos finalizados
            $liquidado = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                               ->where('tipo','P')
                                               ->where('estado','1')
                                               ->sum('importe');
            //Obtenemos el importe de los aumentos
            $aumentos = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                               ->where('tipo','A')
                                               ->sum('importe');
            //El importe total será la suma de los tres importes (lo liquidado viene en negativo)
            $importe = $importe + $aumentos + $liquidado;

            //Borramos los movimientos pendientes
            $movimiento = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                               ->where('tipo','P')
                                               ->where('estado','0')->delete();
        }
        else{
            $importe = $request->importe;
        }

        //Declaramos las variables que necesitamos
        $mes = substr($request->fecha, 5, 2);      //Obtenemos el mes del dia
        $ano = substr($request->fecha, 0, 4);      //Año
        $registros = 0;             //Número de registros que vamos a generar
        $resto = 0;                 //Resto cuando la división no sea exacta

        //Si el mes sobrepasa 12, sumamos uno al año
        if ($mes > 12) {
            $mes = 1;
            $ano = $ano + 1;
        }

        //Vemos la cantidad de registros que tenemos que generar según el importe del plazo
        $registros = intval($importe / $request->importe_plazo);
        //Obtenemos el resto
        $resto = fmod($importe, $request->importe_plazo);

        //Realizamos el proceso tantas veces como el resultado de la división del importe
        for ($x = 1; $x <= $registros; $x++) {
            //Añadimos el registro en la tabla de movimientos
            $movimiento = MovimientoAdelantos::create([
                'adelanto_id' => $request->adelanto_id,
                'empleado_id' => $request->empleado_id,
                'mes' => $mes,
                'ano' => $ano,
                'tipo' => 'P',
                'importe' => -1 * abs($request->importe_plazo),
                'estado' => '0',
                'usuario' => auth()->user()->email
            ]);
            //Incrementamos el mes
            $mes++;
            //Si el mes sobrepasa 12, sumamos uno al año
            if ($mes > 12) {
                $mes = 1;
                $ano = $ano + 1;
            }
        }
        //Si hay resto, grabamos un registro más con ese importe
        if ($resto > 0) {
            $movimiento = MovimientoAdelantos::create([
                'adelanto_id' => $request->adelanto_id,
                'empleado_id' => $request->empleado_id,
                'mes' => $mes,
                'ano' => $ano,
                'tipo' => 'P',
                'importe' => -1 * abs($resto),
                'estado' => '0',
                'usuario' => auth()->user()->email
            ]);
        }
        //Devolvemos el valor del importe como saldo
        return $importe;
    }
    //************************************************************************************
    // CREAAUMENTO: función para crear un aumento del adelanto
    //************************************************************************************
    public function creaAumento(Request $request)
    {
        //Añadimos el registro en la tabla de movimientos
        $movimiento = MovimientoAdelantos::create([
            'adelanto_id' => $request->adelanto_id,
            'empleado_id' => $request->empleado_id,
            'fecha' => $request->fecha_aumento,
            'tipo' => 'A',
            'importe' => $request->importe_aumento,
            'estado' => '1',
            'usuario' => auth()->user()->email]);

    }
    //*******************************************************************
    // PENDIENTE: función para devolver el importe pendiente de liquidar
    //*******************************************************************
    public function pendiente(Request $request)
    {
        //Obtenemos la suma de plazos pendientes.
        //Si el id está informado, significa que se solicita el importe pendiente desde un plazo determinado
        //Caso contrario se está pidiendo el importe pendiente del adelanto
        $pendiente = MovimientoAdelantos::where('adelanto_id', $request->adelanto_id)
                    ->where('tipo','P')
                    ->when($request->id,function ($query,$id){
                           return $query->where('id','>=',$id);})
                    ->where('estado','0')
                    ->sum('importe');

        //Devolvemos el importe pendiente
        return response()->json($pendiente);

    }
    //************************************************************************************
    // ADDMOVIMIENTOS: función para añadir los movimientos (plazos) a partir de un aumento
    //************************************************************************************
    public function addMovimientos(Request $request)
    {

        //Declaramos las variables que necesitamos
        $registros = 0;             //Número de registros que vamos a generar
        $resto = 0;                 //Resto cuando la división no sea exacta
        $resto_aumento = 0;        //Resto cuando la división no sea exacta en el aumento


        //Obtenemos de los adelantos el importe del plazo
        $adelanto = Adelanto::where('id',$request->adelanto_id)->first();

        //Obtenemos el último registro de los movimientos del adelanto
        $movimiento = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                      ->where('estado',0)
                      ->orderBy('id','desc')
                      ->first();

        //El importe está almacenado en negativo
        $ultimoImporte = abs($movimiento->importe);

        //Obtenemos el mes y el año del último registro de movimientos
        $mes = $movimiento->mes + 1;       //Siguiente mes
        $ano = $movimiento->ano;
        //Si el mes sobrepasa 12, sumamos uno al año
        if ($mes > 12) {
            $mes = 1;
            $ano = $ano + 1;
        }

        //Comprobamos si el registro obtenido de movimientos, tiene todo el importe del plazo
        if ($ultimoImporte < $adelanto->importe_plazo){
            //Vemos si tenemos importe en el aumento para llenar la cuota
            if ($request->importe - ($adelanto->importe_plazo - $ultimoImporte) >=0){
                //El nuevo importe del movimiento será la cantidad de la cuota
                $nuevoImporte = -1 * abs($adelanto->importe_plazo);
                //El resto lo calculamos para que después se siga insertando movimientos
                $resto_aumento = $request->importe - ($adelanto->importe_plazo - $ultimoImporte);
            }
            else{
                //El nuevo importe será lo que tenga en el movimiento + el importe del adelanto
                $nuevoImporte = -1 * abs($ultimoImporte + $request->importe);
                //El resto estará a cero
                $resto_aumento = 0;
            }
            //Actualizamos el registro
            $movimiento->usuario =  auth()->user()->email;
            $movimiento->importe = $nuevoImporte;
            $movimiento->save();
        }
        else{
            //Asignamos el importe del aumento a la variable
            $resto_aumento = $request->importe;
        }

        //Si queda importe del aumento
        if ($resto_aumento > 0 ){

            //Vemos la cantidad de registros que tenemos que generar según el importe del plazo
            $registros = intval($resto_aumento / $adelanto->importe_plazo);
            //Obtenemos el resto
            $resto = fmod($resto_aumento, $adelanto->importe_plazo);

            //Realizamos el proceso tantas veces como el resultado de la división del importe
            for ($x = 1; $x <= $registros; $x++) {
                //Añadimos el registro en la tabla de movimientos
                $movimiento = MovimientoAdelantos::create([
                    'adelanto_id' => $request->adelanto_id,
                    'empleado_id' => $request->empleado_id,
                    'mes' => $mes,
                    'ano' => $ano,
                    'tipo' => 'P',
                    'importe' => -1 * abs($adelanto->importe_plazo),
                    'estado' => '0',
                    'usuario' => auth()->user()->email
                ]);
                //Incrementamos el mes
                $mes++;
                //Si el mes sobrepasa 12, sumamos uno al año
                if ($mes > 12) {
                    $mes = 1;
                    $ano = $ano + 1;
                }
            }
            //Si hay resto, grabamos un registro más con ese importe
            if ($resto > 0) {
                $movimiento = MovimientoAdelantos::create([
                    'adelanto_id' => $request->adelanto_id,
                    'empleado_id' => $request->empleado_id,
                    'mes' => $mes,
                    'ano' => $ano,
                    'tipo' => 'P',
                    'importe' => -1 * abs($resto),
                    'estado' => '0',
                    'usuario' => auth()->user()->email
                ]);
            }
       }
//       //Obtenemos el saldo de todos los movimientos del adelanto
//       $saldo = DB::table('movimiento_adelantos')
//                ->where('adelanto_id',$request->adelanto_id)
//                ->where('estado',0)
//                ->where('tipo','P')->get()->sum('importe');
//
//        //Actualizamos el saldo en el adelanto
//        $adelanto = Adelanto::where('id',$request->adelanto_id)->first();
//        $adelanto->saldo = $saldo;
//        $adelanto->usuario = auth()->user()->email;
//        $adelanto->save();

    }

    //******************************************************************************
    // LISTA: función para obtener todos los movimientos del adelanto seleccionado
    //******************************************************************************
    public function lista(Request $request)
    {
        //Montamos la sentencia que obtendrá todos los datos
        $registros = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)->get();

        //Devolvemos los datos obtenidos
        return response()->json($registros);
    }

    //*****************************************************************************************
    // OBTENERINFORMACION: función para obtener la información de los movimientos del adelanto
    //*****************************************************************************************
    public function obtenerInformacion(Request $request){

        //Devolverá: * Número de aumentos
        //           * Importe de los aumentos
        //           * Plazos liquidados
        //           * Importe liquidado
        //           * Número de plazos pendientes
        //           * Importe pendiente
        //           * Mes/Año del último plazo

        //Inicializamos el array de los datos que se van a devolver
        $datos = array();

        //Número de aumentos
        $aumentos = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                         ->where('tipo','A')->count();
        //Importe de los aumentos
        $aumentos_importe = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                                 ->where('tipo','A')->sum('importe');
        //Plazos liquidados
        $liquidados = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                                ->where('tipo','P')
                                                ->where('estado','1')->count();
        //Importe liquidado
        $liquidados_importe = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                                ->where('tipo','P')
                                                ->where('estado','1')->sum('importe');
        //Número de plazos pendientes
        $pendientes = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                         ->where('tipo','P')
                                         ->where('estado','0')->count();
        //Importe pendiente de los plazos
        $pendientes_importe = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                         ->where('tipo','P')
                                         ->where('estado','0')->sum('importe');

        //Último registro de los plazos pendientes
        $ultimo = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                                         ->where('tipo','P')
                                         ->where('estado','0')->get()->last();

        //Llenamos el array para ser devuelto
        $datos = array('aumentos'=>$aumentos,
                        'aumentos_importe'=>$aumentos_importe,
                        'liquidados'=>$liquidados,
                        'liquidados_importe'=>$liquidados_importe,
                        'pendientes'=>$pendientes,
                        'pendientes_importe'=>$pendientes_importe,
                        'ultimo_mes'=>$ultimo->mes,
                        'ultimo_ano'=>$ultimo->ano);

        //Devolvemos los datos obtenidos
        return response()->json($datos);

    }

    //****************************************
    // APLAZAR: función para aplazar el plazo
    //****************************************
    public function aplazar(Request $request){

        //Obtenemos los datos del adelanto
        $adelanto = Adelanto::where('id',$request->adelanto_id)->first();
        $importe_plazo = $adelanto->importe_plazo;

        //Accedemos al registro seleccionado
        $registro = MovimientoAdelantos::where('id',$request->id)->first();

        //nos quedamos con los datos del registro
        $registro_salvado = $registro->toArray();

        //Comprobamos antes cuántos plazos quedan pendientes
        $pendientes = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
            ->where('tipo','P')
            ->where('estado','0')->count();

        //Dependiendo del número de registros se actúa de una forma u otra
        //---- Cuando solo hay un registro (se aplaza el único registro que queda pendiente ----
        if ($pendientes == 1){
            //Creamos un nuevo registro sumando un mes más al guardado
            $mes = $registro_salvado['mes'] + 1;
            //Si es mayor que doce, sumamos uno al año
            if ($mes>12){
                $mes = 1;
                $ano = $registro_salvado['ano'] + 1;
            }
            else{
                $ano = $registro_salvado['ano'];
            }
            //Añadimos el registro en la tabla de movimientos
            $movimiento = MovimientoAdelantos::create([
                'adelanto_id' => $request->adelanto_id,
                'empleado_id' => $registro_salvado['empleado_id'],
                'mes' => $mes,
                'ano' => $ano,
                'tipo' => 'P',
                'importe' => $registro_salvado['importe'],
                'estado' => '0',
                'usuario' => auth()->user()->email
            ]);
        }
        else{
            //Cuando hay más de un movimiento pendiente:
            //Accedemos al último registro del adelanto
            $ultimo = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                ->where('tipo','P')
                ->where('estado','0')->get()->last();
            //Nos quedamos con el registro
            $ultimo_salvado = $ultimo->toArray();

            //Comprobamos si el registro que se quiere aplazar es el último de la lista de los plazos
            if ($registro_salvado['id']==$ultimo_salvado['id']){
                //Solo tenemos que sumar un mes más al registro
                $mes = $registro_salvado['mes'] + 1;
                //Si es mayor que doce, sumamos uno al año
                if ($mes>12){
                    $mes = 1;
                    $ano = $registro_salvado['ano'] + 1;
                }
                else{
                    $ano = $registro_salvado['ano'];
                }
                //Actualizamos el registro
                $registro->mes = $mes;
                $registro->ano = $ano;
                $registro->usuario = auth()->user()->email;
                $registro->save();
            }
            else{
                //Borramos el registro
                $registro->delete();
                //Comprobamos el importe del plazo. Si es menor que el plazo pactado y el importe del registro seleccionado es >= que el importe pactado
                if (abs($ultimo->importe) < $importe_plazo and abs($registro_salvado['importe']) >= $importe_plazo){
                    //Calculamos el importe que quedará al poner ese último plazo con el importe del plazo pactado
                    $remanente = abs($registro_salvado['importe']) - ($importe_plazo - abs($ultimo->importe));
                    //Actualizamos el último registro con el importe del plazo pactado
                    $ultimo->update([
                        'usuario' => auth()->user()->email,
                        'importe' => -1 * ($importe_plazo)
                    ]);

                    //Vemos la cantidad de registros que tenemos que generar según el importe del plazo
                    $plazos = intval($remanente / $importe_plazo);
                    //Obtenemos el resto
                    $resto = fmod($remanente, $importe_plazo);

                    //Sumamos un mes al mes que tenía el último registro
                    $mes = $ultimo_salvado['mes'] + 1;
                    //Si es mayor que doce, sumamos uno al año
                    if ($mes>12){
                        $mes = 1;
                        $ano = $ultimo_salvado['ano'] + 1;
                    }
                    else{
                        $ano = $ultimo_salvado['ano'];
                    }

                    //Realizamos el proceso tantas veces como el resultado de la división del importe
                    for ($x = 1; $x <= $plazos; $x++) {
                        //Añadimos el registro en la tabla de movimientos
                        $movimiento = MovimientoAdelantos::create([
                            'adelanto_id' => $request->adelanto_id,
                            'empleado_id' => $ultimo_salvado['empleado_id'],
                            'mes' => $mes,
                            'ano' => $ano,
                            'tipo' => 'P',
                            'importe' => -1 * abs($request->importe_plazo),
                            'estado' => '0',
                            'usuario' => auth()->user()->email
                        ]);
                        //Incrementamos el mes
                        $mes++;
                        //Si el mes sobrepasa 12, sumamos uno al año
                        if ($mes > 12) {
                            $mes = 1;
                            $ano++;
                        }
                    }
                    //Si hay resto, grabamos un registro más con ese importe
                    if ($resto > 0) {
                        $movimiento = MovimientoAdelantos::create([
                            'adelanto_id' => $request->adelanto_id,
                            'empleado_id' => $ultimo_salvado['empleado_id'],
                            'mes' => $mes,
                            'ano' => $ano,
                            'tipo' => 'P',
                            'importe' => -1 * abs($resto),
                            'estado' => '0',
                            'usuario' => auth()->user()->email
                        ]);
                    }
                }

            }
        }
    }
    //*******************************************************************
    // ACTUALIZARAUMENTO: función para actualizar el importe del aumento
    //*******************************************************************
    public function actualizarAumento(Request $request){

        //Actualizamos el importe del aumento en el registro
        $movimiento = MovimientoAdelantos::where('id',$request->id)->first();
        $movimiento->importe = $request->importe;
        $movimiento->usuario = auth()->user()->email;
        $movimiento->save();


        //Obtenemos el importe solicitado
        $adelanto = Adelanto::where('id',$request->adelanto_id)->first();

        //Importe de los aumentos
        $aumentos = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
            ->where('tipo','A')->sum('importe');

        //Importe liquidado
        $liquidados = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
            ->where('tipo','P')
            ->where('estado','1')->sum('importe');

        //Calculamos el importe que quedará pendiente después de actualizar
        //el importe del aumento
        //Este es el importe con el que debemos de generar los nuevos plazos
        $pendiente = $adelanto->importe + $aumentos - $liquidados;

        //Tenemos que borrar los movimientos pendientes del adelanto, pero antes
        //salvamos el primer elemento en un array, para quedarnos con la primera fecha
        //y el id del empleado

        $plazos = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
                  ->where('tipo','P')
                  ->where('estado','0')->first();

        $primer_movimiento = $plazos->toArray();

        $plazos = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
            ->where('tipo','P')
            ->where('estado','0')->delete();

        //Generamos los nuevos movimientos
        //Vemos la cantidad de registros que tenemos que generar según el importe del plazo
        $registros = intval($pendiente / $adelanto->importe_plazo);
        //Obtenemos el resto
        $resto = fmod($pendiente, $adelanto->importe_plazo);
        //nos quedamos con el mes y el año del primer registro
        $mes = $primer_movimiento['mes'];
        $ano = $primer_movimiento['ano'];

        //Realizamos el proceso tantas veces como el resultado de la división del importe
        for ($x = 1; $x <= $registros; $x++) {
            //Añadimos el registro en la tabla de movimientos
            $movimiento = MovimientoAdelantos::create([
                'adelanto_id' => $request->adelanto_id,
                'empleado_id' => $primer_movimiento['empleado_id'],
                'mes' => $mes,
                'ano' => $ano,
                'tipo' => 'P',
                'importe' => -1 * abs($adelanto->importe_plazo),
                'estado' => '0',
                'usuario' => auth()->user()->email
            ]);
            //Incrementamos el mes
            $mes++;
            //Si el mes sobrepasa 12, sumamos uno al año
            if ($mes > 12) {
                $mes = 1;
                $ano++;
            }
        }
        //Si hay resto, grabamos un registro más con ese importe
        if ($resto > 0) {
            $movimiento = MovimientoAdelantos::create([
                'adelanto_id' => $request->adelanto_id,
                'empleado_id' => $primer_movimiento['empleado_id'],
                'mes' => $mes,
                'ano' => $ano,
                'tipo' => 'P',
                'importe' => -1 * abs($resto),
                'estado' => '0',
                'usuario' => auth()->user()->email
            ]);
        }
    }
    //***************************************************************
    // ACTUALIZARPLAZO: función para actualizar el importe del plazo
    //***************************************************************
    public function actualizarPlazo(Request $request){

        //Obtenemos el importe solicitado
        $adelanto = Adelanto::where('id',$request->adelanto_id)->first();

        //Importe pendiente antes de actualizar el nuevo importe
        $pendiente = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
            ->where('tipo','P')
            ->where('id','>=',$request->id)        //De los registros igual o mayor que el id que nos ha pasado
            ->where('estado','0')->sum('importe');

        //Nos quedamos con el mes y el año del registro seleccionado y actualizamos el registro con el importe nuevo del plazo
        $plazo = MovimientoAdelantos::where('id',$request->id)->first();
        $mes = $plazo->mes;
        $ano = $plazo->ano;
        $empleado_id = $plazo->empleado_id;
        $plazo->importe = - 1 * ($request->importe);
        $plazo->usuario = auth()->user()->email;
        $plazo->save();

        //Vemos cuál es el pendiente real
        $pendiente_real = abs($pendiente)- $request->importe;

        //Borramos los registros mayores al id seleccionado
        $borrados = MovimientoAdelantos::where('adelanto_id',$request->adelanto_id)
            ->where('tipo','P')
            ->where('id','>',$request->id)        //De los registros mayor que el id que nos ha pasado
            ->where('estado','0')->delete();

        //Generamos los nuevos movimientos
        //Vemos la cantidad de registros que tenemos que generar según el importe del plazo
        $registros = intval($pendiente_real / $adelanto->importe_plazo);
        //Obtenemos el resto
        $resto = fmod($pendiente_real, $adelanto->importe_plazo);

        //Incrementamos el mes del registro que hemos modificado
        $mes++;
        //Si el mes sobrepasa 12, sumamos uno al año
        if ($mes > 12) {
            $mes = 1;
            $ano++;
        }

        //Realizamos el proceso tantas veces como el resultado de la división del importe
        for ($x = 1; $x <= $registros; $x++) {
            //Añadimos el registro en la tabla de movimientos
            $movimiento = MovimientoAdelantos::create([
                'adelanto_id' => $request->adelanto_id,
                'empleado_id' => $empleado_id,
                'mes' => $mes,
                'ano' => $ano,
                'tipo' => 'P',
                'importe' => -1 * abs($adelanto->importe_plazo),
                'estado' => '0',
                'usuario' => auth()->user()->email
            ]);
            //Incrementamos el mes
            $mes++;
            //Si el mes sobrepasa 12, sumamos uno al año
            if ($mes > 12) {
                $mes = 1;
                $ano++;
            }
        }
        //Si hay resto, grabamos un registro más con ese importe
        if ($resto > 0) {
            $movimiento = MovimientoAdelantos::create([
                'adelanto_id' => $request->adelanto_id,
                'empleado_id' => $empleado_id,
                'mes' => $mes,
                'ano' => $ano,
                'tipo' => 'P',
                'importe' => -1 * abs($resto),
                'estado' => '0',
                'usuario' => auth()->user()->email
            ]);
        }
    }
 }
