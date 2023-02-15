<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPermissoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_permissoes', function (Blueprint $table) {
            $table->id();
            $table->string('cpf')->unique();
            $table->string('f_admin')->default('0');
            $table->string('f_desenvolvedor')->default('0');
            $table->string('f_usuario')->default('0');
            $table->string('f_medico')->default('0');

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
        Schema::dropIfExists('tb_permissoes');
    }
}
