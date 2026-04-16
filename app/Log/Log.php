<?php

namespace App\Log;

use App\apis\Api;
use App\Models\Log as ModelLogs;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class Log {



    public static function auditoria($dados)
    {

        try{

            // Envia o log direto para o banco de dados do sistema de auditoria, caso falhe por algum motivo, envia via API no catch
            ModelLogs::create([
                'sistema' => $dados['sistema'] ?? env('APP_NAME'),
                'nome' => $dados['nome'] ?? session()->get('user')->nome,
                'rg' => $dados['rg'] ?? session()->get('user')->rg,
                'cpf' => $dados['cpf'] ?? session()->get('user')->cpf,
                'login' => $dados['login'] ?? session()->get('user')->usuario,
                'created_time' => $dados['created_time'] ?? now()->timestamp,
                'dados' => json_encode($dados['dados'] ?? request()->all()),
                'url' => $dados['url'] ?? request()->getRequestUri(),
                'ip' => $dados['ip'] ?? Log::getIP(),
                'acao' => $dados['acao'] ?? "",
                'detalhes' => $dados['detalhes'] ?? ""
            ]);

        }catch(Exception $e){

            $api = new Api('Auditoria');
            $api->post('/api/insertRecord',$dados); // Envia o log via API para o sistema de auditoria caso o salvamento direto no banco falhe

        }

    }

    /**
     * Grava um log de erro
     *
     * @param string $mensagem
     * @param array $conteudo
     * @return void
     */
    public static function error( string $mensagem = null, array $conteudo = [] ){

        $log = "";

        if(Session::has('user')){
            $log = "Nome ".Session::get('user')->nome." - Usuário ".Session::get('user')->usuario." - RG ".Session::get('user')->rg;
        }

        $log .= " IP ".self::getIP();

        $log .= " URL ".Request::getRequestUri();

        if($mensagem){
            $log .= " Mensagem ".$mensagem;
        }

        if($conteudo){
            $conteudo["linhaLog"] = $log;
            $log .= " >>> ".json_encode($conteudo);
        }

        \Illuminate\Support\Facades\Log::setTimezone(new \DateTimeZone('America/Sao_Paulo'))->error($log);
        \Illuminate\Support\Facades\Log::channel('all')->setTimezone(new \DateTimeZone('America/Sao_Paulo'))->error($log);

    }

    /**
     * Grava um log de Atenção
     *
     * @param string $mensagem
     * @param array $conteudo
     * @return void
     */
    public static function warning( string $mensagem = null, array $conteudo = [] ){
        $log = "";

        if(Session::has('user')){
            $log = "Nome ".Session::get('user')->nome." - Usuário ".Session::get('user')->usuario." - RG ".Session::get('user')->rg;
        }

        $log .= " IP ".self::getIP();

        $log .= " URL ".Request::getRequestUri();

        if($mensagem){
            $log .= " Mensagem ".$mensagem;
        }

        if($conteudo){
            $conteudo["linhaLog"] = $log;
            $log .= " >>> ".json_encode($conteudo);
        }

        \Illuminate\Support\Facades\Log::channel(env('SIA_ID_SOFTWARE'))->setTimezone(new \DateTimeZone('America/Sao_Paulo'))->warning($log);
        \Illuminate\Support\Facades\Log::channel('all')->setTimezone(new \DateTimeZone('America/Sao_Paulo'))->warning($log);
    }

    /**
     * Grava um log de informações
     *
     * @param string $mensagem
     * @param array $conteudo
     * @return void
     */
    public static function info( string $mensagem = null, array $conteudo = [] ){

        $log = "";

        if(Session::has('user')){
            $log = "Nome ".Session::get('user')->nome." - Usuário ".Session::get('user')->usuario." - RG ".Session::get('user')->rg;
        }

        $log .= " IP ".self::getIP();

        $log .= " URL ".Request::getRequestUri();

        if($mensagem){
            $log .= " Mensagem ".$mensagem;
        }

        if($conteudo){
            $conteudo["linhaLog"] = $log;
            $log .= " >>> ".json_encode($conteudo);
        }

        \Illuminate\Support\Facades\Log::channel(env('SIA_ID_SOFTWARE'))->setTimezone(new \DateTimeZone('America/Sao_Paulo'))->info($log);
        \Illuminate\Support\Facades\Log::channel('all')->setTimezone(new \DateTimeZone('America/Sao_Paulo'))->info($log);
    }

    /**
     * Grava no log geral
     *
     * @param string $mensagem
     * @param array $conteudo
     * @return void
     */
    public static function all( string $mensagem = null, array $conteudo = [] ){

        $log = "";

        if(Session::has('user')){
            $log = "Nome ".Session::get('user')->nome." - Usuário ".Session::get('user')->usuario." - RG ".Session::get('user')->rg;
        }

        $log .= " IP ".self::getIP();

        $log .= " URL ".Request::getRequestUri();

        if($mensagem){
            $log .= " Mensagem ".$mensagem;
        }

        if($conteudo){
            $conteudo["linhaLog"] = $log;
            $log .= " >>> ".json_encode($conteudo);
        }

        \Illuminate\Support\Facades\Log::channel('all')->setTimezone(new \DateTimeZone('America/Sao_Paulo'))->info($log);
    }



    public static function getIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        // Sometimes the `HTTP_CLIENT_IP` can be used by proxy servers
        $ip = @$_SERVER['HTTP_CLIENT_IP'];
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
           return $ip;
        }

        // Sometimes the `HTTP_X_FORWARDED_FOR` can contain more than IPs
        $forward_ips = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        if ($forward_ips) {
            $all_ips = explode(',', $forward_ips);

            foreach ($all_ips as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'];
    }

}
