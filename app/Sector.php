<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sector extends Model
{
    protected $table = "sectores";

    protected $fillable = ['nombre','usuario'];

    use SoftDeletes;
//    public function tarifa()
//    {
//    	return $this->belongsTo(Tarifa::class, 'tipo_id');
//    }
//
//    public function cajon()
//    {
//    	return $this->belongsTo(Cajon::class, 'tipo_id');
//    }

}
