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
    public function atualizaMosaicos()
    {
        return User::find(Auth::user()->id)->update([
            "mosaico" => request()->input('mosaico') ?? '{}'
        ]);
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
        try {
            Camera::create($request->input());
        } catch (\Throwable $th) {
            ddd($th->getMessage());
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
        try {
            $dados = $request->input();


            $dados["ativo"] = isset($dados["ativo"]) ? true : false;

            $camera->fill($dados);
            $camera->save();
            return redirect("/cameras/$camera->id/edit");
        } catch (\Throwable $th) {
            ddd($th->getMessage());
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
