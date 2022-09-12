<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MovimientoAdelantos extends Model
{
    protected $fillable = ['adelanto_id','empleado_id','tipo','importe','fecha','mes','ano','estado','observaciones','usuario'];

    protected $table = "movimiento_adelantos";

    public function Adelanto(){
        $this->belongsTo(MovimientoAdelantos::class);
    }
}
