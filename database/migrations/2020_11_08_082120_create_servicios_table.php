<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->integer('numero');
            $table->string('nombre',100);
            $table->string('nombre_reducido',50);
            $table->string('telefono',9);
            $table->string('direccion',255);
            $table->string('latitud',25);
            $table->string('longitud',25);
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('delegacion_id');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
            $table->unsignedBigInteger('pago_id');
            $table->foreign('pago_id')->references('id')->on('pagos');
            $table->enum('tipo_tarifa',['M','H'])->default('H');
            $table->decimal('importe',9,2)->default(0);
            $table->date('fecha_tarifa');
            $table->boolean('sin_movimientos')->default(0);
            $table->boolean('factura_manual')->default(0);
            $table->string('serie',4);
            $table->string('contrato',10)->nullable();
            $table->integer('copias')->default(2);
            $table->text('ref_cliente')->nullable();
            $table->text('ref_nuestra')->nullable();
            $table->boolean('activo')->default(1);
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
        Schema::dropIfExists('servicios');
    }
}
