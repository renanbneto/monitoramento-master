<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMosaicoCameraTable extends Migration
{
    public function up()
    {
        Schema::create('mosaico_camera', function (Blueprint $table) {
            $table->foreignId('mosaico_id')->constrained()->onDelete('cascade');
            $table->foreignId('camera_id')->constrained()->onDelete('cascade');
            $table->smallInteger('ordem')->default(0);
            $table->primary(['mosaico_id', 'camera_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mosaico_camera');
    }
}
