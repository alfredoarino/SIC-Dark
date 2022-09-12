<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFestivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festivos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('nombre',100);
            $table->unsignedBigInteger('delegacion_id');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
            $table->string('usuario',100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('festivos');
    }
}
