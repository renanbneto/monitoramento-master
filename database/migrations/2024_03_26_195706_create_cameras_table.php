<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cameras', function (Blueprint $table) {
            $table->id();
            $table->string('servidor')->nullable();
            $table->string('cidade')->nullable();
            $table->string('ip')->nullable();
            $table->string('porta')->nullable();
            $table->string('camera')->nullable();
            $table->string('local_nome')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('usuario')->nullable();
            $table->string('senha')->nullable();
            $table->string('protocolo')->nullable();
            $table->string('vms')->nullable();
            $table->string('formato')->nullable();
            $table->string('hostname')->nullable();
            $table->string('link')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['servidor','cidade','camera']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cameras');
    }
}
