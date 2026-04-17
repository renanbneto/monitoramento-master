<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OnibusController extends Controller
{
    public function index()
    {
        $httpOptions = static function (): array {
            $cfg = config('transporte_urbs');
            $base = [
                'timeout' => $cfg['http']['timeout'],
                'connect_timeout' => $cfg['http']['connect_timeout'],
            ];
            $useProxy = ! app()->environment('local') || ($cfg['proxy']['use_in_local'] ?? false);
            $proxyUrl = $cfg['proxy']['url'] ?? null;
            if ($useProxy && is_string($proxyUrl) && $proxyUrl !== '') {
                return array_merge($base, ['proxy' => $proxyUrl]);
            }

            return $base;
        };

        $fetchUrbs = static function (string $path) use ($httpOptions) {
            $cfg = config('transporte_urbs');
            $url = $cfg['base_url'] . '/' . $path . '?c=' . urlencode((string) $cfg['access_code']);
            $response = Http::withOptions($httpOptions())->get($url);
            if (! $response->successful()) {
                return null;
            }
            $json = $response->json();
            if (is_array($json)) {
                return $json;
            }
            $decoded = json_decode($response->body(), true);

            return is_array($decoded) ? $decoded : null;
        };

        try {
            $onibusTtl = max(1, (int) config('transporte_urbs.cache.onibus_ttl'));
            $linhasTtl = max(1, (int) config('transporte_urbs.cache.linhas_ttl'));
            $epLinhas = config('transporte_urbs.endpoints.linhas');
            $epVeiculos = config('transporte_urbs.endpoints.veiculos');

            $registros = Cache::remember('urbs_onibus', $onibusTtl, function () use ($fetchUrbs, $linhasTtl, $epLinhas, $epVeiculos) {
                $linhas = Cache::remember('urbs_linhas', $linhasTtl, function () use ($fetchUrbs, $epLinhas) {
                    $data = $fetchUrbs($epLinhas);

                    return is_array($data) ? $data : [];
                });

                $registros = $fetchUrbs($epVeiculos);
                if (! is_array($registros)) {
                    return [];
                }

                $now = Carbon::now('America/Sao_Paulo');

                foreach ($registros as $prefixo => &$veiculo) {
                    if (! is_array($veiculo)) {
                        continue;
                    }

                    $status = 'desconhecido';
                    if (! empty($veiculo['REFRESH'])) {
                        try {
                            $refreshTime = Carbon::createFromFormat('H:i', $veiculo['REFRESH'], 'America/Sao_Paulo');
                            if ($refreshTime->gt($now)) {
                                $refreshTime->subDay();
                            }
                            $minutesDiff = $now->diffInMinutes($refreshTime);
                            if ($minutesDiff <= 2) {
                                $status = 'online';
                            } elseif ($minutesDiff <= 5) {
                                $status = 'atrasado';
                            } elseif ($minutesDiff <= 10) {
                                $status = 'offline';
                            }
                        } catch (\Throwable $e) {
                            $status = 'desconhecido';
                        }
                    }

                    $codLinha = $veiculo['CODIGOLINHA'] ?? '';
                    if ($codLinha !== '' && $codLinha !== 'REC') {
                        $linha = collect($linhas)->firstWhere('COD', $codLinha);
                        if ($linha) {
                            $veiculo['NOME_LINHA'] = $linha['NOME'] ?? null;
                            $veiculo['CATEGORIA_LINHA'] = $linha['CATEGORIA_SERVICO'] ?? null;
                            $veiculo['COR_LINHA'] = $linha['NOME_COR'] ?? null;
                        }
                    }

                    $veiculo['STATUS'] = $status;
                }
                unset($veiculo);

                return $registros;
            });

            return response()->json($registros);
        } catch (\Throwable $th) {
            report($th);

            return response()->json([]);
        }
    }
}
