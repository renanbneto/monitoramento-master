<?php

namespace App\apis;

use App\Http\Controllers\jwt\JWT;
use App\Http\Controllers\SiaAPI;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Api { 
        
    public function __construct($apiName)
    {
        $this->apiName = $apiName;
        $this->chave = env('SIA_CHAVE_ASSINATURA');
        $this->baseUrl = config('sistemas.'.$apiName.'.appUrl').':'.config('sistemas.'.$apiName.'.porta');
        $this->tokenApi = "dfdfd";
        $this->tokenSia = null;
    }

    public function get($rota,$dados, $opts = []){

        $options = [
            'timeout' => 10,
            'retry' => 1,
            'verify' => env('SIA_SSL_VERIFY'),
        ];

        foreach($opts as $key=>$value) $options[$key]=$value;

        if(!isset($options['chamadaSistema'])){
            $dados = array_merge($dados,['opms_subordinadas'=>Session::get('user')->opms_subordinadas,'opm_id'=>Session::get('user')->opm_id,'local_id'=>Session::get('user')->local_id,'rg'=>Session::get('user')->rg ,'user_id' => Session::get('user')->id]);
        }else{
            $dados = array_merge($dados,['chamadaSistema' => env('APP_NAME')]);
        }
       
        
        $url = $this->baseUrl.$rota;

        

        if(!$this->registrar()){
            Log::error('Não foi possivel garantir a comunicação com o serviço '.$this->apiName.' API');
            return response('Não foi possivel garantir a comunicação com o serviço '.$this->apiName.' API',500); // Não foi possivel garantia a comunicação
        }
        
        

        try{
            
            Log::info('Requisição GET para '.$this->apiName.' API '.date('H:i:s'));
            
            $retorno = Http::withOptions($options)->get($url,array_merge(['token' => $this->tokenApi],$dados)); 
            //$retorno = Http::get('http://10.147.29.225:8006/api/policial_opm_id?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MzI5MjA2NDcsImV4cCI6MTYzMjkyMjQ0N30.kPBy4ModQOXkXUa3lj-DufMcop1CdpJ0JQ5XhMO35sY&rg=97162506');
            
            Log::info($retorno->json());
            Log::info('Fim da requisição GET para '.$this->apiName.' API '.date('H:i:s'));
            return $retorno->json();

        }catch(Exception $e){
            Log::info($e->getMessage());
            return response([$e->getMessage()],500);
        }        
      
    }

    public function post($rota,$dados, $opts = []){
        
        $options = [
            'timeout' => 10,
            'retry' => 1,
            'verify' => env('SIA_SSL_VERIFY'),
        ];

        foreach($opts as $key=>$value) $options[$key]=$value;

        if(!isset($options['chamadaSistema'])){
            $dados = array_merge($dados,['opms_subordinadas'=>Session::get('user')->opms_subordinadas,'opm_id'=>Session::get('user')->opm_id,'local_id'=>Session::get('user')->local_id,'rg'=>Session::get('user')->rg ,'user_id' => Session::get('user')->id]);
        }else{
            $dados = array_merge($dados,['chamadaSistema' => env('APP_NAME')]);
        }
        
        $url = $this->baseUrl.$rota;

        
        if(!$this->registrar()){
            Log::error('Não foi possivel garantir a comunicação com o serviço '.$this->apiName.' API');
            return response('Não foi possivel garantir a comunicação com o serviço '.$this->apiName.' API',500); // Não foi possivel garantia a comunicação
        }
        
        try{
            
            Log::info('Requisição POST para '.$this->apiName.' API '.date('H:i:s'));
            //$retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->retry($options['retry'])->timeout($options['timeout'])->get($url,array_merge(['token' => $this->tokenApi],$dados)); 

            $retorno = Http::withOptions($options)->post($url,array_merge(['token' => $this->tokenApi],$dados)); 
            //$retorno = Http::get('http://10.147.29.225:8006/api/policial_opm_id?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MzI5MjA2NDcsImV4cCI6MTYzMjkyMjQ0N30.kPBy4ModQOXkXUa3lj-DufMcop1CdpJ0JQ5XhMO35sY&rg=97162506');

            Log::info($retorno->json());
            Log::info('Fim da requisição POST para '.$this->apiName.' API '.date('H:i:s'));
            return $retorno->json();

        }catch(Exception $e){
            Log::info($e->getMessage());
            return response([$e->getMessage()],500);
        }        
      
    }

    public function put($rota,$dados, $opts = []){
        
        $options = [
            'timeout' => 10,
            'retry' => 1,
            'verify' => env('SIA_SSL_VERIFY'),
        ];

        foreach($opts as $key=>$value) $options[$key]=$value;

        if(!isset($options['chamadaSistema'])){
            $dados = array_merge($dados,['opms_subordinadas'=>Session::get('user')->opms_subordinadas ?? '','opm_id'=>Session::get('user')->opm_id,'local_id'=>Session::get('user')->local_id,'rg'=>Session::get('user')->rg ,'user_id' => Session::get('user')->id]);
        }else{
            $dados = array_merge($dados,['chamadaSistema' => env('APP_NAME')]);
        }
        
        $url = $this->baseUrl.$rota;

        
        if(!$this->registrar()){
            Log::error('Não foi possivel garantir a comunicação com o serviço '.$this->apiName.' API');
            return response('Não foi possivel garantir a comunicação com o serviço '.$this->apiName.' API',500); // Não foi possivel garantia a comunicação
        }
        
        try{
            
            Log::info('Requisição PUT para '.$this->apiName.' API '.date('H:i:s'));
            $retorno = Http::withOptions($options)->put($url,array_merge(['token' => $this->tokenApi],$dados)); 
            //$retorno = Http::get('http://10.147.29.225:8006/api/policial_opm_id?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MzI5MjA2NDcsImV4cCI6MTYzMjkyMjQ0N30.kPBy4ModQOXkXUa3lj-DufMcop1CdpJ0JQ5XhMO35sY&rg=97162506');
            Log::info($retorno->json());
            Log::info('Fim da requisição PUT para '.$this->apiName.' API '.date('H:i:s'));
            return $retorno->json();

        }catch(Exception $e){
            Log::info($e->getMessage());
            return response([$e->getMessage()],500);
        }        
      
    }

    public function registrar(){

        if($this->validaToken()){
            return true;
        }else{
            return $this->buscaToken(); 
        }

        Log::info("retornando falso no registrar");

        return false;
    }

    private function buscaToken(){

        
        
        $result = SiaAPI::buscaToken($this->apiName);

        Log::info("buscaToken ".$result['token']);

        if(isset($result["error"])) {return false;}

        if(isset($result["token"])){
            
            try{
    
                
                $decoded = JWT::decode($result["token"], $this->chave,['HS256']); // Objeto com os dados recebidos
                //Log::error('Decodificado token com chave '.$this->chave);
                $this->tokenSia = $result["token"]; // Salva o token recebido do SIA

                $this->tokenApi = $decoded->token; // Extrai o token de acesso ao API
                
                return true;
    
            }catch(Exception $e){
                Log::error('Erro ao validar Token '.$e->getMessage());
                return false;
            }
            return true;
        }
        else
            return false;
    }

    private function validaToken(){

        try{
            
            if($this->tokenSia == null)
                return false;

            $decoded = JWT::decode($this->tokenApi, $this->chave,['HS256']); // Objeto com os dados recebidos
       
            if($this->tokenApi == null) // verifica se o token já foi extraido, senão extrai 
                $this->tokenApi = $decoded->token;
            
            return true;

        }catch(Exception $e){
            Log::error('Erro ao validar Token '.$e->getMessage());
            return false;
        }
    }


}

?>
