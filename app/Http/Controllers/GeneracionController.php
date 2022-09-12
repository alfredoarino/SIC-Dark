<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//Entidades necesarias
use App\Plantilla;
use App\Movimiento;
use App\Servicio;

class GeneracionController extends Controller
{
    //*******************************************************************************************************
    // INDEX: función principal en la cual devuelve la ventana principal
        //*******************************************************************************************************
    public function index()
    {
        //Renderizamos la vista
        return view('Generacion.component');
    }

    //***********************************************************
    // CARGARPLANTILLAS: función para la carga de las plantillas
    //***********************************************************
    public function cargarPlantillas(Request $request)
    {
        //Seleccionamos los movimientos entre las fechas solicitadas
        $serviciosConMovimientos = Movimiento::where('fecha_entrada','>=',$request->fechaInicio)
                                               ->where('fecha_entrada','<=',$request->fechaFin)
                                               ->select('servicio_id')->distinct()->get();
        //Seleccionamos las plantillas
        $registros = Plantilla::join('servicios as s','s.id','plantillas.servicio_id')
                                ->whereNotIn('plantillas.servicio_id',$serviciosConMovimientos)
                                ->select('plantillas.servicio_id','s.numero as numero','s.nombre as nombre')->distinct()->get();

        //Devolvemos los registros obtenidos
//        return response()->json($serviciosConMovimientos);
        return response()->json($registros);
    }


}
