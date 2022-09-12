<?php

use Illuminate\Support\Facades\Route;

//** ADMINISTRACION **/
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\ClientesContactosController;
use App\Http\Controllers\ClientesAccionesController;
use App\Http\Controllers\AyudasController;
use App\Http\Controllers\VacacionesController;

//** GERENCIA **/
use App\Http\Controllers\AdelantosController;
use App\Http\Controllers\MovimientoAdelantosController;
use App\Http\Controllers\GratificacionesController;

//** INSPECCIÓN **/
use App\Http\Controllers\PlantillasController;
use App\Http\Controllers\GeneracionController;
use App\Http\Controllers\MovimientosController;

//** SOPORTE **/
use App\Http\Controllers\DelegacionesController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\PlusesController;
use App\Http\Controllers\SectoresController;
use App\Http\Controllers\FormasPagoController;
use App\Http\Controllers\FestivosController;

//** SERIES **//
use App\Http\Controllers\SeriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Cuando se llame a la raiz. Comprobamos que haya un usuario logeado
Route::get('/', function () {
    if (Route::has('login')) {
        //Si no está logeado, lo llevamos a la pantalla de login
        return redirect('/login');
    }
    else{
        //Vamos a la pantalla home
        return redirect('/home');
    }
});

Auth::routes();

//*******************************************************************
//                          MENUS
//*******************************************************************

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('gestion', [App\Http\Controllers\GestionController::class, 'index'])->name('gestion');
Route::get('rrhh', [App\Http\Controllers\RrhhController::class, 'index'])->name('rrhh');
Route::get('facturacion', [App\Http\Controllers\FacturacionController::class, 'index'])->name('facturacion');
Route::get('inspeccion', [App\Http\Controllers\InspeccionController::class, 'index'])->name('inspeccion');
//*******************************************************************
//                          S O P O R T E
//*******************************************************************

//***********
// DELEGACIONES
//***********
//Vista principal
Route::get('delegaciones', [DelegacionesController::class,'index'])->name('delegaciones');
//Lista completa de las delegaciones
Route::get('delegaciones/lista', [DelegacionesController::class,'lista'])->name('delegaciones.lista');
//Nuevo registro
Route::post('delegaciones/nuevo', [DelegacionesController::class,'nuevo'])->name('delegaciones.nuevo');
//Actualizar registro
Route::post('delegaciones/actualizar', [DelegacionesController::class,'actualizar'])->name('delegaciones.actualizar');
//Eliminar registro
Route::post('delegaciones/eliminar', [DelegacionesController::class,'eliminar'])->name('delegaciones.eliminar');
//Búsquedas
Route::get('delegaciones/buscarRegistro', [DelegacionesController::class,'buscarRegistro'])->name('delegaciones.buscarRegistro');

//***********
// EMPRESAS
//***********
//Vista principal
Route::get('empresas', [EmpresasController::class,'index'])->name('empresas');
//Lista completa
Route::get('empresas/lista', [EmpresasController::class,'lista'])->name('empresas.lista');
//Nuevo registro
Route::post('empresas/nuevo', [EmpresasController::class,'nuevo'])->name('empresas.nuevo');
//Actualizar registro
Route::post('empresas/actualizar', [EmpresasController::class,'actualizar'])->name('empresas.actualizar');
//Eliminar registro
Route::post('empresas/eliminar', [EmpresasController::class,'eliminar'])->name('empresas.eliminar');
//Búsquedas
Route::get('empresas/buscarRegistro', [EmpresasController::class,'buscarRegistro'])->name('empresas.buscarRegistro');

//***********
// PAGOS
//***********
//Vista principal
Route::get('pagos', [PagosController::class,'index'])->name('pagos');
//Lista completa
Route::get('pagos/lista', [PagosController::class,'lista'])->name('pagos.lista');
//Nuevo registro
Route::post('pagos/nuevo', [PagosController::class,'nuevo'])->name('pagos.nuevo');
//Actualizar registro
Route::post('pagos/actualizar', [PagosController::class,'actualizar'])->name('pagos.actualizar');
//Eliminar registro
Route::post('pagos/eliminar', [PagosController::class,'eliminar'])->name('pagos.eliminar');
//Búsquedas
Route::get('pagos/buscarRegistro', [PagosController::class,'buscarRegistro'])->name('pagos.buscarRegistro');

//***********
// PLUSES
//***********
//Vista principal
Route::get('pluses', [PlusesController::class,'index'])->name('pluses');
//Lista completa
Route::get('pluses/lista', [PlusesController::class,'lista'])->name('pluses.lista');
//Nuevo registro
Route::post('pluses/nuevo', [PlusesController::class,'nuevo'])->name('pluses.nuevo');
//Actualizar registro
Route::post('pluses/actualizar', [PlusesController::class,'actualizar'])->name('pluses.actualizar');
//Eliminar registro
Route::post('pluses/eliminar', [PlusesController::class,'eliminar'])->name('pluses.eliminar');
//Búsquedas
Route::get('pluses/buscarRegistro', [PlusesController::class,'buscarRegistro'])->name('pluses.buscarRegistro');

//***********
// SECTORES
//***********
//Vista principal
Route::get('sectores', [SectoresController::class,'index'])->name('sectores');
//Lista completa
Route::get('sectores/lista', [SectoresController::class,'lista'])->name('sectores.lista');
//Nuevo registro
Route::post('sectores/nuevo', [SectoresController::class,'nuevo'])->name('sectores.nuevo');
//Actualizar registro
Route::post('sectores/actualizar', [SectoresController::class,'actualizar'])->name('sectores.actualizar');
//Eliminar registro
Route::post('sectores/eliminar', [SectoresController::class,'eliminar'])->name('sectores.eliminar');
//Búsquedas
Route::get('sectores/buscarRegistro', [SectoresController::class,'buscarRegistro'])->name('sectores.buscarRegistro');

//****************
// FORMAS DE PAGO
//****************
//Vista principal
Route::get('formasPago', [FormasPagoController::class,'index'])->name('formasPago');
//Lista completa
Route::get('formasPago/lista', [FormasPagoController::class,'lista'])->name('formasPago.lista');
//Nuevo registro
Route::post('formasPago/nuevo', [FormasPagoController::class,'nuevo'])->name('formasPago.nuevo');
//Actualizar registro
Route::post('formasPago/actualizar', [FormasPagoController::class,'actualizar'])->name('formasPago.actualizar');
//Eliminar registro
Route::post('formasPago/eliminar', [FormasPagoController::class,'eliminar'])->name('formasPago.eliminar');
//Búsquedas
Route::get('formasPago/buscarRegistro', [FormasPagoController::class,'buscarRegistro'])->name('formasPago.buscarRegistro');

//***********
// FESTIVOS
//***********
//Vista principal
Route::get('festivos', [FestivosController::class,'index'])->name('festivos');
//Lista completa
Route::get('festivos/lista', [FestivosController::class,'lista'])->name('festivos.lista');
//Nuevo registro
Route::post('festivos/nuevo', [FestivosController::class,'nuevo'])->name('festivos.nuevo');
//Actualizar registro
Route::post('festivos/actualizar', [FestivosController::class,'actualizar'])->name('festivos.actualizar');
//Eliminar registro
Route::post('festivos/eliminar', [FestivosController::class,'eliminar'])->name('festivos.eliminar');
//Búsquedas
Route::get('festivos/buscarRegistro', [FestivosController::class,'buscarRegistro'])->name('festivos.buscarRegistro');
//Imprimir
Route::get('festivos/imprimir', [FestivosController::class,'imprimir'])->name('festivos.imprimir');


//*******************************************************************
//                   A D M I N I S T R A C I O N
//*******************************************************************

//**************
// EMPLEADOS
//**************
//Vista principal
Route::get('empleados', [EmpleadosController::class,'index'])->name('empleados');
//Lista completa
Route::get('empleados/lista', [EmpleadosController::class,'lista'])->name('empleados.lista');
//Nuevo registro
Route::post('empleados/nuevo', [EmpleadosController::class,'nuevo'])->name('empleados.nuevo');
//Actualizar registro
Route::post('empleados/actualizar', [EmpleadosController::class,'actualizar'])->name('empleados.actualizar');
//Eliminar registro
Route::post('empleados/eliminar', [EmpleadosController::class,'eliminar'])->name('empleados.eliminar');
//Búsquedas
Route::get('empleados/buscarRegistro', [EmpleadosController::class,'buscarRegistro'])->name('empleados.buscarRegistro');
Route::get('empleados/buscarDireccion', [EmpleadosController::class,'buscarDireccion'])->name('empleados.buscarDireccion');
Route::get('empleados/buscarEmpleado', [EmpleadosController::class,'buscarEmpleado'])->name('empleados.buscarEmpleado');

//**************
// CLIENTES
//**************
//Vista principal
Route::get('clientes', [ClientesController::class,'index'])->name('clientes');
//Lista completa
Route::get('clientes/lista', [ClientesController::class,'lista'])->name('clientes.lista');
//Nuevo registro
Route::post('clientes/nuevo', [ClientesController::class,'nuevo'])->name('clientes.nuevo');
//Actualizar registro
Route::post('clientes/actualizar', [ClientesController::class,'actualizar'])->name('clientes.actualizar');
//Eliminar registro
Route::post('clientes/eliminar', [ClientesController::class,'eliminar'])->name('clientes.eliminar');
//Búsquedas
Route::get('clientes/buscarRegistro', [ClientesController::class,'buscarRegistro'])->name('clientes.buscarRegistro');

//**************
// SERVICIOS
//**************
//Vista principal
Route::get('servicios', [ServiciosController::class,'index'])->name('servicios');
//Lista completa
Route::get('servicios/lista', [ServiciosController::class,'lista'])->name('servicios.lista');
//Nuevo registro
Route::post('servicios/nuevo', [ServiciosController::class,'nuevo'])->name('servicios.nuevo');
//Actualizar registro
Route::post('servicios/actualizar', [ServiciosController::class,'actualizar'])->name('servicios.actualizar');
//Eliminar registro
Route::post('servicios/eliminar', [ServiciosController::class,'eliminar'])->name('servicios.eliminar');
//Búsquedas
Route::get('servicios/buscarRegistro', [ServiciosController::class,'buscarRegistro'])->name('servicios.buscarRegistro');
Route::get('servicios/buscarDireccion', [ServiciosController::class,'buscarDireccion'])->name('servicios.buscarDireccion');
Route::get('servicios/buscarServicio', [ServiciosController::class,'buscarServicio'])->name('servicios.buscarServicio');
//Varios
Route::get('servicios/obtenerNumeroServicio', [ServiciosController::class,'obtenerNumeroServicio'])->name('servicios.obtenerNumeroServicio');
Route::get('servicios/obtenerSerie', [ServiciosController::class,'obtenerSerie'])->name('servicios.obtenerSerie');

//**************
// CONTACTOS
//**************
//Lista completa
Route::get('contactos/lista', [ClientesContactosController::class,'lista'])->name('contactos.lista');
//Nuevo registro
Route::post('contactos/nuevo', [ClientesContactosController::class,'nuevo'])->name('contactos.nuevo');
//Actualizar registro
Route::post('contactos/actualizar', [ClientesContactosController::class,'actualizar'])->name('contactos.actualizar');
//Eliminar registro
Route::post('contactos/eliminar', [ClientesContactosController::class,'eliminar'])->name('contactos.eliminar');
//Búsquedas
Route::get('contactos/buscarRegistro', [ClientesContactosController::class,'buscarRegistro'])->name('contactos.buscarRegistro');

//**************
// ACCIONES
//**************
//Lista completa
Route::get('acciones/lista', [ClientesAccionesController::class,'lista'])->name('acciones.lista');
//Nuevo registro
Route::post('acciones/nuevo', [ClientesAccionesController::class,'nuevo'])->name('acciones.nuevo');
//Actualizar registro
Route::post('acciones/actualizar', [ClientesAccionesController::class,'actualizar'])->name('acciones.actualizar');
//Eliminar registro
Route::post('acciones/eliminar', [ClientesAccionesController::class,'eliminar'])->name('acciones.eliminar');
//Búsquedas
Route::get('acciones/buscarRegistro', [ClientesAccionesController::class,'buscarRegistro'])->name('acciones.buscarRegistro');


//***********
// AYUDAS
//***********
//Vista principal
Route::get('ayudas', [AyudasController::class,'index'])->name('ayudas');
//Lista completa
Route::get('ayudas/lista', [AyudasController::class,'lista'])->name('ayudas.lista');
//Nuevo registro
Route::post('ayudas/nuevo', [AyudasController::class,'nuevo'])->name('ayudas.nuevo');
//Actualizar registro
Route::post('ayudas/actualizar', [AyudasController::class,'actualizar'])->name('ayudas.actualizar');
//Eliminar registro
Route::post('ayudas/eliminar', [AyudasController::class,'eliminar'])->name('ayudas.eliminar');
//Búsquedas
Route::get('ayudas/buscarRegistro', [AyudasController::class,'buscarRegistro'])->name('ayudas.buscarRegistro');
//Imprimir
Route::get('ayudas/imprimir', [AyudasController::class,'imprimir'])->name('ayudas.imprimir');

//***********
// VACACIONES
//***********
//Vista principal
Route::get('vacaciones', [VacacionesController::class,'index'])->name('vacaciones');
//Lista completa
Route::get('vacaciones/lista', [VacacionesController::class,'lista'])->name('vacaciones.lista');
//Nuevo registro
Route::post('vacaciones/nuevo', [VacacionesController::class,'nuevo'])->name('vacaciones.nuevo');
//Actualizar registro
Route::post('vacaciones/actualizar', [VacacionesController::class,'actualizar'])->name('vacaciones.actualizar');
//Eliminar registro
Route::post('vacaciones/eliminar', [VacacionesController::class,'eliminar'])->name('vacaciones.eliminar');
//Búsquedas
Route::get('vacaciones/buscarRegistro', [VacacionesController::class,'buscarRegistro'])->name('vacaciones.buscarRegistro');
//Imprimir
Route::get('vacaciones/imprimir', [VacacionesController::class,'imprimir'])->name('vacaciones.imprimir');

//********************************************************
//                   G E R E N C I A
//********************************************************

//******************
// GRATIFICACIONES
//*****************
//Vista principal
Route::get('gratificaciones', [GratificacionesController::class,'index'])->name('gratificaciones');
//Lista completa
Route::get('gratificaciones/lista', [GratificacionesController::class,'lista'])->name('gratificaciones.lista');
//Nuevo registro
Route::post('gratificaciones/nuevo', [GratificacionesController::class,'nuevo'])->name('gratificaciones.nuevo');
//Actualizar registro
Route::post('gratificaciones/actualizar', [GratificacionesController::class,'actualizar'])->name('gratificaciones.actualizar');
//Eliminar registro
Route::post('gratificaciones/eliminar', [GratificacionesController::class,'eliminar'])->name('gratificaciones.eliminar');
//Búsquedas
Route::get('gratificaciones/buscarRegistro', [GratificacionesController::class,'buscarRegistro'])->name('gratificaciones.buscarRegistro');

//**************
// ADELANTOS
//**************
//Vista principal
Route::get('adelantos', [AdelantosController::class,'index'])->name('adelantos');
//Lista completa
Route::get('adelantos/lista', [AdelantosController::class,'lista'])->name('adelantos.lista');
//Nuevo registro
Route::post('adelantos/nuevo', [AdelantosController::class,'nuevo'])->name('adelantos.nuevo');
//Actualizar registro
Route::post('adelantos/actualizar', [AdelantosController::class,'actualizar'])->name('adelantos.actualizar');
//Eliminar registro
Route::post('adelantos/eliminar', [AdelantosController::class,'eliminar'])->name('adelantos.eliminar');
//Búsquedas
Route::get('adelantos/buscarRegistro', [AdelantosController::class,'buscarRegistro'])->name('adelantos.buscarRegistro');
//Imprimir
Route::get('adelantos/imprimir', [AdelantosController::class,'imprimir'])->name('adelantos.imprimir');

//******************************
// MOVIMIENTOS DE LOS ADELANTOS
//******************************
//Lista de movimientos
Route::get('movimientoAdelantos/lista', [MovimientoAdelantosController::class,'lista'])->name('movimientoAdelantos.lista');
//Obtener información
Route::get('movimientoAdelantos/obtenerInformacion', [MovimientoAdelantosController::class,'obtenerInformacion'])->name('movimientoAdelantos.obtenerInformacion');
//Creación de los movimientos del nuevo adelanto
Route::post('movimientoAdelantos/creaMovimientos', [MovimientoAdelantosController::class,'creaMovimientos'])->name('movimientoAdelantos.creaMovimientos');
//Creación de un aumento del adelanto
Route::post('movimientoAdelantos/creaAumento', [MovimientoAdelantosController::class,'creaAumento'])->name('movimientoAdelantos.creaAumento');
//Añade movimientos a partir de un aumento
Route::post('movimientoAdelantos/addMovimientos', [MovimientoAdelantosController::class,'addMovimientos'])->name('movimientoAdelantos.addMovimientos');
//Aplazar un plazo
Route::post('movimientoAdelantos/aplazar', [MovimientoAdelantosController::class,'aplazar'])->name('movimientoAdelantos.aplazar');
//Calcular el importe pendiente
Route::get('movimientoAdelantos/pendiente', [MovimientoAdelantosController::class,'pendiente'])->name('movimientoAdelantos.pendiente');
////Editar un movimiento
//Route::post('movimientoAdelantos/editarMovimiento', [MovimientoAdelantosController::class,'editarMovimiento'])->name('movimientoAdelantos.editarMovimiento');
//Actualizar un aumento
Route::post('movimientoAdelantos/actualizarAumento', [MovimientoAdelantosController::class,'actualizarAumento'])->name('movimientoAdelantos.actualizarAumento');
//Actualizar un plazo
Route::post('movimientoAdelantos/actualizarPlazo', [MovimientoAdelantosController::class,'actualizarPlazo'])->name('movimientoAdelantos.actualizarPlazo');

//********************************************************
//                   I N S P E C C I O N
//********************************************************

//**************
// PLANTILLAS
//**************
//Vista principal
Route::get('plantillas', [PlantillasController::class,'index'])->name('plantillas');
//Buscar un registro
Route::get('plantillas/buscarRegistro', [PlantillasController::class,'buscarRegistro'])->name('plantillas.buscarRegistro');
//Datos de la plantilla
Route::get('plantillas/lista', [PlantillasController::class,'lista'])->name('plantillas.lista');
//Graba registro
Route::post('plantillas/grabarRegistro', [PlantillasController::class,'grabarRegistro'])->name('plantillas.grabarRegistro');
//Elimina registro
Route::post('plantillas/eliminar', [PlantillasController::class,'eliminar'])->name('plantillas.eliminar');

//**************
// GENERACION
//**************
//Vista principal
Route::get('generacion', [GeneracionController::class,'index'])->name('generacion');
Route::get('generacion/cargarPlantillas', [GeneracionController::class,'cargarPlantillas'])->name('generacion.cargarPlantillas');

//**************
// MOVIMIENTOS
//**************
//Vista principal
Route::get('movimientos', [MovimientosController::class,'index'])->name('movimientos');
Route::get('movimientos/cargaMovimientos', [MovimientosController::class,'cargaMovimientos'])->name('movimientos.cargaMovimientos');
Route::get('movimientos/buscarRegistro', [MovimientosController::class,'buscarRegistro'])->name('movimientos.buscarRegistro');
Route::post('movimientos/grabarMovimiento', [MovimientosController::class,'grabarMovimiento'])->name('movimientos.grabarMovimiento');
Route::get('movimientos/conflictos', [MovimientosController::class,'conflictos'])->name('movimientos.conflictos');
Route::get('movimientos/informacion', [MovimientosController::class,'informacion'])->name('movimientos.informacion');
Route::post('movimientos/copiarMovimiento', [MovimientosController::class,'copiarMovimiento'])->name('movimientos.copiarMovimiento');
Route::post('movimientos/eliminarMovimiento', [MovimientosController::class,'eliminarMovimiento'])->name('movimientos.eliminarMovimiento');

//**************************************************
//                   O T R O S
//**************************************************

//**************
// SERIES
//**************
//Nuevo registro
Route::post('series/nuevo', [SeriesController::class,'nuevo'])->name('series.nuevo');
