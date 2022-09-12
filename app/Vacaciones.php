<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Vacaciones extends Model
{
    protected $fillable = ['empleado_id', 'fecha_inicio','fecha_fin','dias','anualidad','usuario'];

    use SoftDeletes;

    protected $table = "vacaciones";

    public function Vacaciones(){
        return $this->hasMany(Vacaciones::class);
    }

}
