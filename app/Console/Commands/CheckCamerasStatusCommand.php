<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
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
            ->where('link', '!=', '#')
            ->get(['id', 'link', 'ip', 'porta']);

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
        [$status, $responseMs] = $this->probeCamera($camera);

        $camera->update([
            'status'              => $status,
            'status_checked_at'   => now(),
            'status_response_ms'  => $responseMs,
        ]);
    }

    /**
     * Ordem: GET com stream (MJPEG / GetJPEGStream não terminam o corpo), depois HEAD leve, depois TCP.
     * HEAD antes do GET falhava em muitos VMS (sem suporte ou resposta incorreta para streams).
     *
     * @return array{0: string, 1: int|null}
     */
    private function probeCamera(Camera $camera): array
    {
        $link = $camera->link ?? '';
        if ($link === '' || $link === '#') {
            return ['offline', null];
        }

        $get = $this->tryGetStream($link);
        if ($get !== null) {
            return $get;
        }

        $head = $this->tryHead($link);
        if ($head !== null) {
            return $head;
        }

        if ($this->tryTcp($camera)) {
            return ['online', null];
        }

        return ['offline', null];
    }

    /**
     * @return array{0: string, 1: int|null}|null
     */
    private function tryGetStream(string $url): ?array
    {
        try {
            $start = microtime(true);
            $response = $this->http()
                ->timeout(6)
                ->withOptions(['stream' => true])
                ->get($url);

            $ms = (int) round((microtime(true) - $start) * 1000);

            $body = $response->toPsrResponse()->getBody();
            if ($body->isReadable()) {
                $body->read(4096);
            }

            if ($response->status() >= 100 && $response->status() < 600) {
                return ['online', $ms];
            }
        } catch (\Throwable $e) {
            //
        }

        return null;
    }

    /**
     * @return array{0: string, 1: int|null}|null
     */
    private function tryHead(string $url): ?array
    {
        try {
            $start = microtime(true);
            $response = $this->http()->timeout(3)->head($url);
            $ms = (int) round((microtime(true) - $start) * 1000);

            if ($response->status() >= 100 && $response->status() < 600) {
                return ['online', $ms];
            }
        } catch (\Throwable $e) {
            //
        }

        return null;
    }

    private function tryTcp(Camera $camera): bool
    {
        $ip = $camera->ip ?? '';
        $port = $camera->porta ?? '';
        if ($ip === '' || $port === '') {
            return false;
        }

        $portNum = (int) $port;
        if ($portNum <= 0 || $portNum > 65535) {
            return false;
        }

        $errno = 0;
        $errstr = '';
        $fp = @fsockopen($ip, $portNum, $errno, $errstr, 3.0);

        if (is_resource($fp)) {
            fclose($fp);

            return true;
        }

        return false;
    }

    private function http(): PendingRequest
    {
        return Http::withOptions([
            'verify' => $this->sslVerify(),
        ])->withHeaders([
            'User-Agent' => 'MonitoramentoCameraStatus/1.0',
            'Accept'     => '*/*',
        ]);
    }

    private function sslVerify(): bool
    {
        $v = env('CAMERAS_STATUS_VERIFY_SSL', true);
        $parsed = filter_var($v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return $parsed ?? true;
    }
}
