@extends('layouts.theme.app')
@section('estilos')
    {{-- ESTILOS PROPIOS DE LA PÁGINA --}}
      @include('Vacaciones.estilos')
@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                             <h3>Vacaciones</h3>--}}
                        </div>
                    </div>
                </div>
                <br>
                <div class="seleccion">
                    <div class="anualidad">
                        {{-- ANUALIDADES --}}
                        <label for="anualidad"><strong>Anualidad</strong></label>
                        <select class="placeholder js-states form-control" style="max-width: 20px" id="anualidad">
                            <option value="">Anualidad</option>
                                {{-- OBETENEMOS LOS AÑOS QUE QUEREMOS FILTRAR (2 POSTERIORES AL AÑO ACTUAL Y 3 ANTERIORES) --}}
                                @php
                                    $x = date('Y')+2;    //Obtenemos el año + 2 años
                                    $y = date('Y')-3;    //Obtenemos el año y le restamos 3
                                    while ($x >= $y){
                                        if ($x == date('Y')){
                                            echo "<option value='" .$x. "' selected='true' >".$x."</option>";
                                        }
                                        else{
                                            echo "<option value='" .$x. "'>".$x."</option>";
                                        }
                                        $x--;
                                    }
                                @endphp
                        </select>
                    </div>
                    <div class="empleado">
                        {{-- EMPLEADOS --}}
                        <label for="empleado_id"><strong>Empleado</strong></label>
                        <select class="placeholder js-states form-control" id="empleado_id">
                            <option value="">Empleado</option>
                            @foreach($empleados as $e)
                                <option value="{{$e->id}}">{{$e->numero}} - {{$e->nombre}} {{$e->apellidos}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="botones">
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_print">Imprimir vacaciones</button>
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_add">Añadir vacaciones</button>
                    </div>
                </div>
                <br>
                <div id ="tabla_" class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_vacaciones" class="tabla_datos table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="text-transform: none">Número</th>
                                <th class="text-center text-dark" style="text-transform: none">Nombre</th>
                                <th class="text-center text-dark" style="text-transform: none">Apellidos</th>
                                <th class="text-center text-dark" style="text-transform: none">Fecha inicio</th>
                                <th class="text-center text-dark" style="text-transform: none">Fecha fin</th>
                                <th class="text-center text-dark" style="text-transform: none">Días</th>
                                <th class="text-center text-dark" style="text-transform: none;width: 150px">Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th  style="display: none">numero</th>
                                <th  style="display: none">nombre</th>
                                <th  style="display: none">apellidos</th>
                                <th  style="display: none">fecha inicio</th>
                                <th  style="display: none">fecha fin</th>
                                <th  style="display: none">dias</th>
                                <th style="display: none"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- FINI: TABLA --}}
                </div>
            </div>
        </div>
    </div>
    {{-- Incluimos la ventana modal --}}
    @include('Vacaciones.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Vacaciones.scripts')
@stop
