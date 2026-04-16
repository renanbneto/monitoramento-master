@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')
@extends('adminlte::page')

@section('title', 'Cadastrar Câmera')

@section('content_header')
    {{-- Configurar Breadcrumb em
    app\Http\Controllers\breadcrumbs\BreadCrumbsLocalController.php --}}
{{--     {{ Breadcrumbs::render('nomeDaRotaBreadcrumb') }} --}}
@stop

@section('content')
{{-- Padrão para autorização de permissões na view. --}}
@if ($Autorizacao->can(['Administrador']))
    {{-- Código --}}
@endif

<div class="container">
    <h2 class="bg-primary d-flex justify-content-center w-100 mt-5 mb-3">Cadastrar Câmera</h2>
        <form method="POST" action="{{route('cameras.store')}}">
            @csrf
            <div class="form-group">
                <label for="servidor">Servidor:</label>
                <input type="text" class="form-control" id="servidor" name="servidor">
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <select name="cidade" id="cidade">
                </select>
            </div>
            <div class="form-group">
                <label for="ip">IP:</label>
                <input type="text" class="form-control" id="ip" name="ip">
            </div>
            <div class="form-group">
                <label for="porta">Porta:</label>
                <input type="text" class="form-control" id="porta" name="porta">
            </div>
            <div class="form-group">
                <label for="camera">Câmera:</label>
                <input type="text" class="form-control" id="camera" name="camera">
            </div>
            <div class="form-group">
                <label for="local_nome">Nome do Local:</label>
                <input type="text" class="form-control" id="local_nome" name="local_nome">
            </div>
            <div class="form-group">
                <label for="lat">Latitude:</label>
                <input type="text" class="form-control" id="lat" name="lat" value="{{$lat}}">
            </div>
            <div class="form-group">
                <label for="lng">Longitude:</label>
                <input type="text" class="form-control" id="lng" name="lng" value="{{$lng}}">
            </div>
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" class="form-control" id="usuario" name="usuario">
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha">
            </div>
            <div class="form-group">
                <label for="protocolo">Protocolo:</label>
                <input type="text" class="form-control" id="protocolo" name="protocolo">
            </div>
            <div class="form-group">
                <label for="vms">VMS:</label>
                <input type="text" class="form-control" id="vms" name="vms">
            </div>
            <div class="form-group">
                <label for="formato">Formato:</label>
                <input type="text" class="form-control" id="formato" name="formato">
            </div>
            <div class="form-group">
                <label for="hostname">Hostname:</label>
                <input type="text" class="form-control" id="hostname" name="hostname">
            </div>
            <div class="form-group">
                <label for="link">Link:</label>
                <input type="text" class="form-control" id="link" name="link">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="ativo" name="ativo" checked>
                <label class="form-check-label" for="ativo">Ativo</label>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){


         $("#cidade").select2({
            minimumInputLength: 2,
            ajax: {
                url: '{{route('cidades')}}',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function (term) {
                    return {
                        term: term
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.map(el => {return {id:el.text,text:el.text}})
                    };
                }
            }
        });
    });
</script>
@stop
