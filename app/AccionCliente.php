<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccionCliente extends Model
{
    protected $fillable = ['cliente_id','fecha_inicio','fecha_fin','accion',
        'estado','usuario'];

    protected $table = "accion_clientes";
    use SoftDeletes;

}
