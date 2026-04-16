@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')
@extends('adminlte::page')

@section('title', 'Editar Câmera')

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
    <h2 class="bg-primary d-flex justify-content-center w-100 mt-5 mb-3">Editar Câmera</h2>
        <form method="POST" action="{{route('cameras.update',$camera->id)}}">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="servidor">Servidor:</label>
                <input type="text" class="form-control" id="servidor" name="servidor" value="{{$camera->servidor  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <select name="cidade" id="cidade">
                    @if ($camera->cidade)
                        <option value="{{$camera->cidade  ?? ''}}">{{$camera->cidade  ?? ''}}</option>
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="ip">IP:</label>
                <input type="text" class="form-control" id="ip" name="ip" value="{{$camera->ip  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="porta">Porta:</label>
                <input type="text" class="form-control" id="porta" name="porta" value="{{$camera->porta  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="camera">Câmera:</label>
                <input type="text" class="form-control" id="camera" name="camera" value="{{$camera->camera  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="local_nome">Endereço:</label>
                <input type="text" class="form-control" id="local_nome" name="local_nome" value="{{$camera->local_nome  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="lat">Latitude:</label>
                <input type="text" class="form-control" id="lat" name="lat" value="{{$camera->lat  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="lng">Longitude:</label>
                <input type="text" class="form-control" id="lng" name="lng" value="{{$camera->lng  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="{{$camera->usuario  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" value="{{$camera->senha  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="protocolo">Protocolo:</label>
                <input type="text" class="form-control" id="protocolo" name="protocolo" value="{{$camera->protocolo  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="vms">VMS:</label>
                <input type="text" class="form-control" id="vms" name="vms" value="{{$camera->vms  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="formato">Formato:</label>
                <input type="text" class="form-control" id="formato" name="formato" value="{{$camera->formato  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="hostname">Hostname:</label>
                <input type="text" class="form-control" id="hostname" name="hostname" value="{{$camera->hostname  ?? ''}}">
            </div>
            <div class="form-group">
                <label for="link">Link:</label>
                <input type="text" class="form-control" id="link" name="link" value="{{$camera->link  ?? ''}}">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="ativo" name="ativo" {{$camera->ativo ? "checked":""}}>
                <label class="form-check-label" for="ativo">Ativo </label>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
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
