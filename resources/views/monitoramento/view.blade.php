@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')
@extends('adminlte::page')

@section('title', 'Câmeras')

@section('content_header')
@stop

@section('content')
@if ($Autorizacao->can(['Administrador']))
    {{-- Código --}}
@endif

<div class="container">
    <h2 class="bg-primary d-flex justify-content-center w-100 mt-5 mb-3">Listagem de câmeras disponíveis</h2>

    <div class="d-flex mb-3" style="gap:12px;">
        <span class="badge badge-success px-3 py-2" id="count-online">Online: –</span>
        <span class="badge badge-danger px-3 py-2" id="count-offline">Offline: –</span>
        <span class="badge badge-secondary px-3 py-2" id="count-unknown">Desconhecido: –</span>
        <small class="text-muted align-self-center ml-2" id="last-checked"></small>
    </div>

    <table id="tblCameras" class="hover stripe">
        <thead>
            <tr>
                <th>Status</th>
                <th>Cidade</th>
                <th>Câmera</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cameras as $camera)
            <tr>
                <td data-camera-id="{{ $camera->id }}">
                    @php
                        $statusClass = match($camera->status ?? 'unknown') {
                            'online'  => 'success',
                            'offline' => 'danger',
                            default   => 'secondary',
                        };
                        $statusLabel = match($camera->status ?? 'unknown') {
                            'online'  => 'Online',
                            'offline' => 'Offline',
                            default   => 'Desconhecido',
                        };
                    @endphp
                    <span class="badge badge-{{ $statusClass }} status-badge"
                          title="{{ $camera->status_checked_at ? 'Verificado: ' . $camera->status_checked_at->diffForHumans() : 'Nunca verificado' }}">
                        {{ $statusLabel }}
                    </span>
                </td>
                <td>{{$camera->cidade}}</td>
                <td>{{$camera->camera}}</td>
                <td>{{$camera->local_nome}}</td>
                <td>
                    <a href="/cameras/{{$camera->id}}/edit" title="Editar" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                    <a href="/cameras/{{$camera->id}}/edit" title="Excluir" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                    @if ($camera->ativo)
                    <a href="/cameras/{{$camera->id}}/edit" title="Habilitada" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>
                    @else
                    <a href="/cameras/{{$camera->id}}/edit" title="Desabilitada" class="btn btn-danger btn-sm"><i class="fas fa-toggle-off"></i></a>
                    @endif
                    <a href="/cameras/{{$camera->id}}/edit" title="Mostrar no mapa" class="btn btn-light btn-sm"><i class="fas fa-map"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>

<script>
function refreshCameraStatus() {
    $.getJSON('{{ route('cameras.status-json') }}', function(data) {
        var online = 0, offline = 0, unknown = 0;

        $('[data-camera-id]').each(function() {
            var id = $(this).data('camera-id');
            var row = data[id] != null ? data[id] : data[String(id)];
            var badge = $(this).find('.status-badge');
            if (!row) {
                badge.removeClass('badge-success badge-danger badge-secondary').addClass('badge-secondary').text('Desconhecido');
                unknown++;
                return;
            }

            var s = row.status;
            badge.removeClass('badge-success badge-danger badge-secondary');

            if (s === 'online') {
                badge.addClass('badge-success').text('Online');
                online++;
            } else if (s === 'offline') {
                badge.addClass('badge-danger').text('Offline');
                offline++;
            } else {
                badge.addClass('badge-secondary').text('Desconhecido');
                unknown++;
            }

            if (row.checked_at) {
                badge.attr('title', 'Verificado: ' + new Date(row.checked_at).toLocaleString('pt-BR'));
            }
        });

        $('#count-online').text('Online: ' + online);
        $('#count-offline').text('Offline: ' + offline);
        $('#count-unknown').text('Desconhecido: ' + unknown);
        $('#last-checked').text('Atualizado às ' + new Date().toLocaleTimeString('pt-BR'));
    });
}

$(document).ready(function() {
    new DataTable('#tblCameras', {
        responsive: true,
        paging: true,
        select: true,
        language: {
            processing:     "Processando...",
            search:         "Buscar:",
            lengthMenu:     "Exibir _MENU_ registros",
            info:           "Exibindo _START_ a _END_ de _TOTAL_ câmeras",
            infoEmpty:      "Exibindo 0 a 0 de 0 câmeras",
            infoFiltered:   "(filtrado de _MAX_ câmeras no total)",
            loadingRecords: "Carregando...",
            zeroRecords:    "Nenhum registro encontrado",
            emptyTable:     "Nenhuma câmera cadastrada",
            paginate: { first: "Primeiro", previous: "Anterior", next: "Próximo", last: "Último" }
        }
    });

    refreshCameraStatus();
    setInterval(refreshCameraStatus, 30000);
});
</script>
@stop
