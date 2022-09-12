<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Tablas usadas en el controlador
use App\Gratificacion;
use App\Empleado;


class GratificacionesController extends Controller
{

    //*******************************************************************************************************
    // INDEX: función principal en la cual devuelve la ventana principal y se cargan las tablas auxiliares
    //*******************************************************************************************************
    public function index()
    {
        //Obtenemos todos los datos de los empleados
        $empleados = Empleado::all();

        //Renderizamos la vista pasándole las migas de pan y las tablas necesarias
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => "gestion", 'name' => "Gestión"],
            ['link' => "", 'name' => "Gratificaciones"]];
        return view('Gratificaciones.component', ['empleados'=>$empleados,'breadcrumbs'=>$breadcrumbs]);
    }
    //****************************************************
    // LISTA: función para obtener todos los registros
    //****************************************************
    public function lista(Request $request)
    {
        //Obtenemos todos los datos de los Gratificacions
        $Gratificaciones = Gratificacion::leftjoin('empleados as e', 'e.id', 'Gratificaciones.empleado_id')
            ->select('Gratificaciones.*','e.numero as numero','e.nombre as nombre','e.apellidos as apellidos')
            ->orderBy('e.numero', 'asc')->get();

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return response()->json($Gratificaciones);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        $gratificacion = $request->all();

        //Ponemos el usuario que realiza la inserción
        $gratificacion['usuario'] = auth()->user()->email;

        Gratificacion::create($gratificacion);

    }
    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Gratificacion::find($request->id);

        //Obtenemos los datos para actualizar los campos
        $registro->empleado_id = $request->empleado_id;
        $registro->concepto = $request->concepto;
        $registro->importe = floatval($request->importe);
        $registro->aplica_descuento = $request->aplica_descuento;
        $registro->usuario =  auth()->user()->email;

        //Guardamos el registro
        $registro->save();

    }

    //*********************************************************
    // ELIMINAR: función para eliminar el registro seleccionado
    //*********************************************************
    public function eliminar(Request $request)
    {
        //Localizamos el registro
        $registro = Gratificacion ::find($request->id);
        //Actualizamos el registro con el usuario que lo ha realizado
        $registro->update([
            'usuario' => auth()->user()->email,
        ]);
        //Eliminamos el registro
        $registro->delete();
    }

        //**************************************************************
        // BUSCAREGISTRO: función para localizar un registro desde un ID
        //**************************************************************
        public function buscarRegistro(Request $request)
    {
        //buscamos el registro seleccionado por el usuario
        $registro = Gratificacion::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

}
