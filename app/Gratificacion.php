<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gratificacion extends Model
{
    protected $fillable = ['empleado_id','concepto','importe','aplica_descuento','usuario'];

    use SoftDeletes;

    //
    protected $table = "gratificaciones";
}
