<?php

namespace App\Http\Controllers;

use App\apis\Api;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Http\Controllers\jwt\JWT;
use App\Log\Log;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{

    public function auth(){

        if( !request()->query('token') ){
            abort(403); // desautoriza acesso em caso de falta de token
        }

        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');

        try{ // Tenta decodificar o token recebido
            $decoded = JWT::decode(request()->query('token'), $chave,['HS256']); // Objeto com os dados recebidos
        }catch (\Exception $e){
            abort(403); // desautoriza acesso em caso de erros de token
        }

        // TODO Teste se o token informado foi gerado pelo sia

        // Testa se o ipUsuario contido no token é o mesmo ip da maquina tentando logar
       /*  if($decoded->ipUsuario != request()->ip()){
            abort(403); // desautoriza acesso em caso de tentativa de acesso por ip diferente do ip para qual o token foi gerado
        } */

	$user = User::where('email',$decoded->email)->first();

        if(!$user) {

            $user = new User();
            $user->setDados($decoded);
            $user->setAttribute('name', $decoded->nome);
            $user->setAttribute('email', $decoded->email);
            $user->setAttribute('password', $decoded->senha);

        }

        Auth::login($user,true);

        $decoded->opms_subordinadas = Cache::remember('opms_subordinadas_' . $decoded->cdopm_meta4_topo, 86400, function() use($decoded){
            $apiQo = new Api('QO');

            $opmNome = $apiQo->get('/api/listarUnidadesMeta4', [
                "fields" => [
                    "opmPMPR" => "opms_subordinadas"
                ],
                "filter" => [
                    "META4" => $decoded->cdopm_meta4_topo,
                ]
            ],
            ['chamadaSistema' => true]);
            return $opmNome[0]['opms_subordinadas'];
        });

        $decoded->notificacao_obrigatoria = 0;

        Session::put('user',$decoded);
        Session::put('autorizacoes',$decoded->autorizacoes);// TODO

        return redirect(route('home'));



    }

    public function recuperarAcesso(){

        $email = request()->input('email');

        try{

            Log::auditoria([
                'sistema' => env('SIA_ID_SOFTWARE'),
               // 'nome' => session()->get('user')->nome ?? null,
               // 'rg' => session()->get('user')->rg ?? null,
              //  'cpf' => session()->get('user')->cpf ?? null,
              //  'login' => session()->get('user')->usuario ?? null,
                'created_time' => now()->timestamp,
                'acao' => "RECUPERAÇÃO DE ACESSO",
                'dados' => array_merge([
                    'email' => $email
                ]),
                'url' => request()->getRequestUri(),
               // 'ip' => Log::getIP(),
                'detalhes' => "Tentativa de recuperação de acesso para o email $email"
            ]);

            $chave = env('SIA_CHAVE_ASSINATURA');
                $id_sw = env('SIA_ID_SOFTWARE');
                $nome_sw = env('SIA_ID_SOFTWARE');
                $ip = Log::getIP();

                // criar token do sw
                try {

                    $issuedAt = time();
                    $expirationTime = $issuedAt + ( 10 * 60 ); // Token válido por 1o minutos

                    $token = JWT::encode([
                        'id' => $id_sw,
                        'nome' => $nome_sw,
                        'ip' => $ip,
                        'iat' => $issuedAt,
                        'exp' => $expirationTime,
                    ], $chave);

                }catch (\Exception $e){
                    ddd($e);
                    return [];
                }

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->retry(3)->timeout(10)->post(env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.env('PATH_RESET_SENHA_SIA'),[
                'email' => $email,
                'token' => $token,
                'sw' => $nome_sw
            ]); // Envia o email do usuario para realizar a recuperação

        }catch (\Exception $e){
            return view('login.login',['recuperar'=>true,'mensagem' => 'Erro ao processar a recuperação de acesso - 22 '.$e->getMessage()]);
        }

        if($retorno->json('status') === 'error'){ //usuario não foi autenticado
            // retornar view com erro
            return view('login.login',['recuperar'=>true,'mensagem' => 'Erro ao processar a recuperação de acesso - 11 '.$retorno->json('msg')]);
        }

        return view('login.login',['recuperar'=>true,'mensagem' => 'Foi enviado um link de recuperação e um código de confirmação para o email '.$email.' para cadastrar uma nova senha de acesso,']);


    }

    public function showFormLogin(){

        if(Auth::check()){ // Verifica se já está logado
            return redirect(route('home')); // redireciona para Home
        }

        try{

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->get('//'.env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.env('PATH_VIEW_LOGIN_SIA')); // Busca view de login no servidor do sia

            // Atualizar arquivo login.blade.php
            //Storage::disk('login')->put('login.blade.php',$retorno); // Atualiza a view de login Local com a view de login recebida do servidor sia

        }catch (\Exception $e) {

        }
        return view('login/login'); // retorna a view armazenada localmente
    }

    public function logout()
    {
        Log::auditoria([
            'sistema' => env('SIA_ID_SOFTWARE'),
            'nome' => session()->get('user')->nome ?? null,
            'rg' => session()->get('user')->rg ?? null,
            'cpf' => session()->get('user')->cpf ?? null,
            'login' => session()->get('user')->usuario ?? null,
            'created_time' => now()->timestamp,
            'acao' => "LOGOUT DO SISTEMA",
            'dados' => array_merge([]),
            'url' => request()->getRequestUri(),
            'ip' => Log::getIP(),
            'detalhes' => "Deslogou do sistema"
        ]);

        Auth::logout();
        return redirect('/login');
    }

    public function login()
    {

         //valida o captcha
         request()->validate([
            'captcha' => 'required|captcha',
            'usuario' => 'required',
            'senha' => 'required',
            'otpId' => 'nullable|string',
            'codigo_otp' => 'nullable|array',
            'codigo_otp.*' => 'nullable|string|size:1|digits:1'
        ],
        [
            'usuario.required' => 'O campo Usuário deve ser preenchido!',
            'senha.required' => 'O campo Senha deve ser preenchido!',
            'captcha.required' => 'Você deve solucionar o captcha!',
            'captcha.captcha' => 'Você errou o captcha! Tente novamente.'
        ]);

        if( !request()->input('usuario') || !request()->input('senha') ){
            return view('login.login',['mensagem' => 'Você deve informar seu usuário e senha para continuar!']); // RETORNA VIEW LOGIN PADRÃO PARA TODOS OS SISTEMAS
        }

        $usuario = request()->input('usuario');
        $senha = request()->input('senha');
        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');

        // criar token do sw consulta efetivo
        try {

            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);

        }catch (\Exception $e){
            // retornar view login com mensagem de erro ao codificar token de sistema para autenticação no SIA
            //return "Erro ".$e->getMessage();
            return view('login.login',['mensagem' => 'Erro ao codificar o token de sistema para autenticação no SIA, tente novamente e informe a DDTQ caso o erro persista! - '.$e->getMessage()]);
        }

//ddd(request()->ip());

        // fazer post para sia function (Request $request, Response $response){
        try{
           
            
            $dados = [
                    'usuario' => $usuario,
                    'senha' => $senha,
                    'sw' => $nome_sw,
                    'token' => $token,
                    'ipUsuario' => request()->ip()
            ];
	


			if( request()->input('codigo_otp') ){

                    $otp = implode('', request()->input('codigo_otp')); // Converte o array de OTP em uma string
                    if(strlen($otp) > 0 && strlen($otp) < 7){
                        $dados['otp'] = $otp; // Adiciona o OTP ao array de dados
                    }

            }

            if( request()->input('resend_otp') ){
                $dados['resend_otp'] = request()->input('resend_otp'); // Adiciona o ID do OTP ao array de dados
            }
			

	$retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->retry(3)->timeout(30)->post('//'.env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.env('PATH_AUTH_SIA'), $dados); // Realiza a autenticação do usuario no sia
	
	
	$res = $retorno->json();

			   //ddd($res);

			   
			   if($res["status"] == 'otp_requerido'){// Bloco de identificação de 2FA OTP
					return view('login.login',[
						//'mensagem' => 'Autenticação de dois fatores requerida, informe o código de autenticação recebido via SMS/Email ou aplicativo autenticador.',
						'usuario' => $usuario,
						'senha' => $senha,
						'otpRequerido' => true,
						'otpData' => $res
					]);
			   }elseif ($res["status"] == 'recadastramento_requerido') {// Bloco de identificação de recadastramento obrigatório
					$chave = env('SIA_CHAVE_ASSINATURA');
					$id_sw = env('SIA_ID_SOFTWARE');
					$nome_sw = env('SIA_ID_SOFTWARE');
					$ip = Str::beforeLast(request()->server('HTTP_HOST'),':');

					// criar token do sw
					try {

						$issuedAt = time();
						$expirationTime = $issuedAt + ( 10 * 60 ); // Token válido por 1o minutos

						$token = JWT::encode([
							'id' => $id_sw,
							'nome' => $nome_sw,
							'ip' => $ip,
							'iat' => $issuedAt,
							'exp' => $expirationTime,
						], $chave);

					}catch (\Exception $e){
						ddd($e);
						return [];
					}

					return redirect( config('sistemas.Sia-Auth.appUrl') . "/recadastramento?token=$token&sw=$nome_sw", 302);
			   }elseif ($res["status"] == 'senha_expirada') {

                    return redirect(
                            config('sistemas.Sia-Auth.appUrl') . '/upke?' . 
                            http_build_query(['token' => $res["token"], 'sw' => $nome_sw,"msg" => $res["msg"]],302)
                        );

                }


        }catch (\Exception $e){
            ddd($e);
            //return "Erro ".$e->getMessage();
            return view('login.login',['mensagem' => 'Erro ao efeturar login no SIA tente novamente e informe a DDTQ caso o erro persista! - '.$e->getMessage()]);
            // exibir mensagem de erro ao fazer login no sia
        }

        if($retorno->json('status') === 'error'){ //usuario não foi autenticado
            Session::put('tentativas',Session::get('tentativas') + 1) ;

            return view('login.login',['mensagem' => $retorno->json('msg')]);
        }

        try{ // Tenta decodificar o token recebido
            $decoded = JWT::decode($retorno->json('token'), $chave,['HS256']); // Objeto com os dados recebidos
        }catch (\Exception $e){
            // retornar view com erro ao realizar o login no sia + error
            //return "Erro ".$e->getMessage();
            return view('login.login',['mensagem' => 'Erro ao decodificar token recebido doSIA, tente novamente e informe a DDTQ caso o erro persista! - '.$e->getMessage()]);
        }

        $user = User::where('email',$decoded->email)->first();

        if(!$user) {

            $user = new User();
            $user->setDados($decoded);
            $user->setAttribute('name', $decoded->nome);
            $user->setAttribute('email', $decoded->email);
            $user->setAttribute('password', $decoded->senha);


        }
        Auth::login($user,true);

        $decoded->opms_subordinadas = Cache::remember('opms_subordinadas_' . $decoded->cdopm_meta4_topo, 86400, function() use($decoded){
            $apiQo = new Api('QO');

            $opmNome = $apiQo->get('/api/listarUnidadesMeta4', [
                "fields" => [
                    "opmPMPR" => "opms_subordinadas"
                ],
                "filter" => [
                    "META4" => $decoded->cdopm_meta4_topo,
                ]
            ],
            ['chamadaSistema' => true]);

            return $opmNome[0]['opms_subordinadas'];
        });

        $decoded->notificacao_obrigatoria = 0;

        Session::put('user',$decoded);
        Session::put('autorizacoes',$decoded->autorizacoes);// TODO

        Log::auditoria([
            'sistema' => env('SIA_ID_SOFTWARE'),
            'nome' => session()->get('user')->nome ?? null,
            'rg' => session()->get('user')->rg ?? null,
            'cpf' => session()->get('user')->cpf ?? null,
            'login' => session()->get('user')->usuario ?? null,
            'created_time' => now()->timestamp,
            'acao' => "LOGOU NO SISTEMA",
            'dados' => array_merge([
                'user_id' => $decoded->id,
                'policial_id' => $decoded->policial_id,
                'policial_id_meta4' => $decoded->policial_id_meta4,
                'opm_m4_id' => $decoded->opm_m4_id,
                'opm' => $decoded->opm_id_meta4_descricao,
                'cdopm_meta4_topo' => $decoded->cdopm_meta4_topo,
                'autorizacoes' => $decoded->autorizacoes
            ]),
            'url' => request()->getRequestUri(),
            'ip' => Log::getIP(),
            'detalhes' => "Acessou o sistema"
        ]);

        return redirect(route('home'));
        //return Auth::check();

        // confiança entre servidores checkar token_id
        //$response = Http::timeout(3)->get('http://10.147.30.106:8081/api/checktoken');

        //\Illuminate\Support\Facades\Auth::login(blkjdfbj);
        //   SIA::login($token)

        //iniciar seção
        //
        //renderizar a home



    }
}
