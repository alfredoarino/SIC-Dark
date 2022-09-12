<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ContactoCliente extends Model
{
    protected $fillable = ['cliente_id','nombre','apellidos','cargo',
                           'telefono','email','observaciones','usuario'];

    protected $table = "contacto_clientes";
    use SoftDeletes;

}
