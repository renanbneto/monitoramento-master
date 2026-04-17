<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoCameraTable extends Migration
{
    public function up()
    {
        Schema::create('evento_camera', function (Blueprint $table) {
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('camera_id');
            $table->primary(['evento_id', 'camera_id']);
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->foreign('camera_id')->references('id')->on('cameras')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evento_camera');
    }
}
