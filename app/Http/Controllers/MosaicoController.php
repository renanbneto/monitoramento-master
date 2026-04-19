<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\Mosaico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MosaicoController extends Controller
{
    public function index()
    {
        $mosaicos = Mosaico::where('user_id', Auth::id())
            ->withCount('cameras')
            ->with(['cameras' => function ($q) { $q->limit(4); }])
            ->latest()
            ->get();

        return view('mosaicos.index', compact('mosaicos'));
    }

    public function create()
    {
        $cameras = Camera::where('ativo', true)->orderBy('local_nome')->get();
        return view('mosaicos.create', compact('cameras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'       => 'required|string|max:100',
            'descricao'  => 'nullable|string|max:255',
            'cameras'    => 'nullable|array',
            'cameras.*'  => 'exists:cameras,id',
        ]);

        $mosaico = Mosaico::create([
            'user_id'   => Auth::id(),
            'nome'      => $request->nome,
            'descricao' => $request->descricao,
        ]);

        if ($request->cameras) {
            $sync = [];
            foreach ($request->cameras as $ordem => $cameraId) {
                $sync[$cameraId] = ['ordem' => $ordem];
            }
            $mosaico->cameras()->sync($sync);
        }

        return redirect()->route('mosaicos.show', $mosaico)
                         ->with('success', 'Mosaico "' . $mosaico->nome . '" criado com sucesso.');
    }

    public function show(Mosaico $mosaico)
    {
        abort_if($mosaico->user_id !== Auth::id(), 403);
        $mosaico->load('cameras');
        return view('mosaicos.show', compact('mosaico'));
    }

    public function edit(Mosaico $mosaico)
    {
        abort_if($mosaico->user_id !== Auth::id(), 403);
        $cameras      = Camera::where('ativo', true)->orderBy('local_nome')->get();
        $selecionadas = $mosaico->cameras->pluck('id')->toArray();
        return view('mosaicos.edit', compact('mosaico', 'cameras', 'selecionadas'));
    }

    public function update(Request $request, Mosaico $mosaico)
    {
        abort_if($mosaico->user_id !== Auth::id(), 403);

        $request->validate([
            'nome'       => 'required|string|max:100',
            'descricao'  => 'nullable|string|max:255',
            'cameras'    => 'nullable|array',
            'cameras.*'  => 'exists:cameras,id',
        ]);

        $mosaico->update([
            'nome'      => $request->nome,
            'descricao' => $request->descricao,
        ]);

        $sync = [];
        foreach ($request->cameras ?? [] as $ordem => $cameraId) {
            $sync[$cameraId] = ['ordem' => $ordem];
        }
        $mosaico->cameras()->sync($sync);

        return redirect()->route('mosaicos.show', $mosaico)
                         ->with('success', 'Mosaico atualizado com sucesso.');
    }

    public function destroy(Mosaico $mosaico)
    {
        abort_if($mosaico->user_id !== Auth::id(), 403);
        $mosaico->delete();
        return redirect()->route('mosaicos.index')
                         ->with('success', 'Mosaico removido.');
    }
}
