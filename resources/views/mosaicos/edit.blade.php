@extends('adminlte::page')

@section('title', 'Editar Mosaico')

@section('content_header')
    <h1><i class="fas fa-edit mr-2"></i>Editar: {{ $mosaico->nome }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST" action="{{ route('mosaicos.update', $mosaico) }}">
            @csrf @method('PUT')
            <div class="row">

                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header"><h3 class="card-title">Dados do Mosaico</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nome <span class="text-danger">*</span></label>
                                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                       value="{{ old('nome', $mosaico->nome) }}" required>
                                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $mosaico->descricao) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Buscar câmera</label>
                                <input type="text" id="busca-camera" class="form-control"
                                       placeholder="Digite para filtrar...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-secondary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Câmeras disponíveis</h3>
                            <span class="badge badge-info" id="contador-selecionadas">0 selecionada(s)</span>
                        </div>
                        <div class="card-body p-0" style="max-height:480px; overflow-y:auto;">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-dark sticky-top">
                                    <tr>
                                        <th width="40"></th>
                                        <th>Local</th>
                                        <th>Cidade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="tabela-cameras">
                                @foreach($cameras as $camera)
                                <tr class="camera-row {{ in_array($camera->id, $selecionadas) ? 'selecionada' : '' }}"
                                    data-nome="{{ strtolower($camera->local_nome) }} {{ strtolower($camera->cidade) }}">
                                    <td>
                                        <input type="checkbox" name="cameras[]"
                                               value="{{ $camera->id }}"
                                               class="camera-check"
                                               id="cam-{{ $camera->id }}"
                                               {{ in_array($camera->id, $selecionadas) ? 'checked' : '' }}>
                                    </td>
                                    <td><label for="cam-{{ $camera->id }}" class="mb-0 cursor-pointer">{{ $camera->local_nome }}</label></td>
                                    <td><small class="text-muted">{{ $camera->cidade }}</small></td>
                                    <td>
                                        @if($camera->status === 'online')
                                            <span class="badge badge-success">Online</span>
                                        @elseif($camera->status === 'offline')
                                            <span class="badge badge-danger">Offline</span>
                                        @else
                                            <span class="badge badge-secondary">?</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">A ordem de seleção define a posição no mosaico.</small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ route('mosaicos.show', $mosaico) }}" class="btn btn-default mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Salvar Alterações
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
.cursor-pointer { cursor: pointer; }
.camera-row.selecionada { background-color: #1a3a5c !important; }
</style>
@stop

@section('js')
<script>
document.getElementById('busca-camera').addEventListener('input', function () {
    var termo = this.value.toLowerCase();
    document.querySelectorAll('.camera-row').forEach(function (row) {
        row.style.display = row.dataset.nome.includes(termo) ? '' : 'none';
    });
});

function atualizarContador() {
    var total = document.querySelectorAll('.camera-check:checked').length;
    document.getElementById('contador-selecionadas').textContent = total + ' selecionada(s)';
}

document.querySelectorAll('.camera-check').forEach(function (cb) {
    cb.addEventListener('change', function () {
        this.closest('tr').classList.toggle('selecionada', this.checked);
        atualizarContador();
    });
});

atualizarContador();
</script>
@stop
