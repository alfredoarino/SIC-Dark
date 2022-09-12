<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacto_clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->string('nombre',50)->nullable();
            $table->string('apellidos',75)->nullable();
            $table->string('cargo',100)->nullable();
            $table->string('telefono',9)->nullable();
            $table->string('email',100)->nullable();
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
        Schema::dropIfExists('contacto_clientes');
    }
}
