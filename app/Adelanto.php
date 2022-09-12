<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Adelanto extends Model
{
    protected $fillable = ['empleado_id','fecha','importe','importe_plazo','observaciones','estado','usuario'];

    protected $table = "adelantos";

    public function CuotaAdelantos(){
        return $this->hasMany(MovimientoAdelantos::class);
    }

}
