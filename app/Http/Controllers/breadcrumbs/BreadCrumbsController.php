<?php

//Arquivo de Configuração Global, atualizado Pelo SIA.

// Arquivo para BreadCrumbs Global
// Para mais Informações Acesse o Link Abaixo
// https://github.com/davejamesmiller/laravel-breadcrumbs

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push(env('APP_NAME'), route('home'));
});

// Administração
Breadcrumbs::for('administracao', function ($trail) {
    $trail->parent('home');
    $trail->push('Administração', route('administracao'));
});

// Sistemas
Breadcrumbs::for('sistemas', function ($trail) {
    $trail->parent('home');
    $trail->push('Sistemas', route('sistemas'));
});

// Boletins
Breadcrumbs::for('boletins', function ($trail) {
    $trail->parent('home');
    $trail->push('Boletins', route('boletins'));
});

// Perfil
Breadcrumbs::for('perfil', function ($trail) {
    $trail->parent('home');
    $trail->push('Perfil');
});

// Email
Breadcrumbs::for('emails', function ($trail) {
    $trail->parent('home');
    $trail->push('Emails');
});

// Email
Breadcrumbs::for('cameras', function ($trail) {
    $trail->parent('home');
    $trail->push('cameras');
});



