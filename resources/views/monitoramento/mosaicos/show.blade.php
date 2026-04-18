@extends('adminlte::page')

@section('title', $mosaico->nome)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h1 class="mb-0">{{ $mosaico->nome }}</h1>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" id="mosaico-btn-fullscreen" title="Usar toda a tela para o mosaico">
                <i class="fas fa-expand" id="mosaico-ico-fs"></i> <span id="mosaico-lbl-fs">Tela cheia</span>
            </button>
            <a href="{{ route('mosaicos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
    @if ($cameras->isEmpty())
        <div class="alert alert-warning">Este mosaico ainda não possui câmeras. Edite-o na listagem para adicionar.</div>
    @else
        <p class="text-muted small mb-2 d-none d-md-block" id="mosaico-layout-hint">
            A grade ajusta colunas e o tamanho dos quadros automaticamente ao redimensionar a janela.
        </p>
        <div id="mosaico-stage" class="mosaico-stage">
            <div class="mosaico-grid"
                 id="mosaico-grid"
                 data-count="{{ $cameras->count() }}"
                 style="--mosaic-cols: 1;">
                @foreach ($cameras as $camera)
                    @php
                        $link = $camera->link;
                        $semLink = !$link || $link === '#' || trim((string) $link) === '';
                    @endphp
                    <div class="mosaico-tile">
                        <div class="mosaico-tile-header">
                            <span title="{{ $camera->local_nome }}">{{ $camera->camera }} — {{ $camera->cidade }}</span>
                        </div>
                        <div class="mosaico-tile-body">
                            @if ($semLink)
                                <div class="mosaico-sem-stream text-muted">Sem link de vídeo</div>
                            @else
                                <img src="{{ $link }}" alt="{{ $camera->camera }}" loading="lazy"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="mosaico-erro text-danger" style="display:none;">Falha ao carregar o stream</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        /* Área útil: preenche o espaço vertical abaixo do cabeçalho da página */
        .mosaico-stage {
            width: 100%;
            min-height: 280px;
        }
        .mosaico-grid {
            display: grid;
            gap: 6px;
            width: 100%;
            box-sizing: border-box;
            /* altura definida por JS para distribuir linhas com 1fr */
            min-height: 240px;
            grid-template-columns: repeat(var(--mosaic-cols, 1), minmax(0, 1fr));
            grid-template-rows: repeat(var(--mosaic-rows, 1), minmax(0, 1fr));
            align-content: stretch;
        }
        .mosaico-tile {
            min-width: 0;
            min-height: 0;
            background: #0a0a0a;
            border: 1px solid #2a2a2a;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .mosaico-tile-header {
            flex: 0 0 auto;
            background: rgba(0, 0, 0, 0.88);
            color: #e8e8e8;
            font-size: 11px;
            padding: 4px 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .mosaico-tile-body {
            flex: 1 1 auto;
            min-height: 0;
            position: relative;
            background: #111;
        }
        .mosaico-tile-body img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .mosaico-sem-stream,
        .mosaico-erro {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        /* Modo tela cheia: sem margens extras do wrapper AdminLTE */
        :fullscreen .mosaico-stage,
        :-webkit-full-screen .mosaico-stage {
            background: #000;
            padding: 6px;
        }
    </style>
@stop

@section('js')
    @if (!$cameras->isEmpty())
    <script>
    (function () {
        var grid = document.getElementById('mosaico-grid');
        var stage = document.getElementById('mosaico-stage');
        if (!grid || !stage) return;

        var n = parseInt(grid.getAttribute('data-count'), 10) || grid.querySelectorAll('.mosaico-tile').length;
        var resizeTimer = null;

        /**
         * Calcula colunas para aproximar células ~16:9 no espaço vw×vh disponível.
         * cols = ceil(sqrt(n * aspectRatio)), aspectRatio = largura/altura útil.
         */
        function optimalColumns(vw, vh, count) {
            if (count <= 0) return 1;
            if (count === 1) return 1;
            var ar = vw / Math.max(1, vh);
            var cols = Math.ceil(Math.sqrt(count * ar));
            cols = Math.max(1, Math.min(count, cols));
            return cols;
        }

        function viewportHeight() {
            if (window.visualViewport && window.visualViewport.height) {
                return window.visualViewport.height;
            }
            return window.innerHeight;
        }

        function applyLayout() {
            var pad = 10;
            var grect = grid.getBoundingClientRect();
            var availW = Math.max(160, grect.width);
            /* Da linha superior do grid até o fim da área visível (inclui tela cheia e barra mobile) */
            var availH = Math.max(200, viewportHeight() - grect.top - pad);

            var cols = optimalColumns(availW, availH, n);
            var rows = Math.ceil(n / cols);

            grid.style.setProperty('--mosaic-cols', String(cols));
            grid.style.setProperty('--mosaic-rows', String(rows));
            grid.style.height = availH + 'px';
        }

        function onResize() {
            if (resizeTimer) clearTimeout(resizeTimer);
            resizeTimer = setTimeout(applyLayout, 80);
        }

        window.addEventListener('resize', onResize);
        window.addEventListener('orientationchange', onResize);
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', onResize);
        }

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(function () { applyLayout(); });
        } else {
            applyLayout();
        }
        setTimeout(applyLayout, 100);

        /* Recalcular quando o painel AdminLTE abre/fecha sidebar */
        document.querySelectorAll('[data-widget="pushmenu"]').forEach(function (el) {
            el.addEventListener('click', function () { setTimeout(applyLayout, 350); });
        });

        /* Tela cheia no elemento #mosaico-stage */
        var btnFs = document.getElementById('mosaico-btn-fullscreen');
        var icoFs = document.getElementById('mosaico-ico-fs');
        var lblFs = document.getElementById('mosaico-lbl-fs');

        function syncFsUi() {
            var fs = document.fullscreenElement === stage;
            if (icoFs) icoFs.className = fs ? 'fas fa-compress' : 'fas fa-expand';
            if (lblFs) lblFs.textContent = fs ? 'Sair da tela cheia' : 'Tela cheia';
        }

        if (btnFs) {
            btnFs.addEventListener('click', function () {
                if (!document.fullscreenElement) {
                    stage.requestFullscreen().catch(function () {});
                } else {
                    document.exitFullscreen();
                }
            });
        }
        document.addEventListener('fullscreenchange', function () {
            syncFsUi();
            applyLayout();
        });
        syncFsUi();
    })();
    </script>
    @endif
@stop
