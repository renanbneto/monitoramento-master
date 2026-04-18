<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\CameraMosaic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MosaicoController extends Controller
{
    public function index(Request $request)
    {
        $mosaicos = CameraMosaic::where('user_id', Auth::id())
            ->orderBy('nome')
            ->get();

        $cameras = Camera::where('ativo', true)
            ->orderBy('cidade')
            ->orderBy('camera')
            ->get(['id', 'cidade', 'camera', 'local_nome', 'link', 'ativo']);

        $addId = $request->query('add_id');
        if ($addId !== null && $addId !== '') {
            $addId = (int) $addId;
            if ($addId <= 0 || ! Camera::whereKey($addId)->exists()) {
                $addId = null;
            }
        } else {
            $addId = null;
        }

        return view('monitoramento.mosaicos.index', compact('mosaicos', 'cameras', 'addId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'         => 'required|string|max:160',
            'camera_ids'   => 'nullable|array',
            'camera_ids.*' => 'integer|exists:cameras,id',
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['camera_ids'] ?? [])));

        CameraMosaic::create([
            'user_id'    => Auth::id(),
            'nome'       => $data['nome'],
            'camera_ids' => $ids,
        ]);

        return redirect()->route('mosaicos.index')->with('status', 'Mosaico criado.');
    }

    public function show(CameraMosaic $mosaico)
    {
        $this->authorizeMosaic($mosaico);

        $cameras = $mosaico->camerasOrdenadas();

        return view('monitoramento.mosaicos.show', compact('mosaico', 'cameras'));
    }

    public function update(Request $request, CameraMosaic $mosaico)
    {
        $this->authorizeMosaic($mosaico);

        $data = $request->validate([
            'nome'         => 'required|string|max:160',
            'camera_ids'   => 'nullable|array',
            'camera_ids.*' => 'integer|exists:cameras,id',
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['camera_ids'] ?? [])));

        $mosaico->update([
            'nome'       => $data['nome'],
            'camera_ids' => $ids,
        ]);

        return redirect()->route('mosaicos.index')->with('status', 'Mosaico atualizado.');
    }

    public function destroy(CameraMosaic $mosaico)
    {
        $this->authorizeMosaic($mosaico);
        $mosaico->delete();

        return redirect()->route('mosaicos.index')->with('status', 'Mosaico removido.');
    }

    private function authorizeMosaic(CameraMosaic $mosaico): void
    {
        abort_unless((int) $mosaico->user_id === (int) Auth::id(), 403);
    }
}
