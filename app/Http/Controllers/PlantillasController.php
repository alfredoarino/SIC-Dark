<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicio;
use App\Plantilla;

class PlantillasController extends Controller
{

    //*******************************************************************************************************
    // INDEX: función principal en la cual devuelve la ventana principal
    //*******************************************************************************************************
    public function index()
    {
        //Seleccionamos todos los servicios que tengan el indicador de plantilla activado
        //y esté activa
        $servicios = Servicio::where('activo',1)
                              ->where('plantilla',1)
                              ->orderBy('numero')->get();

        //Montamos las migas de pan
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => "inspeccion", 'name' => "Inspección"],
            ['link' => "", 'name' => "Plantillas"]];
        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return view('Plantillas.component',['servicios'=>$servicios,'breadcrumbs'=>$breadcrumbs]);
    }

    //*******************************************************************************************************
    // LISTA: función para obtener los datos de la plantilla
    //*******************************************************************************************************
    public function lista(Request $request)
    {
        //Obtenemos los registros de un servicio concreto
        $plantilla = Plantilla::where('servicio_id',$request->servicio_id)->get();

        //Devolvemos los datos obtenidos
        return response()->json($plantilla);

    }

    //*******************************************************************************************************
    // BUSCARREGISTRO: función para obtener un registro a partir de su id
    //*******************************************************************************************************
    public function buscarRegistro(Request $request)
    {
        //Obtenemos los registros de un servicio concreto
        $plantilla = Plantilla::where('id',$request->id)->first();

        //Devolvemos los datos obtenidos
        return response()->json($plantilla);

    }

    // **************************************
    // ****** GRABACIÓN DEL REGISTRO ******
    // **************************************
    public function grabarRegistro(Request $request){

        //Obtenemos los datos del request
        $id = $request->id;
        $servicio_id = $request->servicio_id;
        $hora_entrada = $request->hora_entrada;
        $hora_salida = $request->hora_salida;
        $dia = $request->dia;
        $efectivos = $request->efectivos;

        //Si el registro está informado, será una modificación
        if ($id != ""){
            //Localizamos el registro para su actualización
            $record = Plantilla::find($id);
            $record->update([
                'hora_entrada' => $hora_entrada,
                'hora_salida' => $hora_salida,
                'dia' => $dia,
                'efectivos' => $efectivos,
                'usuario' => auth()->user()->email
            ]);
        }
        else{
            //Se trata de un alta de un registro nuevo
            $record =  Plantilla::create([
                'servicio_id' => $servicio_id,
                'hora_entrada' => $hora_entrada,
                'hora_salida' => $hora_salida,
                'dia' => $dia,
                'efectivos' => $efectivos,
                'usuario' => auth()->user()->email
            ]);
        }
    }

    //*********************************************************
    // ELIMINAR: función para eliminar el registro seleccionado
    //*********************************************************
    public function eliminar(Request $request)
    {
        //Localizamos el registro
        $registro = Plantilla ::find($request->id);
        //Actualizamos el registro con el usuario que lo ha realizado
        $registro->update([
            'usuario' => auth()->user()->email,
        ]);
        //Eliminamos el registro
        $registro->delete();
    }

}

