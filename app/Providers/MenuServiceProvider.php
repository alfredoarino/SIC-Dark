<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /*******************************************************************************************
     *  PROPORCIONAMOS A TODAS LAS VISTAS EL MENÚ QUE CORRESPONDA SEGÚN EL MÓDULO SELECCIONADO *
     *******************************************************************************************/
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Obtenemos los menus de los diferentes módulos
        //GESTION
        $menuGestionJson = file_get_contents(base_path('resources/data/menu-data/menuGestion.json'));
        $menuGestionData = json_decode($menuGestionJson);
        //RRHH
        $menuRrhhJson = file_get_contents(base_path('resources/data/menu-data/menuRrhh.json'));
        $menuRrhhData = json_decode($menuRrhhJson);
        //FACTURACION
        $menuFacturacionJson = file_get_contents(base_path('resources/data/menu-data/menuFacturacion.json'));
        $menuFacturacionData = json_decode($menuFacturacionJson);
        //INSPECCION
        $menuInspeccionJson = file_get_contents(base_path('resources/data/menu-data/menuInspeccion.json'));
        $menuInspeccionData = json_decode($menuInspeccionJson);

        // Compartimos los menús con todas las vistas
        \View::share('menuData',[$menuGestionData,$menuRrhhData,$menuFacturacionData,$menuInspeccionData]);
    }
}
