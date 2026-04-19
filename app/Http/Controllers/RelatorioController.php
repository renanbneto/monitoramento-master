<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\Evento;
use App\Models\ProspeccaoLPR;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelatorioController extends Controller
{
    public function index()
    {
        $totalCameras   = Camera::count();
        $camerasOnline  = Camera::where('status', 'online')->count();
        $camerasOffline = Camera::where('status', 'offline')->count();
        $totalEventos   = Evento::count();
        $eventosAtivos  = Evento::where('ativo', true)->count();
        $totalLPR       = ProspeccaoLPR::count();

        return view('relatorios.index', compact(
            'totalCameras', 'camerasOnline', 'camerasOffline',
            'totalEventos', 'eventosAtivos', 'totalLPR'
        ));
    }

    public function exportCameras(Request $request): Response
    {
        $cameras = Camera::orderBy('cidade')->orderBy('local_nome')
            ->get(['id', 'cidade', 'local_nome', 'ip', 'protocolo', 'formato', 'ativo', 'status', 'status_checked_at']);

        return $this->csvResponse('cameras_' . now()->format('Ymd_His') . '.csv', function ($fh) use ($cameras) {
            fputcsv($fh, ['ID', 'Cidade', 'Local', 'IP', 'Protocolo', 'Formato', 'Ativo', 'Status', 'Último check']);
            foreach ($cameras as $c) {
                fputcsv($fh, [
                    $c->id,
                    $c->cidade,
                    $c->local_nome,
                    $c->ip,
                    $c->protocolo,
                    $c->formato,
                    $c->ativo ? 'Sim' : 'Não',
                    $c->status ?? 'Desconhecido',
                    $c->status_checked_at?->format('d/m/Y H:i') ?? '',
                ]);
            }
        });
    }

    public function exportEventos(Request $request): Response
    {
        $query = Evento::withCount('cameras')->orderByDesc('data_inicio');

        if ($request->filled('de'))  $query->whereDate('data_inicio', '>=', $request->de);
        if ($request->filled('ate')) $query->whereDate('data_inicio', '<=', $request->ate);

        $eventos = $query->get();

        return $this->csvResponse('eventos_' . now()->format('Ymd_His') . '.csv', function ($fh) use ($eventos) {
            fputcsv($fh, ['ID', 'Nome', 'Local', 'Descrição', 'Início', 'Fim', 'Ativo', 'Câmeras', 'Lat', 'Lng']);
            foreach ($eventos as $e) {
                fputcsv($fh, [
                    $e->id,
                    $e->nome,
                    $e->local_nome,
                    strip_tags($e->descricao ?? ''),
                    $e->data_inicio?->format('d/m/Y') ?? '',
                    $e->data_fim?->format('d/m/Y') ?? '',
                    $e->ativo ? 'Sim' : 'Não',
                    $e->cameras_count,
                    $e->lat,
                    $e->lng,
                ]);
            }
        });
    }

    public function exportLPR(Request $request): Response
    {
        $lpr = ProspeccaoLPR::orderByDesc('created_at')->get([
            'id', 'nome', 'cidade', 'bairro', 'endereco', 'sentido',
            'lat', 'lng', 'cadastrada_por', 'created_at',
        ]);

        return $this->csvResponse('lpr_' . now()->format('Ymd_His') . '.csv', function ($fh) use ($lpr) {
            fputcsv($fh, ['ID', 'Nome', 'Cidade', 'Bairro', 'Endereço', 'Sentido', 'Lat', 'Lng', 'Cadastrada por', 'Data cadastro']);
            foreach ($lpr as $p) {
                fputcsv($fh, [
                    $p->id,
                    $p->nome,
                    $p->cidade,
                    $p->bairro,
                    $p->endereco,
                    $p->sentido,
                    $p->lat,
                    $p->lng,
                    $p->cadastrada_por,
                    $p->created_at?->format('d/m/Y H:i') ?? '',
                ]);
            }
        });
    }

    private function csvResponse(string $filename, callable $writer): Response
    {
        $output = fopen('php://temp', 'r+');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8 para Excel
        $writer($output);
        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return response($content, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-store',
        ]);
    }
}
