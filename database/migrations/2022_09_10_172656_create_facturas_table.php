<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->string('numero',12);
            $table->date('fecha');
            $table->integer('mes');
            $table->integer('ano');
            $table->decimal('base',9,2);
            $table->integer('porcentaje_iva');
            $table->decimal('iva',8,2);
            $table->decimal('total',10,2);
            $table->text('ref_cliente')->nullable();
            $table->text('ref_nuestra')->nullable();
            $table->string('codigo_facturacion',14);
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
        Schema::dropIfExists('facturas');
    }
}
