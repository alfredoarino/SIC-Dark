<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plantilla extends Model
{
    protected $fillable = ['servicio_id','dia','hora_entrada','hora_salida','efectivos','usuario'];
    use SoftDeletes;

}
