<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAyudasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ayudas', function (Blueprint $table) {
            $table->id();
            $table->integer('mes');
            $table->integer('ano');
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->decimal('gasolina',8,2)->nullable();
            $table->decimal('juzgados',8,2)->nullable();
            $table->decimal('baja_enfermedad',8,2)->nullable();
            $table->decimal('baja_accidente',8,2)->nullable();
            $table->decimal('inspecciones',8,2)->nullable();
            $table->decimal('minusvalia',8,2)->nullable();
            $table->decimal('otros',8,2)->nullable();
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
        Schema::dropIfExists('ayudas');
    }
}
