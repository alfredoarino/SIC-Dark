<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Festivo extends Model
{
    //
    protected $fillable = ['fecha','nombre','delegacion_id','usuario'];

    use SoftDeletes;

}
