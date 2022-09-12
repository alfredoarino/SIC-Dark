<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValesdetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valesdetalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vale_id');
            $table->foreign('vale_id')->references('id')->on('vales');
            $table->unsignedBigInteger('articulo_id');
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->integer('cantidad');
            $table->string('usuario',100);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('valesdetalle');
    }
}
