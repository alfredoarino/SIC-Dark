<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Tablas utilizadas
use App\Servicio;
use App\Delegacion;
use App\Empresa;
use App\Cliente;
use App\Pago;
use App\Serie;

class ServiciosController extends Controller
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
        //Obtenemos todos los datos de los clientes
        $clientes = Cliente::all();
        //Obtenemos todos los datos de los pagos
        $pagos = Pago::all();

        //Obtenemos la ruta desde que ha sido llamado
        $ruta = substr( url()->previous(),strrpos(url()->previous(),'/')+1,strlen(url()->previous()));

        //Vemos de que modulo ha sido llamado
        if ($ruta == 'inspeccion'){
            $link = 'inspeccion';
            $name = 'Inspección';
        }
        else{
            $link = 'facturacion';
            $name = 'Facturación';
        }

        //Montamos las migas de pan
        $breadcrumbs = [['link' => "home", 'name' => "Inicio"],['link' => $link, 'name' => $name],
            ['link' => "", 'name' => "Servicios"]];
        //Renderizamos la vista, pasándole las tablas necesarias para el mantenimiento
        return view('servicios.component', ['delegaciones'=>$delegaciones,'empresas'=>$empresas,'clientes'=>$clientes,
                         'pagos'=>$pagos,'breadcrumbs'=>$breadcrumbs]);
    }

    //*************************************************
    // LISTA: función para obtener todos los registros
    //*************************************************
    public function lista(Request $request)
    {
        //Obtenemos todos los datos
        $registros = Servicio::leftjoin('delegaciones as d', 'd.id', 'servicios.delegacion_id')
            ->leftjoin('empresas as e', 'e.id', 'servicios.empresa_id')
            ->leftjoin('clientes as c', 'c.id', 'servicios.cliente_id')
            ->select('servicios.id','servicios.numero','servicios.nombre','c.razonsocial as cliente',
                     'd.siglas as delegacion','e.codigo as empresa')
            ->orderBy('servicios.numero', 'asc')->get();

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

        //Ponemos el usuario que realiza la inserción
        $registro['usuario'] = auth()->user()->email;

        Servicio::create($registro);

    }
    //*************************************************
    // ACTUALIZAR: función para actualizar el registro
    //*************************************************
    public function actualizar(Request $request)
    {
        //Accedemos al registro
        $registro = Servicio::find($request->id);

        //Obtenemos los datos para actualizar los campos
        $registro->numero = $request->numero;
        $registro->nombre = $request->nombre;
        $registro->nombre_reducido = $request->nombre_reducido;
        $registro->cliente_id = $request->cliente_id;
        $registro->empresa_id = $request->empresa_id;
        $registro->delegacion_id = $request->delegacion_id;
        $registro->telefono = $request->telefono;
        $registro->direccion = $request->direccion;
        $registro->latitud = $request->latitud;
        $registro->longitud = $request->longitud;
        $registro->pago_id = $request->pago_id;
        $registro->tipo_tarifa = $request->tipo_tarifa;
        $registro->importe = floatval($request->importe);
        $registro->copias = $request->copias;
        $registro->fecha_tarifa = $request->fecha_tarifa;
        $registro->serie = $request->serie;
        $registro->contrato = $request->contrato;
        $registro->sin_movimientos = $request->sin_movimientos;
        $registro->concepto_factura = $request->concepto_factura;
        $registro->plantilla = $request->plantilla;
        $registro->factura_manual = $request->factura_manual;
        $registro->activo = $request->activo;
        $registro->ref_cliente = $request->ref_cliente;
        $registro->ref_nuestra = $request->ref_nuestra;
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
        $registro = Servicio ::find($request->id);
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
        $registro = Servicio::findOrFail($request['id']);

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //******************************************************************************************
    // BUSCARSERVICIO: función para localizar un servicio a partir de su número y que esté activo
    //******************************************************************************************
    public function buscarServicio(Request $request)
    {
        //buscamos el número del empleado y que esté activo
        $registro = Servicio::where('numero',$request['numero'])
            ->where('activo',1)
            ->first();

        //Devolvemos el registro obtenido
        return response()->json($registro);
    }

    //******************************************************************************************
    // OBTENERSERIE: Función específica de la clase de servicios para obtener el numero de serie
    //******************************************************************************************
    public function obtenerSerie ()
    {
        $ciclos = 0;    //control del número de ciclos que se procesan

        //Realizamos el proceso de búsqueda de la serie hasta que genere un código que no exista en la tabla
        //series o que el proceso se ejecute 100 veces como máximo
        while ($ciclos < 100)
        {
            //Se genera un código aleatorio de 4 letras
            $serie = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);

            //Buscamos en la tabla de series la cadena que hemos generado
            $existe = Serie::where('serie', $serie)->get();

            //Si no encontramos esa serie generada, devolvemos ese valor
            if($existe->count() == 0 ) {
                return response()->json($serie);
            }
            //Sumamos 1 al control de cliclos
            $ciclos++;
        }
        return false;
    }
    //*****************************************************************************************************************
    // OBTENERNUMEROSERVICIO: Función específica de la clase de servicios para obtener el siguiente número del servicio
    //*****************************************************************************************************************
    public function obtenerNumeroServicio ()
    {
        //
        //Obtenemos el múmero maximo de la tabla
        $siguiente_numero = Servicio::max('numero');
        $siguiente_numero = $siguiente_numero + 1;
        //Devolmemos ese numero máximo, habiéndole sumado 1 más
        return response()->json($siguiente_numero);
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
