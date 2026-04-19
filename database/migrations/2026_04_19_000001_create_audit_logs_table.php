<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_rg', 30)->nullable();
            $table->string('user_nome', 150)->nullable();
            $table->string('acao', 100);           // ex: camera.view, evento.create, lpr.consulta
            $table->string('recurso', 80)->nullable();    // ex: Camera, Evento, ProspeccaoLPR
            $table->unsignedBigInteger('recurso_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->json('detalhes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['acao', 'created_at']);
            $table->index(['user_rg', 'created_at']);
            $table->index('recurso');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
