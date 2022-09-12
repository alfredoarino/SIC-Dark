<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Tablas usadas en el controlador
use App\Pago;

class PagosController extends Controller
{
    //******************************************************************
    // INDEX: función principal en la cual devuelve la ventana principal
    //******************************************************************
    public function index()
    {
//Renderizamos la vista pasándole los datos de las migas de pan
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => "gestion", 'name' => "Gestión"],
                        ['link' => "", 'name' => "Pagos"]];
        return view('pagos.component',['breadcrumbs'=>$breadcrumbs]);
    }

    //*************************************************
    // LISTA: función para obtener todos los registros
    //*************************************************
    public function lista(Request $request)
    {
        //Obtenemos todos los datos de los empleados
        $registros = Pago::all();

        //Devolvemos los datos obtenidos
        return response()->json($registros);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        //Obtenemos los datos enviados
        $registro = $request->all();

        //Ponemos el usuario que realiza la inserción
        $registro['usuario'] = auth()->user()->email;

        Pago::create($registro);
    }

    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Pago::find($request->id);

        //Obtenemos los datos para actualizar los campos
        $registro->nombre = $request->nombre;
        $registro->importe = floatval($request->importe);
        $registro->incentivos = $request->incentivos;
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
        $registro = Pago::find($request->id);

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
        $registro = Pago::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

}
