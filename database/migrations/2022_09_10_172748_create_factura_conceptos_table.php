<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_conceptos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->foreign('factura_id')->references('id')->on('facturas');
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->string('concepto',100);
            $table->integer('cantidad');
            $table->decimal('precio_unidad',9,2);
            $table->decimal('descuento',9,2);
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
        Schema::dropIfExists('factura_conceptos');
    }
}
