<?php

namespace App\Http\Controllers;

//Clases necesarias
use App\MovimientoAdelantos;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

//Tablas usadas
use App\Adelanto;
use App\Empleado;

class AdelantosController extends Controller
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
        //Seleccionamos las delegaciones
        $registros = Empleado::all();

        //Renderizamos la vista pasándole las migas de pan
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => "gestion", 'name' => "Gestión"],
                        ['link' => "", 'name' => "Adelantos"]];
        return view('Adelantos.component',['empleados'=>$registros,'breadcrumbs'=>$breadcrumbs]);
    }

    //**************************************************************************
    // LISTA: función para obtener todos los registros de un estado determinado
    //**************************************************************************
    public function lista(Request $request)
    {
        //Si el estado que nos pasan es 9, es que no filtramos los datos
        if ($request->estado!=9){
            //Montamos la sentencia que obtendrá los datos filtado por el estado
            $registros = Adelanto::leftjoin('empleados as e', 'e.id', 'adelantos.empleado_id')
                ->select('adelantos.*', 'e.numero', 'e.nombre', 'e.apellidos')
                ->where('adelantos.estado', $request->estado)
                ->orderBy('e.numero', 'asc')->get();
        }
        else{
            //Montamos la sentencia que obtendrá todos los datos
            $registros = Adelanto::leftjoin('empleados as e', 'e.id', 'adelantos.empleado_id')
                ->select('adelantos.*', 'e.numero', 'e.nombre', 'e.apellidos')
                ->orderBy('e.numero', 'asc')->get();
        }

        //Devolvemos los datos obtenidos
        return response()->json($registros);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        //Obtenemos los datos
        $adelantos = $request->all();

        //El saldo será el importe en negativo
        $adelantos['saldo'] = -1 * abs($adelantos['importe']);

        //Ponemos el usuario que realiza la inserción
        $adelantos['usuario'] = auth()->user()->email;

        //Creamos el registro
        Adelanto::create($adelantos);

        //Obtenemos el id insertado
        $id = Adelanto::latest('id')->first();

        //Devolvemos el id del registro generado
        return response()->json($id);

    }

    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Adelanto::find($request->id);
        //Actualizamos los campos
        $registro->importe_plazo = $request->importe_plazo;
        $registro->usuario = auth()->user()->email;
        //Guardamos el registro
        $registro->save();
    }

    //*********************************************************
    // ELIMINAR: función para eliminar el registro seleccionado
    //*********************************************************
    public function eliminar(Request $request)
    {
        //Localizamos el detalle del adelanto y los borramos
        $movimientos = MovimientoAdelantos::where('adelanto_id',$request->id)->delete();

        //Eliminamos el registro del adelanto
        $registro = Adelanto::where('id',$request->id)->delete();

    }

    //**************************************************************
    // BUSCAREGISTRO: función para localizar un registro desde un ID
    //**************************************************************
    public function buscarRegistro(Request $request)
    {
        //buscamos el registro seleccionado por el usuario
        $registro = Adelanto::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //**************************************************************************
    // IMPRIMIR: función para la impresión de las adelantos de un periodo concreto
    //**************************************************************************
    public function imprimir(Request $request)
    {
        //Desglosamos el periodo
        $mes = substr($request['periodo'], 5, 2);
        $ano = substr($request['periodo'], 0, 4);
        //Montamos la sentencia que obtendrá los datos
        $adelantos = Adelanto::leftjoin('empleados as e', 'e.id', 'ayudas.empleado_id')
            ->select('adelantos.*', 'e.numero', 'e.nombre', 'e.apellidos')
            ->where('adelantos.anualidad', $mes)
            ->orderBy('e.numero', 'asc')->get();
        //Llamamos a la funcion privada del
        $this->imprimePDF($request->periodo, $adelantos);
    }
    //**************************************************************************
    // IMPRIMIRPDF: función privada para la construcción e impresión del PDF
    //**************************************************************************
    private function imprimePDF($anualidad, $adelantos)
    {
        //Creamos el listado
        $this->fpdf = new Fpdf();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage("P", "A4");
        $this->fpdf->SetTitle("Ayudas", true);
        $this->fpdf->SetFont('courier', 'B', 24);
        $this->fpdf->Cell(40, 10, utf8_decode("AYUDAS ") . $periodo);
        $this->fpdf->Ln();

        $this->fpdf->Ln(20);

        $this->fpdf->SetFont('courier', '', 12);

        /*Recorremos la tabla de detalle */
        foreach ($adelantos as $registro) {
            //Mostramos los datos en el listado
            $this->fpdf->Cell(30, 5, "", 0);
            $this->fpdf->Cell(135, 5, utf8_decode($ayuda->nombre), 1, 0, "L");
            $this->fpdf->Cell(135, 5, utf8_decode($ayuda->apellidos), 1, 0, "L");
            $this->fpdf->Ln(8);
        }

        //Sacamos el listado
        $this->fpdf->output();
        exit;

    }
}
