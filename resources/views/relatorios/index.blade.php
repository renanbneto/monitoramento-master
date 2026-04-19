@extends('adminlte::page')

@section('title', 'Relatórios')

@section('content_header')
    <h1><i class="fas fa-file-csv mr-2"></i>Relatórios Operacionais</h1>
@stop

@section('content')

{{-- Contadores resumo --}}
<div class="row">
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-video"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Câmeras cadastradas</span>
                <span class="info-box-number">{{ $totalCameras }}</span>
                <div class="progress"><div class="progress-bar" style="width:100%"></div></div>
                <span class="progress-description">
                    <span class="text-success">{{ $camerasOnline }} online</span> &middot;
                    <span class="text-danger">{{ $camerasOffline }} offline</span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Grandes Eventos</span>
                <span class="info-box-number">{{ $totalEventos }}</span>
                <div class="progress"><div class="progress-bar" style="width:100%"></div></div>
                <span class="progress-description">{{ $eventosAtivos }} ativo(s)</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-car"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Prospecções LPR</span>
                <span class="info-box-number">{{ $totalLPR }}</span>
                <div class="progress"><div class="progress-bar" style="width:100%"></div></div>
                <span class="progress-description">pontos cadastrados</span>
            </div>
        </div>
    </div>
</div>

{{-- Exportações --}}
<div class="row">
    {{-- Câmeras --}}
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-video mr-1"></i> Câmeras</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Exporta todas as câmeras cadastradas com status atual, protocolo, formato e data do último check.
                </p>
                <a href="{{ route('relatorios.export.cameras') }}" class="btn btn-info btn-block">
                    <i class="fas fa-download mr-1"></i> Exportar CSV
                </a>
            </div>
        </div>
    </div>

    {{-- Eventos --}}
    <div class="col-md-4">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Grandes Eventos</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Exporta eventos com contagem de câmeras associadas. Filtre por período.
                </p>
                <form method="GET" action="{{ route('relatorios.export.eventos') }}">
                    <div class="form-row mb-2">
                        <div class="col">
                            <label class="small mb-0">De</label>
                            <input type="date" name="de" class="form-control form-control-sm"
                                   value="{{ request('de') }}">
                        </div>
                        <div class="col">
                            <label class="small mb-0">Até</label>
                            <input type="date" name="ate" class="form-control form-control-sm"
                                   value="{{ request('ate') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning btn-block">
                        <i class="fas fa-download mr-1"></i> Exportar CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- LPR --}}
    <div class="col-md-4">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-car mr-1"></i> Prospecção LPR</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Exporta todos os pontos de prospecção LPR com localização, sentido e responsável.
                </p>
                <a href="{{ route('relatorios.export.lpr') }}" class="btn btn-success btn-block">
                    <i class="fas fa-download mr-1"></i> Exportar CSV
                </a>
            </div>
        </div>
    </div>
</div>

@stop
