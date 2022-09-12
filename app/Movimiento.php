<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = ['servicio_id','empleado_id','fecha_entrada','fecha_salida','hora_entrada','hora_salida',
        'plus_id','horas_dia','horas_resto','estado','servicio_conflicto','factura_id',
        'usuario'];

}
