<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('Cargo');
            $table->string('NomeCompleto');
            $table->string('DatadeNascimento');
            $table->string('RG');
            $table->string('CPF');
            $table->string('Endereco');
            $table->string('Email');
            $table->string('Telefone');
            $table->string('Banco');
            $table->string('Agencia');
            $table->string('Conta');
            $table->unsignedBigInteger('seguradora_id');
            $table->foreign('seguradora_id')->references('id')->on('seguradoras');
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
        Schema::dropIfExists('funcionarios');
    }
}
