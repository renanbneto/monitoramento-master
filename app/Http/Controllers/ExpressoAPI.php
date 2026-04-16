<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ExpressoAPI extends Controller
{

    // Retorna lista de mensagens
    public static function LerMensagens(
        $auth,
        $pasta = "INBOX", 
        $busca = "", 
        $msgID = "", 
        $pagina = 1, 
        $resultadosPorPagina = 50){

        try{    
            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Mail/Messages";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(20)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $auth,
                        'folderID' => $pasta,
                        'search' => $busca,
                        'msgID' => $msgID,
                        'resultsPerPage' => $resultadosPorPagina,
                        'page' => $pagina,

                    ]),
                ]
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta;
            }

            return $resposta['result'];
      
        }catch(Exception $e){
            return ['error' => ['code' => 500, 'message' => $e->getMessage()]];
        }

    }
    
    // Busca dados de emails em uma pasta especifica 
    public static function LerPastaDeEmails($auth,$pasta = "Caixa de Entrada"){
        
        try{    
            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Mail/Folders";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(10)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $auth,
                        'folderID' => 'INBOX',
                        'search' => $pasta,
                    ]),
                ]
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta;
            }

            return $resposta['result']['folders'][0];
      
        }catch(Exception $e){
            return ['error' => ['code' => 500, 'message' => $e->getMessage()]];
        }

    }

    // Autenticação pelo Expresso
    public static function Autenticar($usuario,$senha){
        try{
            
            $user = self::SearchLdap($usuario); // Busca se o usuário existe
            
            if( isset($user['result']) && !$user['result'] ){
                Log::error("Usuário {$usuario} não localizado no expresso ".json_encode($user));

                return ['error' => ['code' => 404, 'message' => 'Usuário não localizado no expresso!']];
            }

            $auth = self::Login($usuario,$senha);        

            if( isset($auth['error']) ){
                Log::error('Usuário não logado '.json_encode($auth['error']));
                return $auth;
            }
                       

            self::Logout($auth['result']['auth']);            

            return $user;

        }catch(\Exception $e){
            return ['error' => ['code' => 500, 'message' => 'Erro interno '.$e->getMessage()]];
        }
    }

    public static function AlterarSenhaUsuario($usuario,$senha){

        try{

            $auth = self::Login(env('API_EXPRESSO_ADMIN_USER'),env('API_EXPRESSO_ADMIN_PASS'));

            if( isset($auth['error']) ){
                Log::error('Usuário não logado '.json_encode($auth['error']));
                return 0;
            }

            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Admin/UpdateUser";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(10)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $auth['result']['auth'],
                        'accountLogin' => $usuario,
                        'accountPassword' => $senha,
                        'accountPassword' => $senha,
                    ]),
                ]
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta['error'];
            }

            $auth = self::Logout($auth);

            return 1;    
            
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return 0;//['error' => ['code' => 500 , 'message' => $e->getMessage()]];
        }

    }
    // Busca LDAP
    public static function SearchLdap($usuario = "",$cpf = "",$rg = "",$email =""){
    
        try{

            $auth = self::Login(env('API_EXPRESSO_ADMIN_USER'),env('API_EXPRESSO_ADMIN_PASS'));

            if( isset($auth['error']) ){
                Log::error('Usuário não logado '.json_encode($auth['error']));
                return $auth;
            }

            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Admin/SearchLdap";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(10)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $auth['result']['auth'],
                        'accountSearchUID' => $usuario,
                        //'accountSearchCPF' => $cpf,
                        //'accountSearchRG' => $rg,
                        //'accountSearchMail' => $email,
                    ]),
                ]
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta['error'];
            }

            $auth = self::Logout($auth);

            return $resposta['result'];    
            
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return ['error' => ['code' => 500 , 'message' => $e->getMessage()]];
        }
        
    }


    //Autenticação
    public static function Login($usuario,$senha){

        try{

            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Login";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(15)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'user' => $usuario,
                        'password' => $senha,
                    ]),
                ]
            );

            //return $resposta['result']['auth'];    // retorna token autenticado
            return $resposta->json();
            
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return ['error' => ['code' => 500 , 'message' => $e->getMessage()]];
        }
        
    }

    public static function Logout($chave){
       
        try{

            $baseUrl = "https://api.expresso.pr.gov.br/sesp";
            $path = "/Logout";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(10)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $chave,
                    ]),
                ]
            );


            return $resposta->json();
            
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return ['error' => ['code' => 500 , 'message' => $e->getMessage()]];
        }
    }

    public static function resetarConta($auth,$login,$senha){
        
        try{
            
            $baseUrl = "https://api.expresso.pr.gov.br/sesp";
            $path = "/Admin/UpdateUser";
            
            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(10)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $auth,
                        'accountLogin' => $login,
                        'accountPassword' => $senha,
                        'accountRePassword' => $senha,
                        'accountPasswordExpired' => '1',
                        'accountStatus' => '1'
                    ]),
                    
                    ]
                );
                

               // dd($resposta->json());

                if($resposta["result"] == false){
                    return false;
                }

                return true;
        }catch(\Exception $e){
            Log::error('erro '.json_encode($e->getMessage()));
            return false;
        }

    }

    // Preferencias do Usuário
    public static function AlterarSenha($chave, $senhaAtual, $novaSenha, $repetirNovaSenha){
        
        try{

            $baseUrl = "https://api.expresso.pr.gov.br/sesp";
            $path = "/Preferences/ChangePassword";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(10)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $chave,
                        'currentPassword' => $senhaAtual,
                        'newPassword_1' => $novaSenha,
                        'newPassword_2' => $repetirNovaSenha,
                    ]),
                ]
            );

            return $resposta->json();          
            
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return ['error' => ['code' => 500 , 'message' => $e->getMessage()]];
        }
    }

    // Email

    //Email e notificações
    public static function listarEmails($dados){ // Busca os emails do expresso do usuario cadastrado
    
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

    public static function deletarEmail($dados){

        try{    
            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Mail/DelMessage";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(20)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => Session::get('user')->auth_expresso,
                        'msgID' => $dados['MsgID'],
                        'folderID' => str_replace('Inbox','INBOX',$dados['FolderID'])
                    ])
                ]
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta;
            }

            return $resposta['result'];
      
        }catch(Exception $e){
            return ['error' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    public static function MoverMensagens(
        $auth,
        $msgID = "",
        $folderID = "",
        $toFolderID = ""
    ){
        try{    
            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Mail/MoveMessages";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(20)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $auth,
                        'msgID' => $msgID,
                        'folderID' => $folderID,
                        'toFolderID' => $toFolderID
                    ])
                ]
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta;
            }

            return $resposta['result'];
      
        }catch(Exception $e){
            return ['error' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    public static function EnviarMensagens($auth,Request $request){
   
       
    
        $dados  = [
            'id' => '1',
            'params' => json_encode([
                'auth' => $auth,
                'msgID' => $request->input('msgID'),
                'msgTo' => $request->input('msgTo'),
                'msgCcTo' => $request->input('msgCcTo'),
                'msgBccTo' => $request->input('msgBccTo'),
                'msgReplyTo' => $request->input('msgReplyTo'),
                'msgType' => "html",
                'msgSubject' => $request->input('msgSubject'),
                'msgBody' => $request->input('msgBody'),
                'msgSaveDraft' => $request->input('msgSaveDraft'),
            ])
        ];

        Log::info("Conteudo do email".json_encode($dados));
        Log::info($request->file('file'));

   
        try{    
            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Mail/Send";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(20);#->asForm();
            
            Log::info("Quantidade de arquivos ".json_encode($request->file('files')));

            $count = 0; // Contador de anexos
             // VERIFICA SE É um ENCAMINHAMENTO
            if($request->input('encaminhar')){
                $msg = ExpressoAPI::LerMensagens(
                    Session::get('user')->auth_expresso,
                    $request->input('Folder'),
                    "",
                    $request->input('Msg')
                );

                if($msg['messages']){

                    if($msg['messages'][0]['msgHasAttachments'] > 0){

                        foreach($msg['messages'][0]['msgAttachments'] as $anexo){
                            /* return $anexo; 
                            {"attachmentID":"2","attachmentIndex":"0","attachmentName":"dhl express track error.PNG","attachmentSize":"110780","attachmentEncoding":"base64","attachamentType":"image\/png"}
                            */
                            $base64 = ExpressoAPI::DownloadAnexos(
                                Session::get('user')->auth_expresso,
                                $request->input('Msg'),
                                $request->input('Folder'),
                                $anexo['attachmentID']);

                            $resposta = $resposta->attach("file_".$count, base64_decode($base64),$anexo['attachmentName']);
                            $count++;
                            //return $file;
                        }
                    }
                }
            }

            if($request->hasFile('files')) {
                Log::info("Quantidade de arquivos ".json_encode($request->file('files')));
                
                foreach ($request->file('files') as $file) {
                    
                    $resposta = $resposta->attach("file_".$count, $file->get(),$file->getClientOriginalName());
                    $count++;

                }
            }
            
            if($count == 0){
                $resposta = $resposta->asForm();
            }

            Log::error('Depois do attach'.json_encode($resposta));

            $resposta = $resposta->post(
                $baseUrl.$path,
                $dados
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta;
            }

            return $resposta['result'];
      
        }catch(Exception $e){
            return ['error' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    public static function DownloadAnexos(
        $auth,
        $msgID = "",
        $folderID = "",
        $attachmentID = ""
    ){
        
        try{    
            $baseUrl = "https://api.expresso.pr.gov.br/sesp";

            $path = "/Mail/Attachment";

            $resposta = Http::withOptions(['verify' => env('API_EXPRESSO_SSL_VERIFY')])->timeout(20)->asForm()->post(
                $baseUrl.$path,
                [
                    'id' => '1',
                    'params' => json_encode([
                        'auth' => $auth,
                        'msgID' => $msgID,
                        'folderID' => $folderID,
                        'attachmentID' => $attachmentID
                    ]),
                ]
            );

            if(!empty($resposta['error'])){
                Log::error(json_encode($resposta['error']));
                return $resposta;
            }


            return $resposta;
      
        }catch(Exception $e){
            return ['error' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    public static function moverEmail($dados){
        try{

            $retorno = ExpressoAPI::MoverMensagens(
                Session::get('user')->auth_expresso,
                isset($dados['msgID']) ? $dados['msgID'] : "", 
                isset($dados['folderID']) ? $dados['folderID'] : "",
                isset($dados['toFolderID']) ? $dados['toFolderID'] : "INBOX/Lixeira",
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

    public static function enviarEmail(Request $request){
        try{

            $retorno = ExpressoAPI::EnviarMensagens(Session::get('user')->auth_expresso,$request); 
            
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

    public static function baixarAnexos($dados){
        
        try{

            $retorno = ExpressoAPI::DownloadAnexos(
                Session::get('user')->auth_expresso,
                isset($dados['msgID']) ? $dados['msgID'] : "", 
                isset($dados['folderID']) ? $dados['folderID'] : "",
                isset($dados['attachmentID']) ? $dados['attachmentID'] : "",
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
}