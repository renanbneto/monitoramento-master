<?php

// Arquivo para BreadCrumbs Local
// Para mais Informações Acesse o Link Abaixo
// https://github.com/davejamesmiller/laravel-breadcrumbs

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Usuários
Breadcrumbs::for('usuarios', function ($trail) {
    $trail->parent('home');
    $trail->push('Usuários',route('usuarios.index'));
});

// Permissões do Usuário
Breadcrumbs::for('permissoesUsuario', function ($trail) {
    $trail->parent('usuarios');
    $trail->push('Permissões');
});

// Softwares
Breadcrumbs::for('softwares', function ($trail) {
    $trail->parent('home');
    $trail->push('Softwares',route('integracoes.index'));
});

// Permissões de Softwares
Breadcrumbs::for('permissoesSoftwares', function ($trail) {
    $trail->parent('softwares');
    $trail->push('Permissões');
});

// Controle
Breadcrumbs::for('exemplo', function ($trail) {
    $trail->parent('home');
    $trail->push('exemplo',route('exemplo.index'));
});