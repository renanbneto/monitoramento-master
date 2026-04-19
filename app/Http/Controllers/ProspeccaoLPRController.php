<?php

namespace App\Http\Controllers;

use App\Models\ProspeccaoLPR;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ProspeccaoLPRController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(ProspeccaoLPR::query())
                ->addColumn('acoes', function ($row) {
                    $id = (int) $row->id;
                    return '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $id . '" onclick="excluirCamera(this)">Excluir</button>';
                })
                ->rawColumns(['acoes'])
                ->make(true);
        }

        return view('monitoramento.prospeccaoLPR.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'     => 'required|string|max:255',
            'cidade'   => 'required|string|max:150',
            'bairro'   => 'required|string|max:150',
            'endereco' => 'required|string|max:255',
            'sentido'  => 'required|string|max:100',
            'lat'      => 'required|numeric|between:-90,90',
            'lng'      => 'required|numeric|between:-180,180',
        ]);

        $validated['cadastrada_por']     = session()->get('user')->nome ?? null;
        $validated['cadastrada_por_cpf'] = session()->get('user')->cpf  ?? null;
        $validated['user_id']            = session()->get('user')->id   ?? null;

        try {
            $prospeccao = ProspeccaoLPR::create($validated);
            Audit::log('lpr.create', 'ProspeccaoLPR', $prospeccao->id, [
                'nome'     => $validated['nome'],
                'endereco' => $validated['endereco'],
            ]);
            return response()->json(['success' => true, 'data' => $prospeccao], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao cadastrar.'], 500);
        }
    }

    public function show(ProspeccaoLPR $prospeccaoLPR)
    {
        //
    }

    public function edit(ProspeccaoLPR $prospeccaoLPR)
    {
        //
    }

    public function update(Request $request, ProspeccaoLPR $prospeccaoLPR)
    {
        $this->autorizarModificacao($prospeccaoLPR);

        $validated = $request->validate([
            'nome'     => 'required|string|max:255',
            'cidade'   => 'required|string|max:150',
            'bairro'   => 'required|string|max:150',
            'endereco' => 'required|string|max:255',
            'sentido'  => 'required|string|max:100',
            'lat'      => 'required|numeric|between:-90,90',
            'lng'      => 'required|numeric|between:-180,180',
        ]);

        try {
            $prospeccaoLPR->update($validated);
            Audit::log('lpr.update', 'ProspeccaoLPR', $prospeccaoLPR->id, [
                'nome'     => $validated['nome'],
                'endereco' => $validated['endereco'],
            ]);
            return response()->json(['success' => true, 'data' => $prospeccaoLPR]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar.'], 500);
        }
    }

    public function destroy($id)
    {
        $prospeccaoLPR = ProspeccaoLPR::findOrFail($id);
        $this->autorizarModificacao($prospeccaoLPR);

        try {
            $prospeccaoLPR->delete();
            Audit::log('lpr.delete', 'ProspeccaoLPR', $prospeccaoLPR->id);
            return response()->json(['success' => true, 'message' => 'Registro excluído com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir o registro.'], 500);
        }
    }

    private function autorizarModificacao(ProspeccaoLPR $prospeccaoLPR): void
    {
        $isAdmin  = Str::of(Session::get('autorizacoes', ''))->contains('Administrador');
        $userId   = session()->get('user')->id ?? null;
        $isOwner  = $userId !== null && $prospeccaoLPR->user_id === $userId;

        if (! $isAdmin && ! $isOwner) {
            abort(403, 'Sem permissão para modificar este registro.');
        }
    }
}
