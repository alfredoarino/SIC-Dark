<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Tablas usadas en el controlador
use App\Cliente;
use App\Delegacion;
use App\Sector;
use App\FormaPago;
use App\Empresa;


class ClientesController extends Controller
{

    //*******************************************************************************************************
    // INDEX: función principal en la cual devuelve la ventana principal y se cargan las tablas auxiliares
    //*******************************************************************************************************
    public function index()
    {
        //Obtenemos todos los datos de las delegaciones
        $delegaciones = Delegacion::all();
        //Obtenemos todos los datos de las empresas
        $empresas = Empresa::all();
        //Obtenemos todos los datos de las formas de pago
        $formasPago = FormaPago::all();
        //Obtenemos todos los datos de los sectores
        $sectores = Sector::all();

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => 'facturacion', 'name' => 'Facturación'],
            ['link' => "", 'name' => "Clientes"]];
        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return view('Clientes.component', [
            'delegaciones'=>$delegaciones,'empresas'=>$empresas,'formasPago'=>$formasPago,'sectores'=>$sectores,'breadcrumbs'=>$breadcrumbs]);
    }
    //*****************************************************************
    // LISTA: función para obtener todos los registros de los Clientes
    //*****************************************************************
    public function lista(Request $request)
    {
        //Obtenemos todos los datos de los Clientes
        $Clientes = Cliente::leftjoin('delegaciones as d', 'd.id', 'Clientes.delegacion_id')
            ->leftjoin('empresas as e', 'e.id', 'Clientes.empresa_id')
            ->select('Clientes.*','d.siglas as delegacion','e.codigo as empresa')
            ->orderBy('Clientes.id', 'asc')->get();

        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return response()->json($Clientes);
    }

    //********************************************
    // NUEVO: función para crear un nuevo registro
    //********************************************
    public function nuevo(Request $request)
    {

        $Cliente = $request->all();

        //Ponemos el usuario que realiza la inserción
        $Cliente['usuario'] = auth()->user()->email;

        Cliente::create($Cliente);

    }
    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Cliente::find($request->id);

        //Obtenemos los datos para actualizar los campos
        $registro->razonsocial = $request->razonsocial;
        $registro->cif = $request->cif;
        $registro->telefono = $request->telefono;
        $registro->direccion = $request->direccion;
        $registro->poblacion = $request->poblacion;
        $registro->provincia = $request->provincia;
        $registro->cp = $request->cp;
        $registro->delegacion_id = $request->delegacion_id;
        $registro->empresa_id = $request->empresa_id;
        $registro->sector_id = $request->sector_id;
        $registro->forma_pago_id = $request->forma_pago_id;
        $registro->email = $request->email;
        $registro->cuentacontable = $request->cuentacontable;
        $registro->facturas_conjuntas = $request->facturas_conjuntas;
        $registro->factura_electronica = $request->factura_electronica;
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
        $registro = Cliente ::find($request->id);
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
        $registro = Cliente::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

}
