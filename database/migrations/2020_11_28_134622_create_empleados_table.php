<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->nullable();
            $table->string('nombre',50)->nullable();
            $table->string('apellidos',75)->nullable();
            $table->string('dni',10)->nullable();
            $table->boolean('operativo')->default(1);
            $table->unsignedBigInteger('delegacion_id');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('convenio_id');
            $table->foreign('convenio_id')->references('id')->on('convenios');
            $table->string('tip',6)->nullable();
            $table->boolean('licencia_armas')->default(0);
            $table->boolean('vehiculo')->default(1);
            $table->string('email',100)->nullable();
            $table->string('telefono',9)->nullable();
            $table->string('direccion',255)->nullable();
            $table->string('latitud',25)->nullable();
            $table->string('longitud',25)->nullable();
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->boolean('cobro_transferencia')->default(1);
            $table->string('cuenta_bancaria',24)->nullable();
            $table->boolean('activo')->default(1);
            $table->string('imagen',50)->nullable();
            $table->string('usuario',100)->nullable();
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
        Schema::dropIfExists('empleados');
    }
}
