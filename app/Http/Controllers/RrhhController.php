<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RrhhController extends Controller
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
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => "", 'name' => "RRHH"]];
        return view('menu.rrhh',['breadcrumbs' => $breadcrumbs]);
    }
}
