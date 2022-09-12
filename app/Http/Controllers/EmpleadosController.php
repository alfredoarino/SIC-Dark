<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Tablas usadas en el controlador
use App\Empleado;
use App\Delegacion;
use App\Empresa;
use App\Convenio;

class EmpleadosController extends Controller
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
        //Obtenemos todos los datos de los convenios
        $convenios = Convenio::all();

        //Obtenemos la ruta desde que ha sido llamado
        $ruta = substr( url()->previous(),strrpos(url()->previous(),'/')+1,strlen(url()->previous()));

        //Vemos de que modulo ha sido llamado
        if ($ruta == 'inspeccion'){
            $link = 'inspeccion';
            $name = 'Inspección';
        }
        else{
            $link = 'rrhh';
            $name = 'RRHH';
        }
        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => $link, 'name' => $name],
                        ['link' => "", 'name' => "Empleados"]];
        return view('empleados.component', [
            'delegaciones'=>$delegaciones,'empresas'=>$empresas,'convenios'=>$convenios,'breadcrumbs'=>$breadcrumbs]);
    }
    //*************************************************
    // LISTA: función para obtener todos los registros
    //*************************************************
    public function lista(Request $request)
    {
        //Obtenemos todos los datos de los empleados
        $registros = Empleado::leftjoin('delegaciones as d', 'd.id', 'empleados.delegacion_id')
            ->leftjoin('empresas as e', 'e.id', 'empleados.empresa_id')
            ->select('empleados.id','empleados.numero','empleados.nombre','empleados.apellidos','empleados.dni',
                      'empleados.telefono','empleados.tip','empleados.activo','d.siglas as delegacion','e.codigo as empresa')
            ->orderBy('empleados.numero', 'asc')->get();

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

        //Comprobamos que haya una imagen
        if ($request->hasFile('imagen')){
            $imagen = $request->file('imagen');
            $imagenEmpleado = date('YmdHis'). "." . $imagen->guessExtension();
            $ruta = public_path('fotos/');
            $imagen->move($ruta,$imagenEmpleado);
            $registro['imagen'] = $imagenEmpleado;
        }

        //Ponemos el usuario que realiza la inserción
        $registro['usuario'] = auth()->user()->email;

        Empleado::create($registro);

    }
    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Empleado::find($request->id);

        //Obtenemos los datos para actualizar los campos
        $registro->numero = $request->numero;
        $registro->nombre = $request->nombre;
        $registro->apellidos = $request->apellidos;
        $registro->dni = $request->dni;
        $registro->operativo = $request->operativo;
        $registro->delegacion_id = $request->delegacion_id;
        $registro->empresa_id = $request->empresa_id;
        $registro->convenio_id = $request->convenio_id;
        $registro->tip = $request->tip;
        $registro->licencia_armas = $request->licencia_armas;
        $registro->vehiculo = $request->vehiculo;
        $registro->email = $request->email;
        $registro->telefono = $request->telefono;
        $registro->telefono2 = $request->telefono2;
        $registro->direccion = $request->direccion;
        $registro->latitud = $request->latitud;
        $registro->longitud = $request->longitud;
        $registro->fecha_alta = $request->fecha_alta;
        $registro->fecha_nacimiento = $request->fecha_nacimiento;
        $registro->cobro_transferencia = $request->cobro_transferencia;
        $registro->cuenta_bancaria = $request->cuenta_bancaria;
        $registro->activo = $request->activo;
        $registro->usuario =  auth()->user()->email;

        //Comprobamos que haya una imagen
        if ($request->hasFile('imagen')){
            $imagen = $request->file('imagen');
            $imagenEmpleado = date('YmdHis'). "." . $imagen->guessExtension();
            $ruta = public_path('fotos/');
            $imagen->move($ruta,$imagenEmpleado);
            $registro['imagen'] = $imagenEmpleado;
        }

        //Guardamos el registro
        $registro->save();

    }

    //*********************************************************
    // ELIMINAR: función para eliminar el registro seleccionado
    //*********************************************************
    public function eliminar(Request $request)
    {
        //Localizamos el registro
        $registro = Empleado ::find($request->id);
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
        $registro = Empleado::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //******************************************************************************************
    // BUSCAEMPLEADO: función para localizar un empleado a partir de su número y que esté activo
    //******************************************************************************************
    public function buscarEmpleado(Request $request)
    {
        //buscamos el número del empleado y que esté activo
        $registro = Empleado::where('numero',$request['numero'])
            ->where('activo',1)
            ->first();

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //*********************************************************************************************
    // BUSCADIRECCION Y
    // GETGEOCODEDATA : funciones para hacer la llamada a la API de google Maps y obtener la dirección
    //*********************************************************************************************
    public function buscarDireccion(Request $request){
        //Función para hacer la llamada a la localización de la dirección

        //Declaración del array de respuesta
        $arrRespuesta=[];

        //Llamamos a la función de localización de la dirección
        $resp=($this->getGeocodeData($request['direccion']));
        if ($resp==false){
            //Como no lo ha encontrado, estado 0
            $arrRespuesta = ['estado'=>0];
        }
        else {
            //Mostramos los datos de la localización en los campos
            $arrRespuesta = ['estado'=>1,'latitud'=>$resp[0],'longitud'=>$resp[1],'direccion'=>$resp[2]];
        }

        //Devolvemos el valor obtenido
        return response()->json($arrRespuesta);
    }

    public function getGeocodeData($address) {
        //Funcion para la localización de dirección por medio de la llamada a la API de google maps
        $address = urlencode($address);
        $googleMapUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDGwrGPU8hsgZHPYRRL2FucJSqPAm496hg&";
        $geocodeResponseData = file_get_contents($googleMapUrl);
        $responseData = json_decode($geocodeResponseData, true);
        if($responseData['status']=='OK') {
            $latitude = isset($responseData['results'][0]['geometry']['location']['lat']) ? $responseData['results'][0]['geometry']['location']['lat'] : "";
            $longitude = isset($responseData['results'][0]['geometry']['location']['lng']) ? $responseData['results'][0]['geometry']['location']['lng'] : "";
            $formattedAddress = isset($responseData['results'][0]['formatted_address']) ? $responseData['results'][0]['formatted_address'] : "";
            if($latitude && $longitude && $formattedAddress) {
                $geocodeData = array();
                array_push(
                    $geocodeData,
                    $latitude,
                    $longitude,
                    $formattedAddress
                );
                return $geocodeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
