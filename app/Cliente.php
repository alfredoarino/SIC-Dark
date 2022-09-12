<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cliente extends Model
{
    protected $fillable = ['razonsocial','cif','direccion','poblacion','provincia','cp',
                           'email','telefono','cuentacontable','delegacion_id','empresa_id',
                           'sector_id','forma_pago_id','facturas_conjuntas','factura_electronica','observaciones','usuario'];

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
