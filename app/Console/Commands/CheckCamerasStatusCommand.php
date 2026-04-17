<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckCamerasStatusCommand extends Command
{
    protected $signature = 'cameras:check-status';
    protected $description = 'Verifica o status de conectividade de todas as câmeras ativas';

    public function handle(): int
    {
        $cameras = Camera::where('ativo', true)
            ->whereNotNull('link')
            ->where('link', '!=', '')
            ->get(['id', 'link']);

        $this->info("Verificando {$cameras->count()} câmeras...");

        foreach ($cameras->chunk(20) as $chunk) {
            foreach ($chunk as $camera) {
                $this->checkCamera($camera);
            }
        }

        $this->info('Verificação concluída.');

        return Command::SUCCESS;
    }

    private function checkCamera(Camera $camera): void
    {
        $status = 'offline';
        $responseMs = null;

        try {
            $start = microtime(true);
            $response = Http::timeout(5)->head($camera->link);
            $responseMs = (int) round((microtime(true) - $start) * 1000);

            // Qualquer resposta HTTP (incluindo 4xx) significa que o servidor está acessível
            $status = 'online';
        } catch (\Throwable $e) {
            // Timeout ou erro de rede = offline
        }

        $camera->update([
            'status'           => $status,
            'status_checked_at' => now(),
            'status_response_ms' => $responseMs,
        ]);
    }
}
