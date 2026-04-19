@extends('adminlte::page')

@section('title', 'Log de Auditoria LGPD')

@section('content_header')
    <h1><i class="fas fa-shield-alt mr-2"></i>Log de Auditoria LGPD</h1>
@stop

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Filtros</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('auditoria-lgpd') }}" class="form-inline flex-wrap" style="gap:8px;">
            <select name="acao" class="form-control form-control-sm">
                <option value="">— Todas as ações —</option>
                @foreach($acoes as $a)
                    <option value="{{ $a }}" {{ request('acao') === $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
            <input type="text" name="user_rg" class="form-control form-control-sm"
                   placeholder="RG do policial" value="{{ request('user_rg') }}" style="width:150px;">
            <input type="date" name="de"  class="form-control form-control-sm" value="{{ request('de') }}">
            <input type="date" name="ate" class="form-control form-control-sm" value="{{ request('ate') }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search mr-1"></i>Filtrar</button>
            <a href="{{ route('auditoria-lgpd') }}" class="btn btn-sm btn-default">Limpar</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            {{ $logs->total() }} registro(s) encontrado(s)
        </h3>
    </div>
    <div class="card-body p-0" style="overflow-x:auto;">
        <table class="table table-sm table-hover table-striped mb-0">
            <thead class="thead-dark">
                <tr>
                    <th style="width:145px;">Data/Hora</th>
                    <th>Ação</th>
                    <th>Usuário</th>
                    <th>RG</th>
                    <th>Recurso</th>
                    <th>IP</th>
                    <th>Detalhes</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="text-nowrap text-muted" style="font-size:11px;">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td>
                        <span class="badge badge-{{ \App\Support\AuditBadge::cor($log->acao) }}">
                            {{ $log->acao }}
                        </span>
                    </td>
                    <td>{{ $log->user_nome ?? '—' }}</td>
                    <td>{{ $log->user_rg  ?? '—' }}</td>
                    <td>
                        @if($log->recurso)
                            <small>{{ $log->recurso }}
                            @if($log->recurso_id) #{{ $log->recurso_id }}@endif
                            </small>
                        @else —
                        @endif
                    </td>
                    <td><small>{{ $log->ip ?? '—' }}</small></td>
                    <td>
                        @if($log->detalhes)
                            <small class="text-muted">
                                @foreach($log->detalhes as $k => $v)
                                    <b>{{ $k }}</b>: {{ $v }}&nbsp;
                                @endforeach
                            </small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Nenhum registro encontrado.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $logs->links() }}
    </div>
</div>
@stop
