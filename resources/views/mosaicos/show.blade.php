@extends('adminlte::page')

@section('title', $mosaico->nome)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-th-large mr-2"></i>{{ $mosaico->nome }}
            <small class="text-muted" style="font-size:14px;">{{ $mosaico->cameras->count() }} câmera(s)</small>
        </h1>
        <div>
            <a href="{{ route('mosaicos.edit', $mosaico) }}" class="btn btn-sm btn-outline-secondary mr-1">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('mosaicos.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')

@if($mosaico->cameras->isEmpty())
    <div class="callout callout-warning">
        <h5>Nenhuma câmera neste mosaico.</h5>
        <p><a href="{{ route('mosaicos.edit', $mosaico) }}">Clique aqui</a> para adicionar câmeras.</p>
    </div>
@else

    {{-- Controles do visualizador --}}
    <div class="d-flex align-items-center mb-2" style="gap:8px; flex-wrap:wrap;">
        <div>
            <label class="mb-0 mr-1 text-sm">Colunas:</label>
            <select id="sel-colunas" class="form-control form-control-sm d-inline-block" style="width:70px;">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3" selected>3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div>
            <label class="mb-0 mr-1 text-sm">Altura:</label>
            <select id="sel-altura" class="form-control form-control-sm d-inline-block" style="width:90px;">
                <option value="160">Pequena</option>
                <option value="220" selected>Média</option>
                <option value="300">Grande</option>
                <option value="420">Extra</option>
            </select>
        </div>
        <button id="btn-atualizar" class="btn btn-sm btn-outline-info">
            <i class="fas fa-sync-alt"></i> Atualizar imagens
        </button>
        <button id="btn-tela-cheia" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-expand"></i> Tela cheia
        </button>
        <span class="ml-auto text-muted" style="font-size:12px;" id="ultima-atualizacao"></span>
    </div>

    {{-- Grid de câmeras --}}
    <div id="grid-mosaico" class="row no-gutters" style="background:#111;">
        @foreach($mosaico->cameras as $camera)
        <div class="camera-cell col-md-4" style="padding:2px;">
            <div style="position:relative; background:#000;">
                <div style="position:absolute;top:0;left:0;right:0;z-index:2;
                            background:rgba(0,0,0,.6);color:#fff;font-size:11px;padding:2px 6px;">
                    {{ $camera->local_nome }}
                    @if($camera->status === 'online')
                        <span class="badge badge-success ml-1" style="font-size:9px;">●</span>
                    @elseif($camera->status === 'offline')
                        <span class="badge badge-danger ml-1" style="font-size:9px;">●</span>
                    @endif
                </div>
                <img src="{{ $camera->link }}"
                     data-src="{{ $camera->link }}"
                     alt="{{ $camera->local_nome }}"
                     class="camera-img w-100"
                     style="height:220px; object-fit:cover; display:block;"
                     onerror="this.src='{{ asset('images/camoff.png') }}'">
            </div>
        </div>
        @endforeach
    </div>

@endif
@stop

@section('css')
<style>
    /* Remove espaços do AdminLTE nesta página */
    .content-wrapper { min-height: 0 !important; padding-bottom: 0 !important; }
    .content-wrapper > .content { padding: 0.5rem !important; }
    .content-wrapper > .content > .container-fluid { padding: 0 !important; }

    .camera-cell img { transition: opacity .2s; }
    .camera-cell img.recarregando { opacity: .4; }
</style>
@stop

@section('js')
<script>
    var alturaAtual = 220;

    function aplicarLayout() {
        var colunas  = parseInt(document.getElementById('sel-colunas').value);
        var altura   = parseInt(document.getElementById('sel-altura').value);
        alturaAtual  = altura;
        var colClass = 'col-' + Math.floor(12 / colunas);

        document.querySelectorAll('.camera-cell').forEach(function (cell) {
            cell.className = 'camera-cell ' + colClass;
            cell.style.padding = '2px';
            cell.querySelector('.camera-img').style.height = altura + 'px';
        });
    }

    function atualizarImagens() {
        var ts = '?t=' + Date.now();
        document.querySelectorAll('.camera-img').forEach(function (img) {
            img.classList.add('recarregando');
            var nova    = new Image();
            nova.onload  = function () { img.src = nova.src; img.classList.remove('recarregando'); };
            nova.onerror = function () { img.src = '{{ asset("images/camoff.png") }}'; img.classList.remove('recarregando'); };
            nova.src = img.dataset.src + ts;
        });
        document.getElementById('ultima-atualizacao').textContent =
            'Atualizado: ' + new Date().toLocaleTimeString('pt-BR');
    }

    document.getElementById('sel-colunas').addEventListener('change', aplicarLayout);
    document.getElementById('sel-altura').addEventListener('change', aplicarLayout);
    document.getElementById('btn-atualizar').addEventListener('click', atualizarImagens);

    document.getElementById('btn-tela-cheia').addEventListener('click', function () {
        var el = document.getElementById('grid-mosaico');
        if (el.requestFullscreen) el.requestFullscreen();
        else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
    });

    // Auto-refresh a cada 30 segundos
    setInterval(atualizarImagens, 30000);
    document.getElementById('ultima-atualizacao').textContent =
        'Atualizado: ' + new Date().toLocaleTimeString('pt-BR');
</script>
@stop
