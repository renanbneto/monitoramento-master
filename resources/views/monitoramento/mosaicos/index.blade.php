@extends('adminlte::page')

@section('title', 'Mosaicos de câmeras')

@section('content_header')
@stop

@section('content')
    @if (session('status'))
        <div class="ml-alert alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="mosaic-lab">
        <header class="ml-hero">
            <div class="ml-hero-text">
                <p class="ml-kicker"><span class="ml-dot"></span> Monitoramento</p>
                <h1 class="ml-title">Mosaicos</h1>
                <p class="ml-sub">Monte grades com câmeras favoritas, defina a ordem e visualize em tela cheia.</p>
            </div>
            <button type="button" class="ml-btn ml-btn-primary" data-toggle="modal" data-target="#modalNovoMosaico" id="btn-open-novo">
                <i class="fas fa-plus"></i> Novo mosaico
            </button>
        </header>

        @if ($mosaicos->isEmpty())
            <div class="ml-empty">
                <div class="ml-empty-icon"><i class="fas fa-th-large"></i></div>
                <p>Nenhum mosaico ainda.</p>
                <button type="button" class="ml-btn ml-btn-ghost" data-toggle="modal" data-target="#modalNovoMosaico">Criar o primeiro</button>
            </div>
        @else
            <div class="ml-grid">
                @foreach ($mosaicos as $m)
                    <article class="ml-card" data-mosaic-id="{{ $m->id }}">
                        <div class="ml-card-top">
                            <span class="ml-card-badge">{{ is_array($m->camera_ids) ? count($m->camera_ids) : 0 }} câm.</span>
                            <div class="ml-card-actions">
                                <a href="{{ route('mosaicos.show', $m) }}" class="ml-icon-btn" title="Abrir grade"><i class="fas fa-play"></i></a>
                                <button type="button" class="ml-icon-btn btn-edit-mosaic"
                                    data-id="{{ $m->id }}"
                                    data-nome="{{ $m->nome }}"
                                    data-camera-ids="{{ e(json_encode($m->camera_ids ?? [])) }}"
                                    title="Editar"><i class="fas fa-sliders-h"></i></button>
                                <form action="{{ route('mosaicos.destroy', $m) }}" method="post" class="d-inline" onsubmit="return confirm('Remover este mosaico?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-icon-btn ml-icon-danger" title="Excluir"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                        <h2 class="ml-card-name">{{ $m->nome }}</h2>
                        <p class="ml-card-meta">Atualizado {{ $m->updated_at->diffForHumans() }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Modal criar --}}
    <div class="modal fade ml-modal" id="modalNovoMosaico" tabindex="-1" role="dialog" aria-labelledby="modalNovoMosaicoLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <form action="{{ route('mosaicos.store') }}" method="post" id="formNovoMosaico" autocomplete="off">
                @csrf
                <div class="modal-content ml-modal-content">
                    <div class="modal-header ml-modal-head">
                        <div>
                            <p class="ml-modal-kicker">Novo mosaico</p>
                            <h5 class="modal-title" id="modalNovoMosaicoLabel">Configurar grade</h5>
                        </div>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body ml-modal-body">
                        <div class="ml-stepbar">
                            <span class="ml-step ml-step-on"><b>1</b> Nome</span>
                            <span class="ml-step-arrow">→</span>
                            <span class="ml-step"><b>2</b> Câmeras & ordem</span>
                        </div>
                        <div class="form-group ml-field">
                            <label for="novo_nome">Nome do mosaico</label>
                            <input type="text" class="form-control ml-input" id="novo_nome" name="nome" required maxlength="160" placeholder="Ex.: Operação centro — turno dia">
                        </div>
                        <div class="ml-panels">
                            <section class="ml-panel ml-panel-pool">
                                <div class="ml-panel-head ml-panel-head-stack">
                                    <span class="ml-panel-title">Catálogo</span>
                                </div>
                                <div class="ml-filters">
                                    <label class="ml-filter-label" for="novo_city">Cidade</label>
                                    <select id="novo_city" class="ml-select-city" title="Reduz a lista antes de buscar pelo nome ou endereço">
                                        <option value="">Todas as cidades</option>
                                    </select>
                                    <label class="ml-filter-label" for="novo_search">Busca</label>
                                    <input type="search" class="ml-search ml-search-wide" id="novo_search" placeholder="Nome da câmera, rua, bairro ou #123 — várias palavras reduzem o resultado" autocomplete="off">
                                </div>
                                <p class="ml-pool-stats" id="novo_pool_stats" aria-live="polite"></p>
                                <div class="ml-pool" id="novo_pool"></div>
                                <button type="button" class="ml-load-more" id="novo_load_more" style="display:none;">Carregar mais câmeras</button>
                            </section>
                            <section class="ml-panel ml-panel-rail">
                                <div class="ml-panel-head">
                                    <span>Grade do mosaico</span>
                                    <span class="ml-counter" id="novo_count">0</span>
                                </div>
                                <p class="ml-hint"><i class="fas fa-grip-vertical"></i> Arraste para definir a ordem das telas na visualização.</p>
                                <ul class="ml-sortable" id="novo_sort"></ul>
                                <p class="ml-pool-empty" id="novo_placeholder">Nenhuma câmera — adicione pelo catálogo ao lado.</p>
                            </section>
                        </div>
                    </div>
                    <div class="modal-footer ml-modal-foot">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="ml-btn ml-btn-primary"><i class="fas fa-check"></i> Salvar mosaico</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal editar --}}
    <div class="modal fade ml-modal" id="modalEditarMosaico" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <form method="post" id="formEditarMosaico" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-content ml-modal-content">
                    <div class="modal-header ml-modal-head">
                        <div>
                            <p class="ml-modal-kicker">Editar mosaico</p>
                            <h5 class="modal-title">Ajustar grade</h5>
                        </div>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body ml-modal-body">
                        <div class="ml-stepbar">
                            <span class="ml-step ml-step-on"><b>1</b> Nome</span>
                            <span class="ml-step-arrow">→</span>
                            <span class="ml-step"><b>2</b> Câmeras & ordem</span>
                        </div>
                        <div class="form-group ml-field">
                            <label for="edit_nome">Nome do mosaico</label>
                            <input type="text" class="form-control ml-input" id="edit_nome" name="nome" required maxlength="160">
                        </div>
                        <div class="ml-panels">
                            <section class="ml-panel ml-panel-pool">
                                <div class="ml-panel-head ml-panel-head-stack">
                                    <span class="ml-panel-title">Catálogo</span>
                                </div>
                                <div class="ml-filters">
                                    <label class="ml-filter-label" for="edit_city">Cidade</label>
                                    <select id="edit_city" class="ml-select-city">
                                        <option value="">Todas as cidades</option>
                                    </select>
                                    <label class="ml-filter-label" for="edit_search">Busca</label>
                                    <input type="search" class="ml-search ml-search-wide" id="edit_search" placeholder="Nome, endereço ou #ID — use várias palavras" autocomplete="off">
                                </div>
                                <p class="ml-pool-stats" id="edit_pool_stats" aria-live="polite"></p>
                                <div class="ml-pool" id="edit_pool"></div>
                                <button type="button" class="ml-load-more" id="edit_load_more" style="display:none;">Carregar mais câmeras</button>
                            </section>
                            <section class="ml-panel ml-panel-rail">
                                <div class="ml-panel-head">
                                    <span>Grade do mosaico</span>
                                    <span class="ml-counter" id="edit_count">0</span>
                                </div>
                                <p class="ml-hint"><i class="fas fa-grip-vertical"></i> Arraste para reordenar.</p>
                                <ul class="ml-sortable" id="edit_sort"></ul>
                                <p class="ml-pool-empty" id="edit_placeholder">Nenhuma câmera selecionada.</p>
                            </section>
                        </div>
                    </div>
                    <div class="modal-footer ml-modal-foot">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="ml-btn ml-btn-primary"><i class="fas fa-save"></i> Salvar alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="application/json" id="mosaic-cameras-json">{!! json_encode($cameras, JSON_UNESCAPED_UNICODE) !!}</script>
@stop

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .mosaic-lab { font-family: 'Outfit', system-ui, sans-serif; color: #1a2332; max-width: 1200px; margin: 0 auto; padding: 0 4px 2rem; }
        .ml-hero { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid rgba(15, 20, 25, 0.08); }
        .ml-kicker { font-size: 0.7rem; letter-spacing: 0.14em; text-transform: uppercase; color: #5c6b7f; margin: 0 0 0.35rem; font-weight: 600; }
        .ml-dot { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #00c9a7; margin-right: 6px; vertical-align: middle; box-shadow: 0 0 10px #00c9a7; animation: ml-pulse 2s ease-in-out infinite; }
        @keyframes ml-pulse { 50% { opacity: 0.5; } }
        .ml-title { font-size: 1.85rem; font-weight: 700; margin: 0; letter-spacing: -0.02em; }
        .ml-sub { margin: 0.35rem 0 0; color: #5c6b7f; font-size: 0.95rem; max-width: 36rem; line-height: 1.45; }
        .ml-btn { font-family: inherit; border: none; border-radius: 10px; padding: 0.65rem 1.15rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: transform 0.15s, box-shadow 0.15s; }
        .ml-btn-primary { background: linear-gradient(135deg, #00b4a8 0%, #008f9a 100%); color: #fff; box-shadow: 0 4px 14px rgba(0, 180, 168, 0.35); }
        .ml-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0, 180, 168, 0.45); color: #fff; }
        .ml-btn-ghost { background: #fff; color: #008f9a; border: 1px solid rgba(0, 143, 154, 0.35); }
        .ml-empty { text-align: center; padding: 3rem 1rem; background: linear-gradient(160deg, #f6f9fc 0%, #eef3f8 100%); border-radius: 16px; border: 1px dashed rgba(0, 143, 154, 0.25); }
        .ml-empty-icon { font-size: 2.5rem; color: #a8b8c8; margin-bottom: 0.75rem; }
        .ml-empty p { color: #5c6b7f; margin-bottom: 1rem; }
        .ml-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
        .ml-card { background: #fff; border-radius: 14px; padding: 1.1rem 1.15rem; border: 1px solid rgba(15, 20, 25, 0.06); box-shadow: 0 4px 24px rgba(15, 20, 25, 0.06); transition: box-shadow 0.2s, transform 0.2s; position: relative; overflow: hidden; }
        .ml-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #00c9a7, #008f9a); opacity: 0.9; }
        .ml-card:hover { box-shadow: 0 8px 32px rgba(15, 20, 25, 0.1); transform: translateY(-2px); }
        .ml-card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.65rem; }
        .ml-card-badge { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; background: #0f1419; color: #a8f5e8; padding: 0.2rem 0.5rem; border-radius: 6px; }
        .ml-card-actions { display: flex; gap: 0.25rem; }
        .ml-icon-btn { width: 34px; height: 34px; border-radius: 8px; border: none; background: #f0f4f8; color: #3d4f66; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: background 0.15s, color 0.15s; }
        .ml-icon-btn:hover { background: #e2ecf5; color: #008f9a; }
        .ml-icon-danger:hover { background: #fdeaea; color: #c0392b; }
        .ml-card-name { font-size: 1.05rem; font-weight: 600; margin: 0 0 0.25rem; line-height: 1.3; }
        .ml-card-meta { font-size: 0.75rem; color: #8a9aac; margin: 0; }

        .ml-modal .modal-xl { max-width: 1100px; }
        .ml-modal-content { border: none; border-radius: 16px; overflow: hidden; background: #0f1419; color: #e8edf4; }
        .ml-modal-head { background: linear-gradient(135deg, #152028 0%, #0c1014 100%); border-bottom: 1px solid rgba(255,255,255,0.06); padding: 1.1rem 1.35rem; }
        .ml-modal-kicker { font-size: 0.65rem; letter-spacing: 0.12em; text-transform: uppercase; color: #5ce0c8; margin: 0 0 0.2rem; font-weight: 600; }
        .ml-modal-head .modal-title { color: #fff; font-weight: 700; margin: 0; font-size: 1.15rem; }
        .ml-modal-body { padding: 1.15rem 1.35rem 1.5rem; background: #12181f; }
        .ml-modal-foot { background: #0f1419; border-top: 1px solid rgba(255,255,255,0.06); padding: 1rem 1.35rem; }
        .ml-stepbar { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-size: 0.8rem; color: #7d8fa3; }
        .ml-step b { font-family: 'JetBrains Mono', monospace; color: #00c9a7; margin-right: 0.25rem; }
        .ml-step-on { color: #e8edf4; font-weight: 600; }
        .ml-step-arrow { opacity: 0.4; }
        .ml-field label { font-size: 0.8rem; color: #9dadc0; font-weight: 500; }
        .ml-input { background: #1a2332 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #fff !important; border-radius: 10px !important; padding: 0.65rem 0.85rem !important; }
        .ml-input:focus { border-color: #00c9a7 !important; box-shadow: 0 0 0 2px rgba(0, 201, 167, 0.2) !important; }
        .ml-panels { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.75rem; min-height: 320px; }
        @media (max-width: 900px) { .ml-panels { grid-template-columns: 1fr; } }
        .ml-panel { background: #1a2332; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; min-height: 0; }
        .ml-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; padding: 0.65rem 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 0.8rem; font-weight: 600; color: #c5d0dc; }
        .ml-panel-head-stack { flex-wrap: wrap; }
        .ml-panel-title { width: 100%; }
        .ml-filters { padding: 0.65rem 0.85rem 0.5rem; display: grid; grid-template-columns: 1fr; gap: 0.4rem; border-bottom: 1px solid rgba(255,255,255,0.06); }
        @media (min-width: 640px) {
            .ml-filters { grid-template-columns: 6.5rem 1fr; grid-template-rows: auto auto; align-items: center; column-gap: 0.75rem; row-gap: 0.45rem; }
            .ml-filter-label[for$="_city"] { grid-column: 1; grid-row: 1; }
            .ml-select-city { grid-column: 2; grid-row: 1; }
            .ml-filter-label[for$="_search"] { grid-column: 1; grid-row: 2; align-self: start; padding-top: 0.4rem; }
            .ml-search-wide { grid-column: 2; grid-row: 2; }
        }
        .ml-filter-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em; color: #7d8fa3; margin: 0; font-weight: 600; }
        .ml-select-city { width: 100%; max-width: 100%; background: #12181f; border: 1px solid rgba(255,255,255,0.1); color: #e8edf4; border-radius: 8px; padding: 0.45rem 0.6rem; font-size: 0.82rem; cursor: pointer; }
        .ml-select-city:focus { border-color: #00c9a7; outline: none; box-shadow: 0 0 0 2px rgba(0, 201, 167, 0.2); }
        .ml-search { flex: 1; max-width: 220px; background: #12181f; border: 1px solid rgba(255,255,255,0.08); color: #fff; border-radius: 8px; padding: 0.35rem 0.6rem; font-size: 0.8rem; }
        .ml-search-wide { max-width: none !important; width: 100%; padding: 0.5rem 0.65rem !important; font-size: 0.82rem !important; }
        .ml-search::placeholder { color: #5c6b7f; }
        .ml-pool-stats { margin: 0; padding: 0.35rem 0.85rem 0; font-size: 0.72rem; color: #7d8fa3; min-height: 1.2rem; }
        .ml-pool-stats b { color: #00c9a7; font-family: 'JetBrains Mono', monospace; font-weight: 600; }
        .ml-stats-tip { color: #8a9aac; font-weight: 400; }
        .ml-load-more { width: calc(100% - 1rem); margin: 0.35rem 0.5rem 0.5rem; padding: 0.45rem; font-size: 0.8rem; border-radius: 8px; border: 1px dashed rgba(0, 201, 167, 0.45); background: rgba(0, 201, 167, 0.08); color: #5ce0c8; cursor: pointer; font-family: inherit; font-weight: 600; transition: background 0.15s; }
        .ml-load-more:hover { background: rgba(0, 201, 167, 0.18); }
        .ml-pool { flex: 1; overflow-y: auto; padding: 0.5rem; display: flex; flex-direction: column; gap: 0.45rem; max-height: 340px; }
        .ml-pool-card { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; padding: 0.55rem 0.65rem; background: #12181f; border-radius: 10px; border: 1px solid transparent; cursor: default; transition: border 0.15s, background 0.15s; }
        .ml-pool-card:hover { border-color: rgba(0, 201, 167, 0.35); background: #152028; }
        .ml-pool-card.is-in-rail { opacity: 0.45; pointer-events: none; }
        .ml-pool-card-main { min-width: 0; }
        .ml-pool-card-title { font-weight: 600; font-size: 0.85rem; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ml-pool-card-sub { font-size: 0.72rem; color: #7d8fa3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ml-pool-card-id { font-family: 'JetBrains Mono', monospace; font-size: 0.65rem; color: #5ce0c8; }
        .ml-add-btn { flex-shrink: 0; width: 32px; height: 32px; border-radius: 8px; border: none; background: rgba(0, 201, 167, 0.15); color: #00c9a7; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s, transform 0.1s; }
        .ml-add-btn:hover:not(:disabled) { background: rgba(0, 201, 167, 0.35); transform: scale(1.05); }
        .ml-add-btn:disabled { opacity: 0.35; cursor: not-allowed; }
        .ml-panel-rail .ml-panel-head { justify-content: flex-start; gap: 0.75rem; }
        .ml-counter { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; background: rgba(0, 201, 167, 0.15); color: #00c9a7; padding: 0.15rem 0.45rem; border-radius: 6px; }
        .ml-hint { font-size: 0.72rem; color: #7d8fa3; margin: 0.35rem 0.85rem 0.5rem; }
        .ml-hint i { margin-right: 0.35rem; opacity: 0.7; }
        .ml-sortable { list-style: none; margin: 0; padding: 0.5rem; flex: 1; overflow-y: auto; max-height: 280px; min-height: 120px; }
        .ml-sortable li { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.6rem; margin-bottom: 0.4rem; background: #12181f; border-radius: 10px; border: 1px solid rgba(255,255,255,0.06); cursor: grab; font-size: 0.82rem; }
        .ml-sortable li:active { cursor: grabbing; }
        .ml-sortable li .ml-grip { color: #5c6b7f; font-size: 0.9rem; }
        .ml-sortable li .ml-rail-title { flex: 1; min-width: 0; color: #e8edf4; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ml-sortable li .ml-rail-num { font-family: 'JetBrains Mono', monospace; font-size: 0.65rem; color: #5ce0c8; width: 1.25rem; }
        .ml-rail-remove { border: none; background: transparent; color: #8a9aac; cursor: pointer; padding: 0.2rem; border-radius: 4px; }
        .ml-rail-remove:hover { color: #ff6b6b; background: rgba(255,107,107,0.1); }
        .ml-pool-empty { text-align: center; color: #5c6b7f; font-size: 0.8rem; padding: 1rem; margin: 0; display: none; }
        .ml-pool-empty.is-visible { display: block; }
        .sortable-ghost { opacity: 0.45; }
        .sortable-drag { opacity: 0.95; box-shadow: 0 8px 24px rgba(0,0,0,0.35); }
        .ml-alert { border-radius: 10px; border: none; }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
(function () {
    var cameras = [];
    try {
        cameras = JSON.parse(document.getElementById('mosaic-cameras-json').textContent) || [];
    } catch (e) { cameras = []; }

    var byId = {};
    cameras.forEach(function (c) { byId[c.id] = c; });

    function norm(s) {
        return (s == null ? '' : String(s)).toLowerCase();
    }

    /** Palavras separadas por espaço: todas precisam aparecer em algum campo (AND). */
    function searchTokens(q) {
        return norm(q).split(/\s+/).filter(function (t) { return t.length > 0; });
    }

    function fillCitySelect(id) {
        var sel = document.getElementById(id);
        if (!sel) return;
        var counts = {};
        cameras.forEach(function (c) {
            var k = (c.cidade && String(c.cidade).trim() !== '') ? String(c.cidade).trim() : '__sem__';
            counts[k] = (counts[k] || 0) + 1;
        });
        var cities = Object.keys(counts).filter(function (k) { return k !== '__sem__'; });
        cities.sort(function (a, b) { return a.localeCompare(b, 'pt-BR'); });
        if (counts['__sem__']) {
            cities.push('__sem__');
        }
        sel.innerHTML = '<option value="">Todas as cidades</option>';
        cities.forEach(function (ci) {
            var o = document.createElement('option');
            o.value = ci;
            o.textContent = (ci === '__sem__' ? '(sem cidade)' : ci) + ' (' + counts[ci] + ')';
            sel.appendChild(o);
        });
    }

    function cameraMatchesFilters(cam, cityValue, tokens) {
        if (cityValue === '__sem__') {
            if (cam.cidade && String(cam.cidade).trim() !== '') {
                return false;
            }
        } else if (cityValue) {
            if (norm(cam.cidade) !== norm(cityValue)) {
                return false;
            }
        }
        if (tokens.length === 0) {
            return true;
        }
        var hay = norm(cam.camera) + ' ' + norm(cam.local_nome) + ' ' + norm(cam.cidade) + ' ' + String(cam.id);
        for (var i = 0; i < tokens.length; i++) {
            if (hay.indexOf(tokens[i]) === -1) {
                return false;
            }
        }
        return true;
    }

    function getFilteredSortedCameras(self) {
        var cityVal = self.cityEl ? self.cityEl.value : '';
        var tokens = searchTokens(self.searchEl ? self.searchEl.value : '');
        var list = cameras.filter(function (cam) {
            return cameraMatchesFilters(cam, cityVal, tokens);
        });
        list.sort(function (a, b) {
            var la = (a.local_nome || '') + '\u0000' + (a.camera || '');
            var lb = (b.local_nome || '') + '\u0000' + (b.camera || '');
            return la.localeCompare(lb, 'pt-BR');
        });
        return list;
    }

    function escHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/"/g, '&quot;');
    }

    function MosaicPicker(prefix) {
        this.prefix = prefix;
        this.poolEl = document.getElementById(prefix + '_pool');
        this.sortEl = document.getElementById(prefix + '_sort');
        this.searchEl = document.getElementById(prefix + '_search');
        this.cityEl = document.getElementById(prefix + '_city');
        this.poolStatsEl = document.getElementById(prefix + '_pool_stats');
        this.loadMoreEl = document.getElementById(prefix + '_load_more');
        this.countEl = document.getElementById(prefix + '_count');
        this.phEl = document.getElementById(prefix + '_placeholder');
        this.order = [];
        this.sortable = null;
        this.visibleLimit = 60;
        this.POOL_CHUNK = 80;
        var self = this;

        this.setOrder = function (ids) {
            self.order = ids.map(function (id) { return parseInt(id, 10); }).filter(function (id) { return byId[id]; });
            self.renderSort();
            self.renderPool();
            self.syncHidden();
        };

        this.add = function (id) {
            id = parseInt(id, 10);
            if (!byId[id] || self.order.indexOf(id) !== -1) return;
            self.order.push(id);
            self.renderSort();
            self.renderPool();
            self.syncHidden();
        };

        this.remove = function (id) {
            id = parseInt(id, 10);
            self.order = self.order.filter(function (x) { return x !== id; });
            self.renderSort();
            self.renderPool();
            self.syncHidden();
        };

        this.renderPool = function () {
            var filtered = getFilteredSortedCameras(self);
            var total = filtered.length;
            var slice = filtered.slice(0, self.visibleLimit);

            self.poolEl.innerHTML = '';
            slice.forEach(function (cam) {
                var inRail = self.order.indexOf(cam.id) !== -1;
                var card = document.createElement('div');
                card.className = 'ml-pool-card' + (inRail ? ' is-in-rail' : '');
                card.innerHTML =
                    '<div class="ml-pool-card-main">' +
                        '<div class="ml-pool-card-title">' + escHtml(cam.camera) + '</div>' +
                        '<div class="ml-pool-card-sub">' + escHtml(cam.cidade) + (cam.local_nome ? ' · ' + escHtml(cam.local_nome) : '') + '</div>' +
                        '<span class="ml-pool-card-id">#' + cam.id + '</span>' +
                    '</div>' +
                    '<button type="button" class="ml-add-btn" data-id="' + cam.id + '"' + (inRail ? ' disabled' : '') + ' title="Adicionar à grade"><i class="fas fa-plus"></i></button>';
                self.poolEl.appendChild(card);
            });

            if (self.poolStatsEl) {
                if (total === 0) {
                    self.poolStatsEl.innerHTML = 'Nenhum resultado. Escolha outra <b>cidade</b> ou use a busca (nome, rua, bairro, <b>#ID</b>).';
                } else {
                    var more = total > slice.length;
                    self.poolStatsEl.innerHTML =
                        'Mostrando <b>' + slice.length + '</b> de <b>' + total + '</b> correspondentes' +
                        (more ? ' — <span class="ml-stats-tip">clique em <b>Carregar mais</b> ou refine a busca com mais palavras</span>' : '');
                }
            }
            if (self.loadMoreEl) {
                self.loadMoreEl.style.display = total > slice.length ? 'block' : 'none';
            }

            self.poolEl.querySelectorAll('.ml-add-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    self.add(parseInt(btn.getAttribute('data-id'), 10));
                });
            });
        };

        this.renderSort = function () {
            self.sortEl.innerHTML = '';
            self.order.forEach(function (id, idx) {
                var cam = byId[id];
                if (!cam) return;
                var li = document.createElement('li');
                li.setAttribute('data-id', id);
                li.innerHTML =
                    '<span class="ml-grip"><i class="fas fa-grip-vertical"></i></span>' +
                    '<span class="ml-rail-num">' + (idx + 1) + '</span>' +
                    '<span class="ml-rail-title" title="' + escHtml(cam.local_nome || cam.camera || '') + '">' + escHtml(cam.camera) + ' — ' + escHtml(cam.cidade) + '</span>' +
                    '<button type="button" class="ml-rail-remove" data-id="' + id + '" aria-label="Remover"><i class="fas fa-times"></i></button>';
                self.sortEl.appendChild(li);
            });
            self.sortEl.querySelectorAll('.ml-rail-remove').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    self.remove(parseInt(btn.getAttribute('data-id'), 10));
                });
            });
            if (self.countEl) self.countEl.textContent = String(self.order.length);
            if (self.phEl) {
                self.phEl.classList.toggle('is-visible', self.order.length === 0);
            }
            self.renumber();
        };

        this.renumber = function () {
            var items = self.sortEl.querySelectorAll('li');
            items.forEach(function (li, i) {
                var n = li.querySelector('.ml-rail-num');
                if (n) n.textContent = String(i + 1);
            });
        };

        this.initSortable = function () {
            if (self.sortable) {
                self.sortable.destroy();
                self.sortable = null;
            }
            if (typeof Sortable === 'undefined') return;
            self.sortable = Sortable.create(self.sortEl, {
                animation: 150,
                handle: '.ml-grip',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function () {
                    self.order = [];
                    self.sortEl.querySelectorAll('li').forEach(function (li) {
                        self.order.push(parseInt(li.getAttribute('data-id'), 10));
                    });
                    self.renderPool();
                    self.syncHidden();
                    self.renumber();
                }
            });
        };

        this.syncHidden = function () {};

        if (this.cityEl) {
            this.cityEl.addEventListener('change', function () {
                self.visibleLimit = 60;
                self.renderPool();
            });
        }
        if (this.searchEl) {
            this.searchEl.addEventListener('input', function () {
                self.visibleLimit = 60;
                self.renderPool();
            });
        }
        if (this.loadMoreEl) {
            this.loadMoreEl.addEventListener('click', function () {
                self.visibleLimit += self.POOL_CHUNK;
                self.renderPool();
            });
        }
    }

    MosaicPicker.prototype.attachForm = function (formSelector) {
        var self = this;
        var form = document.querySelector(formSelector);
        if (!form) return;
        form.addEventListener('submit', function () {
            form.querySelectorAll('input[name="camera_ids[]"]').forEach(function (n) { n.remove(); });
            self.order.forEach(function (id) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'camera_ids[]';
                inp.value = String(id);
                form.appendChild(inp);
            });
        });
    };

    fillCitySelect('novo_city');
    fillCitySelect('edit_city');

    var novoPicker = new MosaicPicker('novo');
    var editPicker = new MosaicPicker('edit');

    novoPicker.attachForm('#formNovoMosaico');
    editPicker.attachForm('#formEditarMosaico');

    var pendingAddId = @json($addId ? (int) $addId : null);

    $('#modalNovoMosaico').on('shown.bs.modal', function () {
        novoPicker.setOrder([]);
        if (pendingAddId) {
            novoPicker.add(pendingAddId);
            pendingAddId = null;
            setTimeout(function () {
                var el = document.getElementById('novo_nome');
                if (el) el.focus();
            }, 50);
        }
        novoPicker.initSortable();
    });

    $('#modalEditarMosaico').on('shown.bs.modal', function () {
        editPicker.renderPool();
        editPicker.initSortable();
    });

    $('#modalNovoMosaico').on('hidden.bs.modal', function () {
        if (novoPicker.sortable) { novoPicker.sortable.destroy(); novoPicker.sortable = null; }
        document.getElementById('novo_nome').value = '';
        var nc = document.getElementById('novo_city');
        var ns = document.getElementById('novo_search');
        if (nc) nc.value = '';
        if (ns) ns.value = '';
        novoPicker.visibleLimit = 60;
        novoPicker.setOrder([]);
    });

    $('#modalEditarMosaico').on('hidden.bs.modal', function () {
        if (editPicker.sortable) { editPicker.sortable.destroy(); editPicker.sortable = null; }
        var ec = document.getElementById('edit_city');
        var es = document.getElementById('edit_search');
        if (ec) ec.value = '';
        if (es) es.value = '';
        editPicker.visibleLimit = 60;
    });

    $('.btn-edit-mosaic').on('click', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nome = $(this).data('nome');
        var ids = $(this).data('camera-ids');
        if (typeof ids === 'string') {
            try { ids = JSON.parse(ids); } catch (err) { ids = []; }
        }
        $('#formEditarMosaico').attr('action', '{{ url('mosaicos') }}/' + id);
        $('#edit_nome').val(nome);
        var ec = document.getElementById('edit_city');
        var es = document.getElementById('edit_search');
        if (ec) ec.value = '';
        if (es) es.value = '';
        editPicker.visibleLimit = 60;
        editPicker.setOrder(ids || []);
        $('#modalEditarMosaico').modal('show');
    });

    @if($addId)
    $(function () { $('#modalNovoMosaico').modal('show'); });
    @endif
})();
    </script>
@stop
