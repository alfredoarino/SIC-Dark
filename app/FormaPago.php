<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FormaPago extends Model
{
    protected $fillable = ['nombre','usuario'];

    protected $table = "forma_pagos";
    use SoftDeletes;

}
