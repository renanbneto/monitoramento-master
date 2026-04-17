<?php

namespace App\Http\Controllers;

class AdminLTELocal
{
    // Reescreve as configurações de template no menu local

    // Arquivo para Menu Local
    // Para mais Informações Acesse o Link Abaixo
    // https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration

    public static function menu()
    {
        return [
            //NOME DO SISTEMA LOCAL
            [
                // 'type'       => 'sidebar',
                // 'text'       => 'search',         // Placeholder for the underlying input.
                // 'url'        => 'sidebar/search', // The url used to submit the data ('#' by default).
                // 'method'     => 'post',           // 'get' or 'post' ('get' by default).
                // 'input_name' => 'searchVal',      // Name for the underlying input ('adminlteSearch' by default).
                // 'id'         => 'sidebarSearch',   // ID attribute for the underlying input (optional).
            ],
            [
                'text'        => 'Mapa', // Link para monitoramento de serviços
                'url'         => '/home',
                'icon'        => 'fas fa-map',
                //'can'         =>  ['Administrador']
            ],
            [
                'text'        => 'Câmeras', // Link para monitoramento de serviços
                'url'         => '/cameras/view',
                'icon'        => 'fas fa-video',
                'can'         =>  ['Administrador']
            ],
            [
                'text'        => 'Cadastrar Câmera', // Link para monitoramento de serviços
                'url'         => '/cameras/create',
                'icon'        => 'fas fa-camera',
                'can'         =>  ['Administrador']
            ],
            [
                'text'        => 'Prospecção LPR',
                'url'         => '/prospeccoesLPR',
                'icon'        => 'fas fa-camera',
            ],
            [
                'text'        => 'Grandes Eventos',
                'url'         => '/eventos',
                'icon'        => 'fas fa-calendar-alt',
            ],
        ];
    }

    public static function local() // reescreve configurações de template global
    {
        return [
            //Menus personalizado ficam "acima" ou "abaixo" dos configurados no método AdminLTELocal::menu acima.
            'menu_item_local'  => 'abaixo',
        ];
    }
}
