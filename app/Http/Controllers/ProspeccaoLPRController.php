<?php

namespace App\Http\Controllers;

use App\Models\ProspeccaoLPR;
use App\Support\Audit;
use Illuminate\Http\Request;

class ProspeccaoLPRController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->ajax()) {
            return datatables()->of(ProspeccaoLPR::query())
                ->addColumn('acoes', function ($row) {
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" onclick="excluirCamera(this)">Excluir</button>';
                    return $deleteBtn;
                })
                ->rawColumns(['acoes'])
                ->make(true);
        }

        return view("monitoramento.prospeccaoLPR.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required',
            'cidade' => 'required',
            'bairro' => 'required',
            'endereco' => 'required',
            'sentido' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $validated['cadastrada_por'] = session()->get('user')->nome ?? null;
        $validated['cadastrada_por_cpf'] = session()->get('user')->cpf ?? null;
        $validated['user_id'] = session()->get('user')->id ?? null;

        try {
            $prospeccao = ProspeccaoLPR::create($validated);
            Audit::log('lpr.create', 'ProspeccaoLPR', $prospeccao->id, [
                'nome'     => $validated['nome'],
                'endereco' => $validated['endereco'],
            ]);
            return response()->json(['success' => true, 'data' => $prospeccao], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao cadastrar', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProspeccaoLPR  $prospeccaoLPR
     * @return \Illuminate\Http\Response
     */
    public function show(ProspeccaoLPR $prospeccaoLPR)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProspeccaoLPR  $prospeccaoLPR
     * @return \Illuminate\Http\Response
     */
    public function edit(ProspeccaoLPR $prospeccaoLPR)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProspeccaoLPR  $prospeccaoLPR
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProspeccaoLPR $prospeccaoLPR)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ProspeccaoLPR::destroy($id);
            return response()->json(['success' => true, 'message' => 'Registro excluído com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir o registro.', 'error' => $e->getMessage()], 500);
        }
    }
}
