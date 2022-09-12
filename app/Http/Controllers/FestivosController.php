<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;

//Tablas utilizadas
use App\Festivo;
use App\Delegacion;

class FestivosController extends Controller
{
    //*******************************************************************************************************
    // INDEX: función principal en la cual devuelve la ventana principal
    //*******************************************************************************************************
    public function index()
    {
        //Seleccionamos las delegaciones
        $registros = Delegacion::all();
        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return view('festivos.component',['delegaciones'=>$registros]);
    }

    //***************************************************************************************
    // LISTA: función para obtener todos los registros de los festivos a partir de un mes / año
    //***************************************************************************************
    public function lista(Request $request)
    {
        $registros = DB::table('festivos')
            ->leftjoin('delegaciones as d', 'd.id', 'festivos.delegacion_id')
            ->select('festivos.*','d.siglas as delegacion')
            ->where(DB::Raw('YEAR(fecha)'),'=',$request->anualidad)
            ->whereNull('festivos.deleted_at')
            ->orderBy('festivos.fecha', 'asc')
            ->orderBy('d.siglas', 'asc')->get();

        //Devolvemos los datos obtenidos
        return response()->json($registros);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        //Obtenemos los datos
        $registro = $request->all();

        //Ponemos el usuario que realiza la inserción
        $registro['usuario'] = auth()->user()->email;

        //Creamos el registro
        Festivo::create($registro);

    }

    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Festivo::find($request->id);
        //Actualizamos los campos
        $registro->delegacion_id = $request->delegacion_id;
        $registro->fecha = $request->fecha;
        $registro->nombre = $request->nombre;
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
        $registro = Festivo::find($request->id);
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
        $registro = Festivo::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //**************************************************************************
    // IMPRIMIR: función para la impresión de las festivos de un periodo concreto
    //**************************************************************************
    public function imprimir(Request $request)
    {
        //Desglosamos el periodo
        $mes = substr($request['periodo'],5,2);
        $ano = substr($request['periodo'],0,4);
        //Montamos la sentencia que obtendrá los datos
        $festivos = Festivo::leftjoin('empleados as e', 'e.id', 'festivos.empleado_id')
            ->select('festivos.*','e.numero','e.nombre','e.apellidos')
            ->where ('festivos.mes',$mes)
            ->where ('festivos.ano',$ano)
            ->orderBy('e.numero', 'asc')->get();
        //Llamamos a la funcion privada del
        $this->imprimePDF($request->periodo,$festivos);
    }

    //**************************************************************************
    // IMPRIMIRPDF: función privada para la construcción e impresión del PDF
    //**************************************************************************
    private function imprimePDF($periodo,$festivos){
        //Creamos el listado
        $this->fpdf = new Fpdf();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage ("P","A4");
        $this->fpdf->SetTitle("Festivos", true);
        $this->fpdf->SetFont('courier', 'B', 24);
        $this->fpdf->Cell(40, 10, utf8_decode("FESTIVOS ") . $periodo );
        $this->fpdf->Ln ();

        $this->fpdf->Ln (20);

        $this->fpdf->SetFont('courier','', 12);

        /*Recorremos la tabla de detalle */
        foreach ($festivos as $ayuda)
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
