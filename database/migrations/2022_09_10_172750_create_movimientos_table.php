<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->time('hora_entrada');
            $table->time('hora_salida');
            $table->unsignedBigInteger('plus_id')->nullable();
            $table->foreign('plus_id')->references('id')->on('pluses');
            $table->decimal('horas_dia',5,2);
            $table->decimal('horas_resto',5,2);
            $table->string('estado',1);
            $table->unsignedBigInteger('servicio_conflicto')->nullable();
            $table->string('codigo_facturacion',14)->nullable();
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
        Schema::dropIfExists('movimientos');
    }
}
