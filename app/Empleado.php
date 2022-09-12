<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    protected $fillable = ['numero','nombre','apellidos','dni','operativo','delegacion_id','empresa_id','convenio_id',
        'tip','licencia_armas','vehiculo','email','telefono','telefono2','direccion','latitud','longitud','fecha_alta','fecha_nacimiento',
        'cobro_transferencia','cuenta_bancaria','activo','imagen','usuario'];
    use SoftDeletes;
}
