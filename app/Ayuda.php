<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Ayuda extends Model
{
    protected $fillable = ['mes','ano','empleado_id','gasolina','juzgados','baja_enfermedad','baja_accidente','inspecciones','minusvalia','otros','usuario'];

    use SoftDeletes;

}
