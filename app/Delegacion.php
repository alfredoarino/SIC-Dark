<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class delegacion extends Model
{
    protected $fillable = ['nombre','siglas','usuario'];

    //
    protected $table = "delegaciones";
    use SoftDeletes;
}
