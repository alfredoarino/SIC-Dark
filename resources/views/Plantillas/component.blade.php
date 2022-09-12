@extends('layouts.theme.app')
@section('estilos')
    {{-- ESTILOS PROPIOS DE LA PÁGINA --}}
      @include('Plantillas.estilos')
@stop
@section('content')
    {{-- Componente principal --}}
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="widget-header">
                    <div class="page-header">
                        <div class="page-title">
{{--                             <h3>Plantillas horarias</h3>--}}
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
{{--                        <div class="servicio">--}}
                            {{-- SERVICIOS ACTIVADOS CON PLANTILLA --}}
                            <label for="servicio_id"><strong>Servicio</strong></label>
                            <select class="placeholder js-states form-control" id="servicio_id">
                                <option value="">Servicio</option>
                                @foreach($servicios as $s)
                                    <option value="{{$s->id}}">{{$s->numero}} - {{$s->nombre}}</option>
                                @endforeach
                            </select>
{{--                        </div>--}}
{{--                        <div class="botones">--}}
{{--                            <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_print">Imprimir vacaciones</button>--}}
{{--                            <button class="btn btn-primary mr-2 mt-4 text-align-right float-right" type="button" id="btn_add">Añadir vacaciones</button>--}}
{{--                        </div>--}}
                    </div>
                    <div class="col-lg-6">
                        <div class="horarios">
                            <div class="row">
                                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                    <h5><strong>Horario semanal</strong></h5>
                                </div>
                                <div class="form-group col-lg-8 col-md-8 col-sm-12">
                                    <div class="split-buttons float-right">
                                        <button id="btn_add" class="btn btn-primary mb-2">Añadir</button>
                                        <button id="btn_grabar" class="btn btn-primary mb-2">Grabar</button>
                                        <button id="btn_deshacer" class="btn btn-primary mb-2">Deshacer</button>
                                        <button id="btn_eliminar" class="btn btn-danger mb-2">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-2 col-sm-12">
                                    <label for="hora_entrada"><strong>Entrada</strong></label>
                                    <input id="hora_entrada" type="time" maxlength="5"  class="form-control text-center">
                                </div>
                                <div class="form-group col-lg-3 col-md-2 col-sm-12">
                                    <label for="hora_salida"><strong>Salida</strong></label>
                                    <input id="hora_salida" type="time" maxlength="5" class="form-control text-center">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                                    <label for="dia"><strong>Día</strong></label>
                                    <select class="placeholder js-states form-control" id="dia">
                                        <option value="">Día</option>
                                        <option value="L">Lunes</option>
                                        <option value="M">Martes</option>
                                        <option value="X">Miércoles</option>
                                        <option value="J">Jueves</option>
                                        <option value="V">Viernes</option>
                                        <option value="S">Sábado</option>
                                        <option value="D">Domingo</option>
                                        <option value="P">Víspera</option>
                                        <option value="F">Festivo</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                                    <label for="efectivos"><strong>Nº efectivos</strong></label>
                                    <input id="efectivos" type="number" class="form-control text-center">
                                </div>
                            </div>
{{--                            <div class="row">--}}
{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12"></div>--}}
{{--                                <div class="form-group col-lg-3 col-md-3 col-sm-12">--}}
{{--                                    <button class="btn btn-primary ml-3 mt-0" type="button" id="btn_print">Añadir</button>--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-lg-3 col-md-3 col-sm-12">--}}
{{--                                    <button class="btn btn-primary mr-2 mt-0" type="button" id="btn_add">Eliminar</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <br>
                <div id ="tabla" class="table-responsive mb-4">
                    {{-- INICIO: TABLA --}}
                    <table id="tabla_plantilla" class="tabla table  mb-4">
                        <thead>
                            <tr>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Lunes</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Martes</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Miércoles</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Jueves</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Viernes</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Sábado</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Domingo</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Víspera</th>
                                <th class="text-center text-white" style="background: #575860 ;text-transform: none">Festivo</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- FINI: TABLA --}}
                </div>
            </div>
        </div>
    </div>
{{--    --}}{{-- Incluimos la ventana modal --}}
{{--    @include('Vacaciones.form')--}}
@endsection

{{-- Comienza la sección de los SCRIPTS propios --}}
@section('scripts')
    @include('Plantillas.scripts')
@stop
