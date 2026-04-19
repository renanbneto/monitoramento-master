<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedFields;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;
use App\Models\Cidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CameraController extends Controller
{


    /**
     * Retorna cidade - estado para select2
     *
     * @return void
     */
    public function cidades(){

        try {

            $dados = request()->input('term');
            $termo = $dados["term"];
            return Cidade::join('estados', DB::raw('CAST(cidades.uf_cod AS INTEGER)'), '=', 'estados.codigo')
            ->selectRaw("concat(cidades.nome,' - ',estados.sigla) as text")
            ->where('cidades.nome','ilike','%'.$termo.'%')
            ->get();
        } catch (\Throwable $th) {
            return response([$th->getMessage()],500);
        }
    }

    /**
     * Display a view of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        $cameras = Camera::get();
        return view('monitoramento.view',compact('cameras'));
    }

    public function statusJson()
    {
        $cameras = Camera::select('id', 'status', 'status_checked_at', 'status_response_ms')->get();
        $result = [];
        foreach ($cameras as $camera) {
            $result[$camera->id] = [
                'status'      => $camera->status ?? 'unknown',
                'checked_at'  => $camera->status_checked_at?->toISOString(),
                'response_ms' => $camera->status_response_ms,
            ];
        }
        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        
        //->allowedFields('local_nome','cidade','camera','lat','lng');

        if(request()->has('termo') && request()->input('termo') != ''){
            $termo = request()->input('termo');
            return Camera::where(function($query) use ($termo) {
                $query->where('local_nome', 'ilike', "%{$termo}%")
                    ->orWhere('cidade', 'ilike', "%{$termo}%")
                    ->orWhere('camera', 'ilike', "%{$termo}%");
            })->get();
        }

        return Camera::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mosaicos()
    {
        return User::select('mosaico')->find(Auth::user()->id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function atualizaMosaicos(Request $request)
    {
        $raw = $request->input('mosaico', '{}');
        $decoded = json_decode($raw, true);
        $mosaico = is_array($decoded) ? json_encode($decoded) : '{}';

        return User::find(Auth::id())->update(['mosaico' => $mosaico]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lat = request()->query('lat');
        $lng = request()->query('lng');
        return view('monitoramento.create',compact('lat','lng'));
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
            'servidor'   => 'nullable|string|max:255',
            'cidade'     => 'required|string|max:255',
            'ip'         => 'nullable|string|max:255',
            'porta'      => 'nullable|integer|min:1|max:65535',
            'camera'     => 'nullable|string|max:255',
            'local_nome' => 'required|string|max:255',
            'lat'        => 'nullable|numeric|between:-90,90',
            'lng'        => 'nullable|numeric|between:-180,180',
            'usuario'    => 'nullable|string|max:255',
            'senha'      => 'nullable|string|max:255',
            'protocolo'  => 'nullable|string|max:50',
            'vms'        => 'nullable|string|max:255',
            'formato'    => 'nullable|string|max:50',
            'hostname'   => 'nullable|string|max:255',
            'link'       => 'nullable|string|max:1000',
            'ativo'      => 'nullable|boolean',
        ]);

        try {
            Camera::create($validated);
        } catch (\Throwable $th) {
            Log::error('Erro ao criar câmera: ' . $th->getMessage(), ['exception' => $th]);
            return back()->withErrors(['error' => 'Erro ao salvar câmera.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Camera  $camera
     * @return \Illuminate\Http\Response
     */
    public function show(Camera $camera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Camera  $camera
     * @return \Illuminate\Http\Response
     */
    public function edit(Camera $camera)
    {
        return view('monitoramento.edit',compact('camera'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Camera  $camera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Camera $camera)
    {
        $validated = $request->validate([
            'servidor'   => 'nullable|string|max:255',
            'cidade'     => 'required|string|max:255',
            'ip'         => 'nullable|string|max:255',
            'porta'      => 'nullable|integer|min:1|max:65535',
            'camera'     => 'nullable|string|max:255',
            'local_nome' => 'required|string|max:255',
            'lat'        => 'nullable|numeric|between:-90,90',
            'lng'        => 'nullable|numeric|between:-180,180',
            'usuario'    => 'nullable|string|max:255',
            'senha'      => 'nullable|string|max:255',
            'protocolo'  => 'nullable|string|max:50',
            'vms'        => 'nullable|string|max:255',
            'formato'    => 'nullable|string|max:50',
            'hostname'   => 'nullable|string|max:255',
            'link'       => 'nullable|string|max:1000',
            'ativo'      => 'nullable|boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo');

        try {
            $camera->fill($validated);
            $camera->save();
            return redirect("/cameras/$camera->id/edit");
        } catch (\Throwable $th) {
            Log::error('Erro ao atualizar câmera: ' . $th->getMessage(), ['exception' => $th]);
            return back()->withErrors(['error' => 'Erro ao atualizar câmera.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Camera  $camera
     * @return \Illuminate\Http\Response
     */
    public function destroy(Camera $camera)
    {
        //
    }
}
