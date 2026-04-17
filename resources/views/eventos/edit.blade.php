@extends('adminlte::page')

@section('title', 'Editar Evento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Evento</h1>
        <a href="{{ route('eventos.show', $evento) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> Ver Evento
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('eventos.update', $evento) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Nome do Evento <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control"
                               value="{{ old('nome', $evento->nome) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <div class="custom-control custom-switch mt-2">
                            <input type="checkbox" class="custom-control-input" id="ativo" name="ativo"
                                   {{ old('ativo', $evento->ativo) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="ativo">Ativo</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $evento->descricao) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Local</label>
                        <input type="text" name="local_nome" class="form-control"
                               value="{{ old('local_nome', $evento->local_nome) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="lat" class="form-control"
                               value="{{ old('lat', $evento->lat) }}" placeholder="-25.4284">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="lng" class="form-control"
                               value="{{ old('lng', $evento->lng) }}" placeholder="-49.2733">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Data/Hora de Início</label>
                        <input type="datetime-local" name="data_inicio" class="form-control"
                               value="{{ old('data_inicio', $evento->data_inicio?->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Data/Hora de Término</label>
                        <input type="datetime-local" name="data_fim" class="form-control"
                               value="{{ old('data_fim', $evento->data_fim?->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Câmeras do Evento</label>
                <select name="cameras[]" class="form-control select2-cameras" multiple>
                    @foreach ($cameras as $camera)
                        <option value="{{ $camera->id }}"
                            {{ in_array($camera->id, old('cameras', $camerasSelecionadas)) ? 'selected' : '' }}>
                            {{ $camera->cidade }} — {{ $camera->camera }}{{ $camera->local_nome ? ' (' . $camera->local_nome . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-end" style="gap:8px;">
                <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-cameras').select2({ placeholder: 'Selecione as câmeras...', allowClear: true, width: '100%' });
});
</script>
@stop
