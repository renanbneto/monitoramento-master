<?php

namespace App\Http\Controllers;

use App\Http\Controllers\apis\QoApi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\jwt\JWT;
use App\Http\Controllers\xmpp\Prebind;
use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SiaAPI extends Controller
{

    public static function buscaToken($apiName){

        

        if( 'Sia-Auth' == env('SIA_ID_SOFTWARE') ){

            
                try {
                    # code...
                    // Neste caso não devemos requisitar ao sia-auth pois é ele quem está chamando
                    $sistemaRequisicao = \App\Models\Sistemas::where('nome','=',env('SIA_ID_SOFTWARE'))->get();
                    
                    if(count($sistemaRequisicao) <= 0 ){
                        
                        Log::warning('Tentativa de geração de senha de banco '.request()->ip().' usando sistema não cadastrado');
                        return response()->json([],404);
                        
                    }

                    
                    $sistema = \App\Models\Sistemas::where('nome','=',$apiName)->get();
                    
                    if(count($sistema) <= 0 ){
                        
                        Log::warning('Tentativa de geração de token por '.request()->ip().' para sistema não cadastrado');
                        return response()->json([],404);
                        
                    }
                    
                    
                    
                    $tokenSistema = JWT::gerarTokenApi([],$sistema[0],request()->ip(),60*30);
                    
                    $token = ['token' => JWT::gerarTokenApi(['token' => $tokenSistema],$sistemaRequisicao[0],request()->ip(),60*31)];

                    Log::info("Entrou no sia auth id software e buscou o registro da API");
                    
                    return $token;

                } catch (\Throwable $e) {
                    return ['error' => "Erro ao criar toker de acesso a apis para SIA-AUTH"];
                }
        }
            
        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
        
        //return ['token' =>'enfrjnfjnfkjr'];
        // criar token do sw
        try {
            
            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);
            
        }catch (\Exception $e){
            //ddd($e);  // TODO remover após ajustar o retorno com erro
            return ['error' => "Erro ao criar token de acesso ao SIA Auth"];
        }

        try{
                
            $path = "api/sistemas/apisToken/".$apiName;
                            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $query = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()]);
            
            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->get($url,$query); // Realiza a autenticação do usuario no sia
            
            //Log::error("Erro ".$retorno);

            return ['token' => $retorno];
            
        }catch (\Exception $e){
           //ddd($e);  // TODO remover após ajustar o retorno com erro
           return ['error' => "Erro ao buscar token de Api no SIA Auth"];
            //return response([$e->getMessage()],500);
        }
        // receber token de resposta sia
        ///decodificar o token extraindo o token de acesso a api

        return ['token' => "Erro ao buscar token"];
    }

    public function listarUnidadesQo(){
        $api = new QoApi("QO"); // Instancia a API do QO
        return $api->get('/api/listarUnidades',request()->all());
    }

    public static function atualizaDbPass($sw){
        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
        
        // criar token do sw
        try {
            
            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);
            
        }catch (\Exception $e){
            //ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }

        try{
                
            $path = "api/sistemas/dbpass/".$sw;
                            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $query = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()]);
            
            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->get($url,$query); // Realiza a autenticação do usuario no sia
            
            return $retorno->json();
            
        }catch (\Exception $e){
           // ddd($e);  // TODO remover após ajustar o retorno com erro
            return response([$e->getMessage()],500);
        }

    }

    //Email e notificações
    public static function BuscarEmails($dados){ // Busca os emails do expresso do usuario cadastrado
    
        try{

            $retorno = ExpressoAPI::LerMensagens(
                Session::get('user')->auth_expresso,
                isset($dados['pasta']) ? $dados['pasta'] : "", 
                isset($dados['busca']) ? $dados['busca'] : "", 
                isset($dados['msgID']) ? $dados['msgID'] : "", 
                isset($dados['pagina']) ? $dados['pagina'] : "", 
                isset($dados['resultadosPorPagina']) ? $dados['resultadosPorPagina'] : "", 
            ); 
            
            // verificar status
            if(isset($retorno['error'])){
                return response($retorno,500);
            }
            // Atualizar Sessão

            return $retorno;

        }catch(Exception $e){
            return response([$e->getMessage()],500);
        }

    }
    //Email e notificações

    public static function AlterarEmailAlternativo($dados) {

        try{
            
            $path = "api/usuario/alterarEmailAlternativo";
           
            $codigosDeAcessoSia = self::gerarCodigosDeAcesso();
            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $payload = array_merge($codigosDeAcessoSia,$dados,['id' => Session::get('user')->id]);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 

            return $retorno->json();

        }catch(Exception $e){

        }

    }

    public static function XmppAuth(){ // Retorna o prebind para client xmpp

        $params = [
            "user" => Session::get('user')->rg,
            "password" => (new Encrypter(str_pad(env('SIA_CHAVE_ASSINATURA'),32,'0',STR_PAD_RIGHT), "AES-256-CBC"))->decryptString(Session::get('user')->chave_chat),
            "tld" => env('XMPP_TLD','im.pm.pr.gov.br'),
            "boshUrl" => env('XMPP_BOSH_URL','https://im.pm.pr.gov.br:7443/http-bind/') 
        ];
        $xmpp = new Prebind($params);
        return json_encode($xmpp->connect()); //will return JID, SID, RID as JSON

    }

    public static function alterarSenha($dados){
        

        if(!$auth = Session::get('user')->auth_expresso){
            return ['error' => ['code' => 403, 'message' => 'Não está logado no expresso, faça login novamente para receber uma chave do expresso']];
        }

        $path = "api/usuario/alterarSenha";
        $codigosDeAcessoSia = self::gerarCodigosDeAcesso();

        try{
            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $payload = array_merge($codigosDeAcessoSia,$dados,['auth' => $auth, 'id' => Session::get('user')->id]);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 

            return $retorno->json();

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return ['error' => ['code' => 500 , 'message' => $e->getMessage()]];
        }
    }

    public static function adicionarPermissaoUsuario($id,$permissao){
        
        $path = 'api/permissoes/usuario/'.$id.'/adicionar';
        $codigosDeAcessoSia = self::gerarCodigosDeAcesso();

        try{
            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $payload = array_merge($codigosDeAcessoSia,$permissao);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 

            return $retorno->json();

        }catch (\Exception $e){
            return response([$e->getMessage()],500);
        }
    }
    public static function removerPermissaoUsuario($id,$permissao){
        
        $path = 'api/permissoes/usuario/'.$id.'/remover';
        $codigosDeAcessoSia = self::gerarCodigosDeAcesso();

        try{
            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $payload = array_merge($codigosDeAcessoSia,$permissao);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 

            return $retorno->json();

        }catch (\Exception $e){
            return response([$e->getMessage()],500);
        }
    }

    public static function listarPermissoesUsuario($filters,$id = null){
        
        
        try{

            $codigosDeAcessoSia = self::gerarCodigosDeAcesso();

           
            $path = "api/permissoes/usuario/".$id;
           
            
            $filtro = [];

            if($filters){

                foreach ($filters as $key => $value) {
                    $filtro["filter[$key]"] = $value;
                }
                
            }

            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $query = array_merge($codigosDeAcessoSia,$filtro,$filters);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10
            )->get($url,$query); // Realiza a autenticação do usuario no sia

            return $retorno->json();

        }catch (\Exception $e){
            //ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }

    }

    public static function desabilitarUsuario($dados){

        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');

        // criar token do sw
        try {

            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);

        }catch (\Exception $e){
            ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }


        // fazer post para sia function (Request $request, Response $response){
        try{

            $path = "api/usuario/desabilitar";
            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 

            return $retorno->json();

        }catch (\Exception $e){
            ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }

    }

    public static function habilitarUsuario($dados){
        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');

        // criar token do sw
        try {

            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);

        }catch (\Exception $e){
            ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }


        // fazer post para sia function (Request $request, Response $response){
        try{

            $path = "api/usuario/habilitar";
            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 

            return $retorno->json();

        }catch (\Exception $e){
            ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }
    }

    public static function listarUsuarios($filters = null){
        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');

        // criar token do sw
        try {

            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);

        }catch (\Exception $e){
            ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }


        // fazer post para sia function (Request $request, Response $response){
        try{

            $path = "api/usuarios";
            
            $filtro = [];

            if($filters){
                $filtro["filter[status]"] = 'true';
                foreach ($filters as $key => $value) {
                    if($key == 'inativos'){
                        $filtro["filter[status]"] = 'false';
                    }else{
                        $filtro["filter[$key]"] = $value;
                    }
                }
                
            }

            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $query = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$filtro);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->get($url,$query); // Realiza a autenticação do usuario no sia

            return $retorno->json();

        }catch (\Exception $e){
            //ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function listarSistemas($filters = null){
        
        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
        
        // criar token do sw
        try {
            
            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);
            
        }catch (\Exception $e){
            //ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }
        
        
        // fazer post para sia function (Request $request, Response $response){
            try{
                
                $path = "api/sistemas";
                
                $filtro = [];
                
                if($filters){
                    
                    foreach ($filters as $key => $value) {
                        $filtro["filter[$key]"] = $value;
                    }
                    
                }
                
                $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                
                $query = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$filtro);
                
                $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->get($url,$query); // Realiza a autenticação do usuario no sia
                
                return $retorno->json();
                
            }catch (\Exception $e){
                ddd($e);  // TODO remover após ajustar o retorno com erro
                return [];
            }
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public static function inserirSistema($dados)
        {
            $chave = env('SIA_CHAVE_ASSINATURA');
            $id_sw = env('SIA_ID_SOFTWARE');
            $nome_sw = env('SIA_ID_SOFTWARE');
            $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
            
            // criar token do sw
            try {
                
                $token = JWT::encode([
                    'id' => $id_sw,
                    'nome' => $nome_sw,
                    'ip' => $ip
                ], $chave);
                
            }catch (\Exception $e){
                ddd($e);  // TODO remover após ajustar o retorno com erro
                return [];
            }
            
            
            // fazer post para sia function (Request $request, Response $response){
                try{
                    
                    $path = "api/sistemas";
                    
                    $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                    
                    $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);
                    
                    $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 
                    
                    return $retorno->json();
                    
                }catch (\Exception $e){
                    ddd($e);  // TODO remover após ajustar o retorno com erro
                    return [];
                }
            }
            
            /**
             * Display the specified resource.
             *
             * @param  int  $id
             * @return \Illuminate\Http\Response
             */
            public function listarSistema($id)
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
            public static function atualizarSistema($dados)
            {
                $chave = env('SIA_CHAVE_ASSINATURA');
                $id_sw = env('SIA_ID_SOFTWARE');
                $nome_sw = env('SIA_ID_SOFTWARE');
                $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
                
                // criar token do sw
                try {
                    
                    $token = JWT::encode([
                        'id' => $id_sw,
                        'nome' => $nome_sw,
                        'ip' => $ip
                    ], $chave);
                    
                }catch (\Exception $e){
                    ddd($e);  // TODO remover após ajustar o retorno com erro
                    return [];
                }
                
                
                // fazer post para sia function (Request $request, Response $response){
        try{
            
            $path = "api/sistemas/".request()->input('id');
            
            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
            
            $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);
            
            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->put($url,$payload); 
            
            return $retorno->json();
            
        }catch (\Exception $e){
            ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function deletarSistema($dados){
        $chave = env('SIA_CHAVE_ASSINATURA');
        $id_sw = env('SIA_ID_SOFTWARE');
        $nome_sw = env('SIA_ID_SOFTWARE');
        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
        
        // criar token do sw
        try {
            
            $token = JWT::encode([
                'id' => $id_sw,
                'nome' => $nome_sw,
                'ip' => $ip
            ], $chave);
            
        }catch (\Exception $e){
            ddd($e);  // TODO remover após ajustar o retorno com erro
            return [];
        }
        
        
        // fazer post para sia function (Request $request, Response $response){
            try{
                
                $path = "api/sistemas/".request()->input('id');
                
                $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                
                $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);
                
                $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->delete($url,$payload); 
                
                return $retorno->json();
                
            }catch (\Exception $e){
                ddd($e);  // TODO remover após ajustar o retorno com erro
                return [];
            }
    }
        
        
    public static function atualizarAssinatura($dados){
            
            $chave = env('SIA_CHAVE_ASSINATURA');
            $id_sw = env('SIA_ID_SOFTWARE');
            $nome_sw = env('SIA_ID_SOFTWARE');
            $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
            
            // criar token do sw
            try {
                
                $token = JWT::encode([
                    'id' => $id_sw,
                    'nome' => $nome_sw,
                    'ip' => $ip
                ], $chave);
                
            }catch (\Exception $e){
                ddd($e);  // TODO remover após ajustar o retorno com erro
                return [];
            }
            
            
            // fazer post para sia function (Request $request, Response $response){
                try{
                    
                    $path = "api/sistemas/assinatura/".request()->input('id');
                    
                    $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                    
                    $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);
                    
                    $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->put($url,$payload); 
                    
                    return $retorno->json();
                    
                }catch (\Exception $e){
                    ddd($e);  // TODO remover após ajustar o retorno com erro
                    return [];
                }
            }
            
            
            
            /// PERMISSOES DOS SISTEMAS
            public static function listarPermissoes($sistemas_id,$filters = null){
                $chave = env('SIA_CHAVE_ASSINATURA');
                $id_sw = env('SIA_ID_SOFTWARE');
                $nome_sw = env('SIA_ID_SOFTWARE');
                $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
                
                // criar token do sw
                try {
                    
                    $token = JWT::encode([
                        'id' => $id_sw,
                        'nome' => $nome_sw,
                        'ip' => $ip
                    ], $chave);
                    
                }catch (\Exception $e){
                    ddd($e);  // TODO remover após ajustar o retorno com erro
                    return [];
                }
                
                
                // fazer post para sia function (Request $request, Response $response){
                    try{
                        
                        $path = "api/permissoes/".$sistemas_id;
                        
                        $filtro = [];
                        
                        if($filters){
                            
                            foreach ($filters as $key => $value) {
                                $filtro["filter[$key]"] = $value;
                            }
                            
                        }
                        
                        $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                        
                        $query = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$filtro);
                        
                        $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->get($url,$query); // Realiza a autenticação do usuario no sia
                        
                        return $retorno->json();
                        
                    }catch (\Exception $e){
                        ddd($e);  // TODO remover após ajustar o retorno com erro
                        return [];
                    }
                }
                
                public static function inserirPermissao($dados){
                    $chave = env('SIA_CHAVE_ASSINATURA');
                    $id_sw = env('SIA_ID_SOFTWARE');
                    $nome_sw = env('SIA_ID_SOFTWARE');
                    $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
                    
                    // criar token do sw
                    try {
                        
                        $token = JWT::encode([
                            'id' => $id_sw,
                            'nome' => $nome_sw,
                            'ip' => $ip
                        ], $chave);
                        
                    }catch (\Exception $e){
                        ddd($e);  // TODO remover após ajustar o retorno com erro
                        return [];
                    }
                    
                    
                    // fazer post para sia function (Request $request, Response $response){
                        try{
                            
                            $path = "api/permissoes";
                            
                            $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                            
                            $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);
                            
                            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->post($url,$payload); 
                            
                            return $retorno->json();
                            
                        }catch (\Exception $e){
                            ddd($e);  // TODO remover após ajustar o retorno com erro
                            return [];
                        }
                    }
                    
                    public static function atualizarPermissao($dados){
                        $chave = env('SIA_CHAVE_ASSINATURA');
                        $id_sw = env('SIA_ID_SOFTWARE');
                        $nome_sw = env('SIA_ID_SOFTWARE');
                        $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
                        
                        // criar token do sw
                        try {
                            
                            $token = JWT::encode([
                                'id' => $id_sw,
                                'nome' => $nome_sw,
                                'ip' => $ip
                            ], $chave);
                            
                        }catch (\Exception $e){
                            ddd($e);  // TODO remover após ajustar o retorno com erro
                            return [];
                        }
                        
                        
                        // fazer post para sia function (Request $request, Response $response){
                            try{
                                
                                $path = "api/permissoes/".request()->input('id');
                                
                                $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                                
                                $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);
                                
                                $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->put($url,$payload); 
                                
                                return $retorno->json();
                                
                            }catch (\Exception $e){
                                ddd($e);  // TODO remover após ajustar o retorno com erro
                                return [];
                            }
                        }
                        
                        public static function deletarPermissao($dados){
                            
                            $chave = env('SIA_CHAVE_ASSINATURA');
                            $id_sw = env('SIA_ID_SOFTWARE');
                            $nome_sw = env('SIA_ID_SOFTWARE');
                            $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
                            
                            // criar token do sw
                            try {
                                
                                $token = JWT::encode([
                                    'id' => $id_sw,
                                    'nome' => $nome_sw,
                                    'ip' => $ip
                                ], $chave);
                                
                            }catch (\Exception $e){
                                ddd($e);  // TODO remover após ajustar o retorno com erro
                                return [];
                            }
                            
                            
                            // fazer post para sia function (Request $request, Response $response){
                                try{
                                    
                                    $path = "api/permissoes/".request()->input('id');
                                    
                                    $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                                    
                                    $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],$dados);
                                    
                                    $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->timeout(10)->delete($url,$payload); 
                                    
                                    return $retorno->json();
                                    
                                }catch (\Exception $e){
                                    ddd($e);  // TODO remover após ajustar o retorno com erro
                                    return [];
                                }
                            }
                            /// PERMISSOES DOS SISTEMAS
                            
                            
                            // Atualizar Template
                            public static function atualizarTemplate(){
                                $chave = env('SIA_CHAVE_ASSINATURA');
                                $id_sw = env('SIA_ID_SOFTWARE');
                                $nome_sw = env('SIA_ID_SOFTWARE');
                                $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
                                
                                // criar token do sw
                                try {
                                    
                                    $token = JWT::encode([
                                        'id' => $id_sw,
                                        'nome' => $nome_sw,
                                        'ip' => $ip
                                    ], $chave);
                                    
                                }catch (\Exception $e){
                                    ddd($e);  // TODO remover após ajustar o retorno com erro
                                    return [];
                                }
                                
                                
                                // fazer post para sia function (Request $request, Response $response){
                                    try{
                                        
                                        $path = "api/atualizarTemplate";
                                        
                                        $url = env('DOMINIO_SIA').':'.env('PORTA_SIA').'/'.$path;
                                        
                                        $payload = array_merge(['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()],[]);
                                        
                                        $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->post($url,$payload); 
                                        
                                        return array_column(config('sistemas'),"appUrl");
                                        
                                    }catch (\Exception $e){
                                        // ddd($e);  // TODO remover após ajustar o retorno com erro
                                        
                                        return response($e->getMessage(),500);
                                    }        
                                }
                                //
                                
                                //TESTE brasil
    private static function gerarCodigosDeAcesso(){
                                    
                                    $chave = env('SIA_CHAVE_ASSINATURA');
                                    $id_sw = env('SIA_ID_SOFTWARE');
                                    $nome_sw = env('SIA_ID_SOFTWARE');
                                    $ip = Str::beforeLast(request()->server('HTTP_HOST'),':');
                            
                                    // criar token do sw
                                    try {
                            
                                        $token = JWT::encode([
                                            'id' => $id_sw,
                                            'nome' => $nome_sw,
                                            'ip' => $ip
                                        ], $chave);
                            
                                    }catch (\Exception $e){
                                        //ddd($e);  // TODO remover após ajustar o retorno com erro
                                        return [];
                                    }
                                    return ['sw' => $nome_sw,'token' => $token,'ipUsuario' => request()->ip()];
    }
}
                            