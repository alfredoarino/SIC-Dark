<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoAdelantosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_adelantos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adelanto_id');
            $table->foreign('adelanto_id')->references('id')->on('adelantos');
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->string('tipo',1);
            $table->decimal('importe',9,2)->nullable();
            $table->date('fecha')->nullable();
            $table->integer('mes')->nullable();
            $table->integer('ano')->nullable();
            $table->integer('estado')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('usuario',100);
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
        Schema::dropIfExists('movimiento_adelantos');
    }
}
