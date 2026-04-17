@extends('adminlte::page')

@section('title', 'Grandes Eventos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Grandes Eventos</h1>
        <a href="{{ route('eventos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Evento
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table id="tblEventos" class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th>
                    <th>Local</th>
                    <th>Data Início</th>
                    <th>Data Fim</th>
                    <th>Câmeras</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventos as $evento)
                <tr>
                    <td>{{ $evento->nome }}</td>
                    <td>{{ $evento->local_nome ?? '–' }}</td>
                    <td>{{ $evento->data_inicio ? $evento->data_inicio->format('d/m/Y H:i') : '–' }}</td>
                    <td>{{ $evento->data_fim ? $evento->data_fim->format('d/m/Y H:i') : '–' }}</td>
                    <td><span class="badge badge-info">{{ $evento->cameras_count }}</span></td>
                    <td>
                        @if($evento->ativo)
                            <span class="badge badge-success">Ativo</span>
                        @else
                            <span class="badge badge-secondary">Inativo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('eventos.show', $evento) }}" class="btn btn-info btn-sm" title="Visualizar">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-primary btn-sm" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Remover este evento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Remover">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
<script>
$(document).ready(function() {
    new DataTable('#tblEventos', {
        responsive: true,
        order: [[2, 'desc']],
        language: {
            search: "Buscar:", lengthMenu: "Exibir _MENU_ registros",
            info: "Exibindo _START_ a _END_ de _TOTAL_ eventos",
            infoEmpty: "Nenhum evento", zeroRecords: "Nenhum evento encontrado",
            emptyTable: "Nenhum evento cadastrado",
            paginate: { first:"Primeiro", previous:"Anterior", next:"Próximo", last:"Último" }
        }
    });
});
</script>
@stop
