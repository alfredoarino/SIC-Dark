@extends('layouts.theme.app')
@section('estilos')
    {{-- ESTILOS PROPIOS DE LA PÁGINA --}}
      @include('Festivos.estilos')
@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
                             <h3>Festivos</h3>
                        </div>
                    </div>
                </div>
                <br>
                <div class="seleccion" >
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
                                    echo "<option value='" .$x. "'>".$x."</option>";
                                    $x--;
                                }
                            @endphp
                        </select>
                    </div>
                    <div></div>
                    <div class="botones">
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_print">Imprimir festivos</button>
                        <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_add">Añadir festivos</button>
                    </div>
                </div>
                <br>
                <div id ="tabla_" class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_festivos" class="tabla_datos table table-hover">
                        <thead class="bg-sgls">
                            <tr>
                                <th class="text-center text-dark" style="display: none">id</th>
                                <th class="text-center text-dark" style="width: 80px;text-transform: none">Fecha</th>
                                <th class="text-center text-dark" style="text-transform: none">Nombre</th>
                                <th class="text-center text-dark" style="text-transform: none">Delegación</th>
                                <th class="text-center text-dark" style="text-transform: none;width: 150px">Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="display: none"></th>
                                <th>fecha</th>
                                <th>nombre</th>
                                <th>delegacion</th>
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
    @include('Festivos.form')
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Festivos.scripts')
@stop
