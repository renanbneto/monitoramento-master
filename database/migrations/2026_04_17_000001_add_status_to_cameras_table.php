<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCamerasTable extends Migration
{
    public function up()
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->string('status', 10)->default('unknown')->after('ativo');
            $table->timestamp('status_checked_at')->nullable()->after('status');
            $table->integer('status_response_ms')->nullable()->after('status_checked_at');
        });
    }

    public function down()
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->dropColumn(['status', 'status_checked_at', 'status_response_ms']);
        });
    }
}
