<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspeccaoLPRSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospeccao_l_p_r_s', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->nullable();
            $table->string('cidade')->nullable();
            $table->string('bairro')->nullable();
            $table->text('endereco')->nullable();
            $table->string('sentido')->nullable();
            $table->string('cadastrada_por')->nullable();
            $table->string('cadastrada_por_cpf')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
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
        Schema::dropIfExists('prospeccao_l_p_r_s');
    }
}
