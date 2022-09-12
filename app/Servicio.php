<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Servicio extends Model
{
    protected $fillable = ['cliente_id','numero','nombre','nombre_reducido','telefono','direccion','latitud',
        'longitud','empresa_id','delegacion_id','pago_id','tipo_taria','importe','fecha_tarifa','sin_movimientos','concepto_factura',
        'factura_manual','plantilla','serie','contrato','copias','ref_cliente','ref_nuestra','activo','observaciones','usuario'];
}
