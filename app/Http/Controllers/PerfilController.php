<?php

namespace App\Http\Controllers;

use App\Log\Log;
use Illuminate\Support\Facades\Session;

// use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    
    /**
     * Atualiza o Email Alternativo para recuperação de Senha.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizarEmailAlternativo(Request $request)
    {
        //TODO validar email
        try {

            $retorno = SiaAPI::AlterarEmailAlternativo(request()->input());

            if (isset($retorno['status']) && $retorno['status'] == 'success') {
                Session::get('user')->emailAlternativo = request()->input('emailAlternativo');
            }
            
            $notificacao = array(
                'frmAtuallizaEmailAlternativoMessage' => $retorno['msg'],
                'alert-type' => 'success'
            );

            Log::auditoria([
                'sistema' => env('SIA_ID_SOFTWARE'),
                'nome' => session()->get('user')->nome ?? null,
                'rg' => session()->get('user')->rg ?? null,
                'cpf' => session()->get('user')->cpf ?? null,
                'login' => session()->get('user')->usuario ?? null,
                'created_time' => now()->timestamp,
                'acao' => "ATUALIZOU EMAIL ALTERNATIVO",
                'dados' => array_merge(request()->all(),$notificacao),
                'url' => request()->getRequestUri(),
                'ip' => Log::getIP(),
                'detalhes' => "Cadastrou um novo email alternativo na tela de perfil"
            ]);

            return redirect()->back()->with($notificacao);
            
        } catch (\Exception $e) {
            $notificacao = array(
                'frmAtuallizaEmailAlternativoMessage' => $e->getMessage(),
                'alert-type' => 'danger'
            );
            return redirect()->back()->with($notificacao);
        }
    }
    
    /**
     * Atualiza a Senha do Perfil de Usuário
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function atualizarSenhaPerfil(Request $request)
    {

        $validated = $request->validate([
            'senhaAtual'  => 'required',
            'novaSenha'   => 'required',
            'reNovaSenha' => 'required',
        ]);

        try {

            $retorno = SiaAPI::alterarSenha(request()->input());
            
            //Verifica se retonou erro da API do SIA
            if (isset($retorno['error'])) {
                $notificacao = array(
                    'frmAlterarSenhaMessage' => $retorno['error']['message'],
                    'alert-type' => 'danger'
                );
                //Caso não tenha erro e tenha sucesso    
            }elseif(isset($retorno['result']) && $retorno['result'] ){
                $notificacao = array(
                    'frmAlterarSenhaMessage' => 'Senha Alterada com Sucesso',
                    'alert-type' => 'success'
                );
            }else{
                //Possível erro desconhecido
                $notificacao = array(
                    'frmAlterarSenhaMessage' => 'Senha não alterada. Tente novamente!',
                    'alert-type' => 'warning'
                );
            }

            Log::auditoria([
                'sistema' => env('SIA_ID_SOFTWARE'),
                'nome' => session()->get('user')->nome ?? null,
                'rg' => session()->get('user')->rg ?? null,
                'cpf' => session()->get('user')->cpf ?? null,
                'login' => session()->get('user')->usuario ?? null,
                'created_time' => now()->timestamp,
                'acao' => "ALTEROU SUA SENHA",
                'dados' => array_merge(request()->except(['senhaAtual','novaSenha','reNovaSenha']),$notificacao),
                'url' => request()->getRequestUri(),
                'ip' => Log::getIP(),
                'detalhes' => "Alterou sua senha na tela de perfil"
            ]);

            return redirect()->back()->with($notificacao);
            
        } catch (\Exception $e) {

            $notificacao = array(
                'frmAlterarSenhaMessage' => $e->getMessage(),
                'alert-type' => 'danger'
            );
            return redirect()->back()->with($notificacao);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('perfil.index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
}
