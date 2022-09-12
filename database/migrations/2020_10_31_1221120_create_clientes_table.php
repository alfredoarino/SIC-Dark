<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('razonsocial',100);
            $table->string('cif',12);
            $table->string('direccion',100);
            $table->string('poblacion',75);
            $table->string('provincia',50);
            $table->string('cp',5);
            $table->string('email',100);
            $table->string('telefono',9);
            $table->string('telefono2',9);
            $table->string('cuentacontable',10);
            $table->unsignedBigInteger('delegacion_id');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('sector_id');
            $table->foreign('sector_id')->references('id')->on('sectores');
            $table->unsignedBigInteger('forma_pago_id');
            $table->foreign('forma_pago_id')->references('id')->on('forma_pagos');
            $table->boolean('facturas_conjuntas')->default(0);
            $table->boolean('factura_electronica')->default(0);
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
