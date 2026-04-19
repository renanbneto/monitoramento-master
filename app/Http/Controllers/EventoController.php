<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function count()
    {
        return response()->json(['total' => Evento::count()]);
    }

    public function jsonAtivos(Request $request)
    {
        $query = Evento::select('id', 'nome', 'descricao', 'local_nome', 'lat', 'lng', 'data_inicio', 'data_fim', 'ativo')
            ->withCount('cameras')
            ->orderByDesc('data_inicio');

        if (! $request->boolean('todos')) {
            $query->where('ativo', true);
        }

        $eventos = $query->get()
            ->map(fn ($e) => [
                'id'          => $e->id,
                'nome'        => $e->nome,
                'descricao'   => $e->descricao,
                'local_nome'  => $e->local_nome,
                'lat'         => (float) $e->lat,
                'lng'         => (float) $e->lng,
                'data_inicio' => $e->data_inicio?->format('d/m/Y'),
                'data_fim'    => $e->data_fim?->format('d/m/Y'),
                'cameras'     => $e->cameras_count,
            ]);

        return response()->json($eventos);
    }

    public function index()
    {
        $eventos = Evento::withCount('cameras')->orderByDesc('data_inicio')->get();
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $cameras = Camera::where('ativo', true)->orderBy('cidade')->orderBy('camera')->get();
        return view('eventos.create', compact('cameras'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'        => 'required|string|max:255',
            'descricao'   => 'nullable|string',
            'local_nome'  => 'nullable|string|max:255',
            'lat'         => 'nullable|string|max:20',
            'lng'         => 'nullable|string|max:20',
            'data_inicio' => 'nullable|date',
            'data_fim'    => 'nullable|date',
            'ativo'       => 'nullable',
            'cameras'     => 'nullable|array',
            'cameras.*'   => 'exists:cameras,id',
        ]);

        $data['ativo'] = isset($data['ativo']);

        $evento = Evento::create($data);
        $evento->cameras()->sync($request->input('cameras', []));

        return redirect()->route('eventos.show', $evento)->with('success', 'Evento criado com sucesso.');
    }

    public function show(Evento $evento)
    {
        $evento->load('cameras');
        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $cameras = Camera::where('ativo', true)->orderBy('cidade')->orderBy('camera')->get();
        $camerasSelecionadas = $evento->cameras->pluck('id')->toArray();
        return view('eventos.edit', compact('evento', 'cameras', 'camerasSelecionadas'));
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'nome'        => 'required|string|max:255',
            'descricao'   => 'nullable|string',
            'local_nome'  => 'nullable|string|max:255',
            'lat'         => 'nullable|string|max:20',
            'lng'         => 'nullable|string|max:20',
            'data_inicio' => 'nullable|date',
            'data_fim'    => 'nullable|date',
            'ativo'       => 'nullable',
            'cameras'     => 'nullable|array',
            'cameras.*'   => 'exists:cameras,id',
        ]);

        $data['ativo'] = isset($data['ativo']);

        $evento->update($data);
        $evento->cameras()->sync($request->input('cameras', []));

        return redirect()->route('eventos.show', $evento)->with('success', 'Evento atualizado com sucesso.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento removido com sucesso.');
    }
}
