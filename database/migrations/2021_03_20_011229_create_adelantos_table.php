<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdelantosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adelantos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->date('fecha');
            $table->decimal('importe',9,2)->default(0);
            $table->decimal('importe_plazo',9,2)->default(0);
            $table->text('observaciones')->nullable();
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('adelantos');
    }
}
