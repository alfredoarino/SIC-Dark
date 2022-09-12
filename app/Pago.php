<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pago extends Model
{
    protected $fillable = ['nombre','importe','incentivos','usuario'];

    use SoftDeletes;

}
