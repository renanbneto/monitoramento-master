@extends('adminlte::page')

@section('title', $evento->nome)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>{{ $evento->nome }}</h1>
            <small class="text-muted">
                @if($evento->local_nome) <i class="fas fa-map-marker-alt"></i> {{ $evento->local_nome }} &nbsp; @endif
                @if($evento->data_inicio) <i class="fas fa-calendar"></i> {{ $evento->data_inicio->format('d/m/Y H:i') }} @endif
                @if($evento->data_fim) — {{ $evento->data_fim->format('d/m/Y H:i') }} @endif
            </small>
        </div>
        <div style="gap:8px; display:flex;">
            <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('eventos.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    {{-- Mapa --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-map"></i> Mapa do Evento</h3></div>
            <div class="card-body p-0">
                <div id="mapa-evento" style="height:400px;"></div>
            </div>
        </div>
    </div>

    {{-- Câmeras --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-video"></i> Câmeras do Evento</h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $evento->cameras->count() }} câmeras</span>
                </div>
            </div>
            <div class="card-body" style="max-height:400px; overflow-y:auto;">
                @forelse($evento->cameras as $camera)
                <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                    <div>
                        <strong>{{ $camera->camera }}</strong><br>
                        <small class="text-muted">{{ $camera->cidade }}{{ $camera->local_nome ? ' — ' . $camera->local_nome : '' }}</small>
                    </div>
                    <div>
                        @php
                            $sc = match($camera->status ?? 'unknown') {
                                'online' => 'success', 'offline' => 'danger', default => 'secondary'
                            };
                            $sl = match($camera->status ?? 'unknown') {
                                'online' => 'Online', 'offline' => 'Offline', default => 'Desconhecido'
                            };
                        @endphp
                        <span class="badge badge-{{ $sc }}">{{ $sl }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">Nenhuma câmera vinculada a este evento.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    {{-- Placeholder: Contagem de Veículos --}}
    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h3 class="card-title"><i class="fas fa-car"></i> Contagem de Veículos</h3>
                <div class="card-tools">
                    <span class="badge badge-light text-warning">Em desenvolvimento</span>
                </div>
            </div>
            <div class="card-body text-center py-5">
                <i class="fas fa-car fa-4x text-warning mb-3"></i>
                <h5 class="text-muted">Análise de fluxo veicular por IA</h5>
                <p class="text-muted small">
                    Futura integração com inteligência artificial de visão computacional
                    para contagem automática de veículos em tempo real a partir das câmeras do evento.
                </p>
                <span class="badge badge-warning px-4 py-2">Em desenvolvimento</span>
            </div>
        </div>
    </div>

    {{-- Placeholder: Contagem de Pessoas --}}
    <div class="col-md-6">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h3 class="card-title"><i class="fas fa-users"></i> Contagem de Pessoas</h3>
                <div class="card-tools">
                    <span class="badge badge-light text-info">Em desenvolvimento</span>
                </div>
            </div>
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-4x text-info mb-3"></i>
                <h5 class="text-muted">Estimativa de público por IA</h5>
                <p class="text-muted small">
                    Futura integração com inteligência artificial de visão computacional
                    para estimativa de densidade e contagem de pessoas presentes no evento.
                </p>
                <span class="badge badge-info px-4 py-2">Em desenvolvimento</span>
            </div>
        </div>
    </div>
</div>

@if($evento->descricao)
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle"></i> Descrição</h3></div>
            <div class="card-body">{{ $evento->descricao }}</div>
        </div>
    </div>
</div>
@endif
@stop

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #mapa-evento { z-index: 0; }
</style>
@stop

@section('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function() {
    var lat  = {{ $evento->lat  ? (float)$evento->lat  : -25.4284 }};
    var lng  = {{ $evento->lng  ? (float)$evento->lng  : -49.2733 }};
    var zoom = {{ ($evento->lat && $evento->lng) ? 14 : 11 }};

    var map = L.map('mapa-evento').setView([lat, lng], zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    @if($evento->lat && $evento->lng)
    L.marker([{{ (float)$evento->lat }}, {{ (float)$evento->lng }}])
        .addTo(map)
        .bindPopup('<strong>{{ addslashes($evento->nome) }}</strong><br>{{ addslashes($evento->local_nome ?? '') }}')
        .openPopup();
    @endif

    var cameras = @json($evento->cameras->filter(fn($c) => $c->lat && $c->lng)->values());
    cameras.forEach(function(c) {
        L.circleMarker([parseFloat(c.lat), parseFloat(c.lng)], {
            radius: 8, color: '#007bff', fillColor: '#007bff', fillOpacity: 0.8
        }).addTo(map).bindPopup('<i class="fas fa-video"></i> ' + c.camera + '<br><small>' + (c.local_nome || '') + '</small>');
    });

    if (cameras.length > 0) {
        var bounds = cameras.map(function(c) { return [parseFloat(c.lat), parseFloat(c.lng)]; });
        @if($evento->lat && $evento->lng)
        bounds.push([{{ (float)$evento->lat }}, {{ (float)$evento->lng }}]);
        @endif
        map.fitBounds(bounds, { padding: [30, 30] });
    }
})();
</script>
@stop
