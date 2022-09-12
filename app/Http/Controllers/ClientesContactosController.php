<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Tablas usadas en el controlador
use App\ContactoCliente;

class ClientesContactosController extends Controller
{

    //*******************************************************************************
    // LISTAS: función para obtener los contactos del cliente seleccionado
    //*******************************************************************************
    public function lista(Request $request)
    {
        //Obtenemos todos los datos de los contactos del cliente
        $Contactos = ContactoCliente::where('cliente_id',$request->id)->get();

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return response()->json($Contactos);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        $registro = $request->all();

        //Ponemos el usuario que realiza la inserción
        $registro['usuario'] = auth()->user()->email;

        ContactoCliente::create($registro);

    }

    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = ContactoCliente::find($request->id);

        //Obtenemos los datos para actualizar los campos
        $registro->nombre = $request->nombre;
        $registro->apellidos = $request->apellidos;
        $registro->cargo = $request->cargo;
        $registro->telefono = $request->telefono;
        $registro->email = $request->email;
        $registro->observaciones = $request->observaciones;
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
        $registro = ContactoCliente ::find($request->id);
        //Actualizamos el registro con el usuario que lo ha realizado
        $registro->update([
            'usuario' => auth()->user()->email,
        ]);
        //Eliminamos el registro
        $registro->delete();
    }


    //****************************************************************
    // BUSCAREGISTRO: función para localizar un registro desde un ID
    //*****************************************************************
    public function buscarRegistro(Request $request)
    {
        //buscamos el registro seleccionado por el usuario
        $registro = ContactoCliente::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

}
