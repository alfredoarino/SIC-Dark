<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Plus extends Model
{
    protected $fillable = ['nombre','importe','usuario'];

    protected $table = "pluses";


}
