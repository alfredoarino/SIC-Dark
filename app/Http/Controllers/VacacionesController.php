<?php

namespace App\Http\Controllers;

//Clases necesarias
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

//Tablas usadas
use App\Vacaciones;
use App\Empleado;

class VacacionesController extends Controller
{
    private $fpdf;

    public function __construct()
    {

    }

    //*******************************************************************************************************
    // INDEX: función principal en la cual devuelve la ventana principal
    //*******************************************************************************************************
    public function index()
    {
        //Seleccionamos todos los empleados que estén activos
        $empleados = Empleado::where('activo',1)->get();

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => 'rrhh', 'name' => 'RRHH'],
        ['link' => "", 'name' => "Vacaciones"]];

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return view('Vacaciones.component',['empleados'=>$empleados,'breadcrumbs'=>$breadcrumbs]);
    }

    //***************************************************************************************
    // LISTA: función para obtener todos los registros de las vacaciones a partir de un mes / año
    //***************************************************************************************
    public function lista(Request $request)
    {
        //Montamos la sentencia que obtendrá los datos
        $vacaciones = Vacaciones::leftjoin('empleados as e', 'e.id', 'vacaciones.empleado_id')
            ->select('vacaciones.*','e.numero','e.nombre','e.apellidos')
            ->where ('vacaciones.anualidad',$request->anualidad)
            ->where ('vacaciones.empleado_id',$request->empleado_id)
            ->orderBy('e.numero', 'asc')->get();

        //Devolvemos los datos obtenidos
        return response()->json($vacaciones);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        //Obtenemos los datos
        $vacaciones = $request->all();

        //Ponemos el usuario que realiza la inserción
        $vacaciones['usuario'] = auth()->user()->email;

        //Creamos el registro
        Vacaciones::create($vacaciones);

    }

    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Vacaciones::find($request->id);
        //Actualizamos los campos
        $registro->empleado_id = $request->empleado_id;
        $registro->fecha_inicio =$request->fecha_inicio;
        $registro->fecha_fin =$request->fecha_fin;
        $registro->dias =$request->dias;
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
        $registro = Vacaciones::find($request->id);
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
        $registro = Vacaciones::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //**************************************************************************
    // IMPRIMIR: función para la impresión de las vacaciones de un periodo concreto
    //**************************************************************************
    public function imprimir(Request $request)
    {
        //Desglosamos el periodo
        $mes = substr($request['periodo'],5,2);
        $ano = substr($request['periodo'],0,4);
        //Montamos la sentencia que obtendrá los datos
        $vacaciones = Vacaciones::leftjoin('empleados as e', 'e.id', 'ayudas.empleado_id')
            ->select('vacaciones.*','e.numero','e.nombre','e.apellidos')
            ->where ('vacaciones.anualidad',$mes)
            ->orderBy('e.numero', 'asc')->get();
        //Llamamos a la funcion privada del
        $this->imprimePDF($request->periodo,$vacaciones);
    }
    //**************************************************************************
    // IMPRIMIRPDF: función privada para la construcción e impresión del PDF
    //**************************************************************************
    private function imprimePDF($anualidad,$vacaciones){
        //Creamos el listado
        $this->fpdf = new Fpdf();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage ("P","A4");
        $this->fpdf->SetTitle("Ayudas", true);
        $this->fpdf->SetFont('courier', 'B', 24);
        $this->fpdf->Cell(40, 10, utf8_decode("AYUDAS ") . $periodo );
        $this->fpdf->Ln ();

        $this->fpdf->Ln (20);

        $this->fpdf->SetFont('courier','', 12);

        /*Recorremos la tabla de detalle */
        foreach ($vacaciones as $registro)
        {
            //Mostramos los datos en el listado
            $this->fpdf->Cell (30, 5, "", 0);
            $this->fpdf->Cell (135, 5, utf8_decode($ayuda->nombre), 1,0,"L");
            $this->fpdf->Cell (135, 5, utf8_decode($ayuda->apellidos), 1,0,"L");
            $this->fpdf->Ln (8);
        }

        //Sacamos el listado
        $this->fpdf->output();
        exit;

    }


}
