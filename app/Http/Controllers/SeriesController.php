<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Serie;

class SeriesController extends Controller
{
    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        //Obtenemos los datos enviados
        $registro = $request->all();

        //Ponemos el usuario que realiza la inserción
        $registro['usuario'] = auth()->user()->email;

        Serie::create($registro);

    }
}
