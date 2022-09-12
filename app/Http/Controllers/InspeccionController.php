<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InspeccionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Renderizamos la vista pasándole los datos de las migas de pan
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => "", 'name' => "Inspección"]];
        return view('menu.inspeccion',['breadcrumbs' => $breadcrumbs]);
    }
}
