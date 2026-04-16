<?php

namespace App\Http\Controllers;

use App\Models\Notificacoes;
use App\Models\NotificacoesDistribuidas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificacoesController extends Controller
{
    public function buscarNotificacoes()
    {
        
        if(!request()->input('token')){
        
            Log::warning('Tentativa de acesso a api por IP '.request()->ip().' sem token de acesso');
            return response()->json([],404); // Retorna nada caso não receba o token do sistema gerador da solicitação 
    
        }
        
        $chave = env('SIA_CHAVE_ASSINATURA');
        
        try{ // Tenta decodificar o token recebido
            $decoded = JWT::decode(request()->input('token'), $chave,['HS256']); // Objeto com os dados recebidos
        }catch (\Exception $e){
            return response()->json([],404); // Retorna nada caso token do sistema esteja inválido 
        }

        try {
            $notificacoes = NotificacoesDistribuidas::join('distribuicao','distribuicao_id','distribuicao.id')
                ->join('notificacoes', 'notificacoes_id','notificacoes.id')
                ->select([
                    'notificacoes_distribuidas.user_id',
                    'notificacoes_distribuidas.id',
                    'notificacoes.data',
                    'notificacoes.titulo',
                    'notificacoes.conteudo',
                    'notificacoes.tipo',
                    'notificacoes.urgencia',
                    'notificacoes.prazo',
                ])
                ->where('notificacoes_distribuidas.user_id','=', request()->input('user_id'))
                ->get();

                $avisos = array_filter($notificacoes->toArray(), function($notificacao) {
                    return $notificacao['tipo'] == 'Aviso';
                });

                $escalas = array_filter($notificacoes->toArray(), function($notificacao) {
                    return $notificacao['tipo'] == 'Escalas';
                });

                $determinacao = array_filter($notificacoes->toArray(), function($notificacao) {
                    return $notificacao['tipo'] == 'Determinação';
                });

                $apresentacao = array_filter($notificacoes->toArray(), function($notificacao) {
                    return $notificacao['tipo'] == 'Apresentação';
                });

                $ordem = array_filter($notificacoes->toArray(), function($notificacao) {
                    return $notificacao['tipo'] == 'Ordem';
                });
                
                $informacao = array_filter($notificacoes->toArray(), function($notificacao) {
                    return $notificacao['tipo'] == 'Informação';
                });

                $notifications = [];

                if($avisos != null && count($avisos) > 0 ){
                    array_push($notifications,[
                        'icon' => 'fas fa-fw fa-bell text-success',
                        'text' => count($avisos) . ' Avisos',
                        'time' => rand(0, 10) . ' minutes',
                        'action' => 'javascript:exibirNotificacoes('.json_encode($avisos).')'
                    ]);
                }
                
                if($escalas != null && count($escalas) > 0 ){
                    array_push($notifications,[
                        'icon' => 'fas fa-fw fa-building text-info',
                        'text' => count($escalas) . ' Escalas',
                        'time' => rand(0, 10) . ' minutes',
                        'action' => 'javascript:exibirNotificacoes('.json_encode($escalas).')'
                    ]);
                }
                
                if($determinacao != null && count($determinacao) > 0 ){
                    array_push($notifications,[
                        'icon' => 'fas fa-fw fa-gavel text-danger',
                        'text' => count($determinacao) . ' Determinações',
                        'time' => rand(0, 10) . ' minutes',
                        'action' => 'javascript:exibirNotificacoes('.json_encode($determinacao).')'
                    ]);
                }
            
                if($informacao != null && count($informacao) > 0 ){
                    array_push($notifications,[
                        'icon' => 'fas fa-fw fa-info-circle',
                        'text' => count($informacao) . ' Informações',
                        'time' => rand(0, 10) . ' minutes',
                        'action' => 'javascript:exibirNotificacoes('.json_encode($informacao).')'
                    ]);
                }

                if($ordem != null && count($ordem) > 0 ){
                    array_push($notifications,[
                        'icon' => 'fas fa-fw fa-fist-raised',
                        'text' => count($ordem) . ' Ordens',
                        'time' => rand(0, 10) . ' minutes',
                        'action' => 'javascript:exibirNotificacoes('.json_encode($ordem).')'
                    ]);
                }
                
                if($apresentacao != null && count($apresentacao) > 0 ){
                    array_push($notifications,[
                        'icon' => 'fas fa-fw fa-file-alt',
                        'text' => count($apresentacao) . ' Apresentações',
                        'time' => rand(0, 10) . ' minutes',
                        'action' => 'javascript:exibirNotificacoes('.json_encode($apresentacao).')'
                    ]);
                }
                // Now, we create the notification dropdown main content.
            
                $dropdownHtml = '';
            
                foreach ($notifications as $key => $not) {
                    $icon = "<i class='mr-2 {$not['icon']}'></i>";
            
                    $time = "<span class='float-right text-muted text-sm'>
                               {$not['time']}
                             </span>";
            
                    $dropdownHtml .= "<a href='{$not['action']}' class='dropdown-item'>
                                        {$icon}{$not['text']}{$time}
                                      </a>";
            
                    if ($key < count($notifications) - 1) {
                        $dropdownHtml .= "<div class='dropdown-divider'></div>";
                    }
                }
            
                // Return the new notification data.
            
                return [
                    'label'       => count($notificacoes),
                    'label_color' => 'danger',
                    'icon_color'  => 'dark',
                    'dropdown'    => $dropdownHtml,
                    'notificacoes' => $notificacoes
                ];


        } catch (\Throwable $e) {
            Log::error("ERRO Aqui! ".$e->getTraceAsString());
            return response(['message' => $e->getMessage(),'error' => $e->getTraceAsString()],500);
        }
        
    }
    

    public function exibirNotificacoes()
    {
        $notificacaoObrigatoria = session()->get('user')->notificacao_obrigatoria;

        return view('components.minhas-notificacoes', compact('notificacaoObrigatoria'));

    }
}
