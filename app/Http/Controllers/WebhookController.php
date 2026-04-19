<?php

namespace App\Http\Controllers;

use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Posição de viaturas em tempo real (AVL/GPS).
     * Payload esperado: { "prefixo": "...", "lat": -25.4, "lng": -49.2, "velocidade": 40, "ignicao": true }
     */
    public function viaturas(Request $request)
    {
        $data = $request->validate([
            'prefixo'    => 'required|string|max:20',
            'lat'        => 'required|numeric|between:-90,90',
            'lng'        => 'required|numeric|between:-180,180',
            'velocidade' => 'nullable|numeric|min:0',
            'ignicao'    => 'nullable|boolean',
        ]);

        Audit::log('webhook.viaturas', 'viatura', null, ['prefixo' => $data['prefixo']]);
        Log::channel('stack')->info('webhook.viaturas', $data);

        // TODO: persistir e disparar evento WebSocket quando Broadcasting estiver ativo
        return response()->json(['ok' => true]);
    }

    /**
     * Posição de rádios/policiais.
     * Payload esperado: { "rg": "...", "lat": -25.4, "lng": -49.2 }
     */
    public function radios(Request $request)
    {
        $data = $request->validate([
            'rg'  => 'required|string|max:20',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        Audit::log('webhook.radios', 'radio', null, ['rg' => $data['rg']]);
        Log::channel('stack')->info('webhook.radios', $data);

        return response()->json(['ok' => true]);
    }

    /**
     * Ocorrências ativas do CAD/COPOM.
     * Payload esperado: { "id_ocorrencia": "...", "natureza": "...", "lat": -25.4, "lng": -49.2, "status": "aberta" }
     */
    public function ocorrencias(Request $request)
    {
        $data = $request->validate([
            'id_ocorrencia' => 'required|string|max:50',
            'natureza'      => 'required|string|max:100',
            'lat'           => 'required|numeric|between:-90,90',
            'lng'           => 'required|numeric|between:-180,180',
            'status'        => 'required|in:aberta,encerrada,deslocamento',
        ]);

        Audit::log('webhook.ocorrencias', 'ocorrencia', null, [
            'id'       => $data['id_ocorrencia'],
            'natureza' => $data['natureza'],
        ]);
        Log::channel('stack')->info('webhook.ocorrencias', $data);

        return response()->json(['ok' => true]);
    }

    /**
     * Alerta de botão do pânico — Programa Maria da Penha (MPF/SEED).
     * Payload: { "id_alerta": "...", "lat": -25.4, "lng": -49.2, "tipo": "mpf|escola", "descricao": "..." }
     */
    public function panico(Request $request, string $tipo)
    {
        $data = $request->validate([
            'id_alerta'  => 'required|string|max:50',
            'lat'        => 'required|numeric|between:-90,90',
            'lng'        => 'required|numeric|between:-180,180',
            'descricao'  => 'nullable|string|max:500',
        ]);

        $data['tipo'] = $tipo;
        Audit::log("webhook.panico.{$tipo}", 'panico', null, ['id' => $data['id_alerta']]);
        Log::channel('stack')->info("webhook.panico.{$tipo}", $data);

        return response()->json(['ok' => true]);
    }

    /**
     * Leitura LPR de câmera (cruzamento com lista BOLO futuro).
     * Payload: { "placa": "ABC1D23", "camera_id": 10, "imagem_base64": "..." }
     */
    public function lpr(Request $request)
    {
        $data = $request->validate([
            'placa'           => 'required|string|max:10',
            'camera_id'       => 'nullable|integer|exists:cameras,id',
            'imagem_base64'   => 'nullable|string',
        ]);

        // Normaliza placa (remove traço, maiúsculas)
        $data['placa'] = strtoupper(str_replace('-', '', $data['placa']));

        Audit::log('webhook.lpr', 'leitura_lpr', null, [
            'placa'     => $data['placa'],
            'camera_id' => $data['camera_id'] ?? null,
        ]);
        Log::channel('stack')->info('webhook.lpr', ['placa' => $data['placa'], 'camera_id' => $data['camera_id'] ?? null]);

        return response()->json(['ok' => true, 'placa' => $data['placa']]);
    }
}
