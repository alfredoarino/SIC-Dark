<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Ayuda;
use App\Empleado;

class AyudasController extends Controller
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
        $registros = Empleado::where('activo',1)->get();

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => 'rrhh', 'name' => 'RRHH'],
            ['link' => "", 'name' => "Ayudas"]];

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return view('ayudas.component',['empleados'=>$registros,'breadcrumbs'=>$breadcrumbs]);
    }

    //***************************************************************************************
    // LISTA: función para obtener todos los registros de las ayudas a partir de un mes / año
    //***************************************************************************************
    public function lista(Request $request)
    {
        //Desglosamos el periodo
        $mes = substr($request['periodo'],5,2);
        $ano = substr($request['periodo'],0,4);
        //Montamos la sentencia que obtendrá los datos
        $ayudas = Ayuda::leftjoin('empleados as e', 'e.id', 'ayudas.empleado_id')
            ->select('ayudas.*','e.numero','e.nombre','e.apellidos')
            ->where ('ayudas.mes',$mes)
            ->where ('ayudas.ano',$ano)
            ->orderBy('e.numero', 'asc')->get();

        //Devolvemos los datos obtenidos
        return response()->json($ayudas);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        //Obtenemos los datos
        $ayuda = $request->all();

        //Ponemos el usuario que realiza la inserción
        $ayuda['usuario'] = auth()->user()->email;

        //Creamos el registro
        Ayuda::create($ayuda);

    }

    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Ayuda::find($request->id);
        //Actualizamos los campos
        $registro->empleado_id = $request->empleado_id;
        $registro->gasolina = floatval($request->gasolina);
        $registro->juzgados = floatval($request->juzgados);
        $registro->baja_enfermedad = floatval($request->baja_enfermedad);
        $registro->baja_accidente = floatval($request->baja_accidente);
        $registro->inspecciones = floatval($request->inspecciones);
        $registro->minusvalia = floatval($request->minusvalia);
        $registro->otros = floatval($request->otros);
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
        $registro = Ayuda::find($request->id);
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
        $registro = Ayuda::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //**************************************************************************
    // IMPRIMIR: función para la impresión de las ayudas de un periodo concreto
    //**************************************************************************
    public function imprimir(Request $request)
    {
        //Desglosamos el periodo
        $mes = substr($request['periodo'],5,2);
        $ano = substr($request['periodo'],0,4);
        //Montamos la sentencia que obtendrá los datos
        $ayudas = Ayuda::leftjoin('empleados as e', 'e.id', 'ayudas.empleado_id')
            ->select('ayudas.*','e.numero','e.nombre','e.apellidos')
            ->where ('ayudas.mes',$mes)
            ->where ('ayudas.ano',$ano)
            ->orderBy('e.numero', 'asc')->get();
        //Llamamos a la funcion privada del
        $this->imprimePDF($request->periodo,$ayudas);
    }

    //**************************************************************************
    // IMPRIMIRPDF: función privada para la construcción e impresión del PDF
    //**************************************************************************
    private function imprimePDF($periodo,$ayudas){
        //Creamos el array de los mes del año
        $arrMeses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        //Creamos el listado
        $this->fpdf = new Fpdf();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage ("P","A4");
        $this->fpdf->SetTitle("Ayudas", true);
        //Ponemos el título del listado
        $this->fpdf->SetFont('courier', 'B', 12);
        $this->fpdf->Cell(40, 10, utf8_decode("AYUDAS ") . $arrMeses[substr($periodo,5,2)-1]. ' de '. substr($periodo,0,4));
        $this->fpdf->Ln ();

        $this->fpdf->Ln (20);

        $this->fpdf->SetFont('courier','', 12);

        /*Recorremos la tabla de detalle */
        foreach ($ayudas as $ayuda)
        {
            //Mostramos los datos en el listado
            $this->fpdf->Cell (30, 5, "", 0);
            $this->fpdf->Cell (135, 5, utf8_decode($ayuda->nombre), 0,0,"L");
            $this->fpdf->Cell (135, 5, utf8_decode($ayuda->apellidos), 0,0,"L");
            $this->fpdf->Ln (8);
        }

        //Sacamos el listado
        $this->fpdf->output('D','ayudas.pdf');
        exit;
    }

    //Función para la impresion de las cabeceras del listado
    private function cabeceraPDF(){

    }
}
