@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')
@extends('adminlte::page')

@section('title', 'Nome da Página')

@section('content_header')
    {{-- Configurar Breadcrumb em 
    app\Http\Controllers\breadcrumbs\BreadCrumbsLocalController.php --}}
    {{ Breadcrumbs::render('nomeDaRotaBreadcrumb') }}
@stop

@section('content')
{{-- Padrão para autorização de permissões na view. --}}
@if ($Autorizacao->can(['Administrador']))
    {{-- Código --}}
@endif
@stop

@section('css')

@stop

@section('js')

@stop
