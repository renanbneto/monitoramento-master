<?php

use App\Facedes\SIA;
use App\Http\Controllers\EmailExpressoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Http\Controllers\LoginController;
use \Illuminate\Support\Facades\Session;
use App\Http\Controllers\jwt\JWT;
use App\Http\Controllers\NotificacoesController;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SiaAPI;
use App\Http\Middleware\Auth2;
use App\Models\Notas;
use Illuminate\Support\Facades\DB;

Route::fallback(function(){
    return response()->redirectTo(route('home'))->with(['alertType' => 'error','mensagem' => "Você foi redirecionado para home, pois houve erro de acesso ou falha do sistema!"]);
});

Route::get('monitoramentos', function(){
    return view('sistemas.monitoramento');
});

Route::get('monitoramento', function(){

    try{
        $pdo = DB::connection()->getPdo();

        if($pdo instanceof PDO){
            $res = DB::select('select 1');
            return response(['Ok'],200)
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Methods', "PUT, POST, DELETE, GET, OPTIONS")
            ->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type"); 
        }else{
            return response(['Banco de dados não disponivel'],500)
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Methods', "PUT, POST, DELETE, GET, OPTIONS")
            ->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type");   
        }
        
    }catch(Exception $e){
        return response([$e->getMessage()],500);
    }

})->name('monitoramento');

Route::get('auth',[LoginController::class, 'auth'])->name('auth');
Route::get('login',[LoginController::class, 'showFormLogin'])->name('login');
Route::post('login',[LoginController::class, 'login']);
Route::post('resendOtp/{id}',[LoginController::class, 'resendOtp'])->name('resend.otp');
Route::post('recuperarAcesso',[LoginController::class, 'recuperarAcesso'])->name('recuperarAcesso');

// rota para reload do captcha
Route::get('reload-captcha', function(){
    return response()->json(['captcha'=> captcha_img()]);
})->name('reload-captcha');
// rota para reload do captcha

Route::group(['middleware' => ['auth','auth2']],function (){

    Route::get('auditoria', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->middleware('autorizacao:Administrador;Auditoria')->name('auditoria');

    Route::get('buscarNotificacoes', [NotificacoesController::class,'buscarNotificacoes'])->name('buscarNotificacoes');
    Route::get('exibirNotificacoes', [NotificacoesController::class,'exibirNotificacoes'])->name('exibirNotificacoes');

    Route::post('atualizaSessionNotificacoesObrigatorias', function(){
        try {
            return session()->get('user')->notificacao_obrigatoria = intval(request()->input('notificacaoObrigatoria'),10);
        } catch (\Throwable $th) {
            return response([$th->getMessage()],500);
        }

    } )->name('atualizaSessionNotificacoesObrigatorias');


    Route::get('atualizarTemplate', function(){
        return SiaAPI::atualizarTemplate();
    })->name('atualizarTemplate');

    Route::get('sistemasNaoIntegrados',function(){ // Retorna json com sistemas não integrados
        return view('sistemas.naoIntegrados');
    })->name('sistemasNI');
    
    Route::get('/api/comunicados', function(){
    return [
        'id' => 1,
        'titulo' => 'teste',
        'descricao' => 'jwhgkjhrkge'
    ];
    })->name('comunicados');


    Route::get('/emails', [EmailExpressoController::class,'index']);
    Route::get('/buscarEmails', [EmailExpressoController::class,'buscarEmails'])->name('listarEmails');

    Route::get('/xmppPrebind',function (){
        return SiaAPI::XmppAuth();
    })->name('xmppPrebind');

    Route::get('/chat', function(){
        return view('chat.index');
    })->name('chat');

    Route::get('/', function () {
        return view('home.index');
    })->name('home');

    Route::get('/sistemas', function () {
        return view('sistemas.index');
    })->name('sistemas');
   
    //TODO condicção se está em desenv
    Route::get('/sessao', function () {
        return Session::all();
    })->name('sessao');

    Route::get('/administracao', function () {
        return view('administracao.index');
    })->name('administracao');

    Route::get('/boletins', function () {
        return view('boletins.index');
    })->name('boletins');

   //Route::resource('usuarios', UsuariosController::class);
    
    Route::any('logout',[LoginController::class, 'logout'])->name('logout');
    
    Route::resource('perfil', PerfilController::class);
    
    Route::any('atualizarEmailAlternativo', [PerfilController::class,'atualizarEmailAlternativo'])->name('atualizarEmailAlternativo');

    Route::post('atualizarSenhaPerfil', [PerfilController::class,'atualizarSenhaPerfil'])->name('atualizarSenhaPerfil');
    
    Route::get('/userSistemas', function(Request $request, Response $response )
    {
        try{

            $chave = env('SIA_CHAVE_ASSINATURA');
            $sw = env('SIA_ID_SOFTWARE');
            $ipSistema = Str::beforeLast(request()->server('HTTP_HOST'),':');
            $token = JWT::encode([
                'id' => $sw,
                'nome' => $sw,
                'ipSistema' => $ipSistema,
                'ipusuario' => request()->ip()
            ], $chave);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->retry(3)->timeout(3)->get(env('DOMINIO_SIA').':'.env('PORTA_SIA').'/api/sistemas/users/'.Session::get('user')->id,[
                'token' => $token,
                'ipUsuario' => request()->ip(),
                'sw' => $sw,
                'user' => Session::get('user')
            ]);

        }catch (\Exception $e){
            return $e;
        }

        $result = [];

        foreach ($retorno->json() as $s){
            $sistema = config('sistemas.'.$s['id']);

            if($sistema) {
                $sistema['token'] = $s['token'];

                array_push($result, $sistema);
            }
        }

        // Monta array com as configurações dos sistemas que o usuario possui

        return response()->json($result)->withCookie('teste','teste',0,null,'*');

    })->name('userSistemas');

});



Route::get('comunicados',function(){

    $mes = [

        "Janeiro" => "Jan",
        "Fevereiro" => "Feb",
        "Março" => "Mar",
        "Abril" => "Apr",
        "Maio" => "May",
        "Junho" => "Jun",
        "Julho" => "Jul",
        "Agosto" => "Aug",
        "Setembro" => "Sep",
        "Outubro" => "Oct",
        "Novembro" => "Nov",
        "Dezembro" => "Dec"  
    ];

    $dados = [];

    $content = Http::get('http://10.47.1.20/?s=&cat=4');
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($content);
    $xp = new DOMXPath($dom);
    $els = $xp->query("//div[contains(@class,'type-post')]");
    //$els = $xp->query("//div[contains(@class,'post-main')]");

   //return [$els[0]->childNodes[0]->attributes[0]];
   // return;
    $extract = function($el) use ( &$extract ){
        if(count($el->childNodes) > 0){
            $values = "";
            foreach ($el->childNodes as $child) {
                $values = $values.$extract($child);
            }
            return $values;
        }
        else{
            return $el->nodeValue;
        }
        
    };
    
    if(!is_null($els)){
        foreach ($els as $item) {

            $links = array();

            foreach($item->getElementsByTagName('a') as $link) {
                $links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
            }
        
            if(count($links) > 0){
                
                $string = str_replace(array("\n", "\r","\t"), '\n', $item->nodeValue);
                $arr = array_filter(explode('\n',$string));


                // Fazer o scrap da nota por meio do link externo
                $notaAtualcontent = Http::get(str_replace('intranet.pmpr.parana','10.47.1.20',$links[0]['url']));
                $notaAtualdom = new DOMDocument();
                libxml_use_internal_errors(true);
                $notaAtualdom->loadHTML($notaAtualcontent);
                $notaAtualxp = new DOMXPath($notaAtualdom);
                $notaAtualels = $notaAtualxp->query("//div[contains(@class,'post-container')]");

                $as = $notaAtualels[0]->getElementsByTagName('a');
                $imgs = $notaAtualels[0]->getElementsByTagName('img');
                
                foreach ($imgs as $img) {
                    $path = str_replace('http://intranet.pmpr.parana/','',$img->getAttribute('src'));
                    $img->setAttribute('src','/servicos/Notas/proxyIntranet/'.base64_encode($path));
                    //array_push($dados,[$img->getAttribute('src')]);
                }

                foreach ($as as $a) {
                    $path = str_replace('http://intranet.pmpr.parana/','',$a->getAttribute('href'));
                    $a->setAttribute('href','/servicos/Notas/proxyIntranet/'.base64_encode($path));
                    //array_push($dados,[$a->getAttribute('href')]);
                }
//continue ;
                //return [count($notaAtualels[0]->getElementsByTagName('a'))];

                $titulo = $notaAtualels[0]->getElementsByTagName('h1')[0]->ownerDocument->saveHTML($notaAtualels[0]->getElementsByTagName('h1')[0]);
                $info = $notaAtualels[0]->getElementsByTagName('div')[0]->ownerDocument->saveHTML($notaAtualels[0]->getElementsByTagName('div')[0]);
                $conteudo = $notaAtualels[0]->getElementsByTagName('div')[1]->ownerDocument->saveHTML($notaAtualels[0]->getElementsByTagName('div')[1]);


   // $titulo = str_replace('http://intranet.pmpr.parana/wp-content','/servicos/Notas/proxyIntranet/'$titulo)

   //array_push($dados,['a'=>$titulo,'b' => $info,'c' => $conteudo]);
   //continue;

//                array_push($dados,[$notaAtualTitulo[0]->ownerDocument->saveXML($notaAtualTitulo[0])]);
                //array_push($dados,[$notaAtualels[0]->ownerDocument->saveXML($notaAtualels[0])]);
                
                //continue;
                // contruir um proxy para anexos udando todos os links e sources para o server atual
//return [date('Y-m-d H:m:i',strtotime($arr[19]." ".$mes[substr($arr[27], 0, strlen($arr[27])-4)].", ".substr($arr[27], strlen($arr[27])-4, strlen($arr[27]))))];
                array_push($dados,[
                    'id_externo' => explode('=',$links[0]['url'])[1],
                    'link_externo' => str_replace('intranet.pmpr.parana','10.47.1.20',$links[0]['url']),
                    'data' => date('d/m/Y',strtotime($arr[19]." ".$mes[substr($arr[27], 0, strlen($arr[27])-4)].", ".substr($arr[27], strlen($arr[27])-4, strlen($arr[27])))),
                    'icon' => 'fas fa-file-alt',
                    'dia' => $arr[19],
                    'mes' => substr($arr[27], 0, strlen($arr[27])-4),
                    'ano' => substr($arr[27], strlen($arr[27])-4, strlen($arr[27])),
                    'titulo' => strtoupper($arr[83]),
                    //'conteudo' => strtoupper($arr[105])
                    'conteudo' => $conteudo
                ]);

                Notas::updateOrInsert([
                    'id_externo' => explode('=',$links[0]['url'])[1]
                ],[
                    'id_externo' => explode('=',$links[0]['url'])[1],
                    'link_externo' => $links[0]['url'],
                    'numero' => explode('=',$links[0]['url'])[1],
                    'ano' => substr($arr[27], strlen($arr[27])-4, strlen($arr[27])),
                    'titulo' => $titulo,
                    'local' => explode('-',$titulo)[0],
                    'conteudo' => base64_encode($conteudo),
                    'data_publicacao' => date('Y-m-d H:m:i',strtotime($arr[19]." ".$mes[substr($arr[27], 0, strlen($arr[27])-4)].", ".substr($arr[27], strlen($arr[27])-4, strlen($arr[27])))),
                    'publicado' => true,
                    'status' => 'Publicado'
                ]);
            }




            continue;
          
           
        }
    }
    
   return response()->json($dados);
})->name('listarComunicados');
