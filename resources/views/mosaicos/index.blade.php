@extends('adminlte::page')

@section('title', 'Mosaicos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0"><i class="fas fa-th mr-2"></i>Meus Mosaicos</h1>
        <a href="{{ route('mosaicos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Novo Mosaico
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($mosaicos->isEmpty())
        <div class="callout callout-info">
            <h5>Nenhum mosaico cadastrado ainda.</h5>
            <p>Clique em <strong>Novo Mosaico</strong> para criar seu primeiro conjunto de câmeras.</p>
        </div>
    @else
        <div class="row">
            @foreach($mosaicos as $mosaico)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card card-dark h-100">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-th-large mr-1"></i>
                            {{ $mosaico->nome }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-secondary">{{ $mosaico->cameras_count }} câmera(s)</span>
                        </div>
                    </div>

                    {{-- Preview das primeiras 4 câmeras --}}
                    <div class="card-body p-1" style="background:#111;">
                        @if($mosaico->cameras->isNotEmpty())
                            <div class="row no-gutters">
                                @foreach($mosaico->cameras->take(4) as $cam)
                                <div class="{{ $mosaico->cameras->count() === 1 ? 'col-12' : 'col-6' }}"
                                     style="height:100px; overflow:hidden;">
                                    <img src="{{ $cam->link }}"
                                         alt="{{ $cam->local_nome }}"
                                         style="width:100%; height:100%; object-fit:cover;"
                                         onerror="this.src='{{ asset('images/camoff.png') }}'">
                                </div>
                                @endforeach
                                @if($mosaico->cameras_count > 4)
                                <div class="col-12 text-center text-muted" style="font-size:11px; padding:2px;">
                                    + {{ $mosaico->cameras_count - 4 }} câmera(s) adicionais
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center text-muted py-3" style="font-size:13px;">
                                <i class="fas fa-camera-slash"></i> Sem câmeras
                            </div>
                        @endif
                    </div>

                    @if($mosaico->descricao)
                    <div class="card-body py-2 px-3">
                        <small class="text-muted">{{ $mosaico->descricao }}</small>
                    </div>
                    @endif

                    <div class="card-footer d-flex justify-content-between p-2">
                        <a href="{{ route('mosaicos.show', $mosaico) }}"
                           class="btn btn-sm btn-success">
                            <i class="fas fa-play mr-1"></i> Abrir
                        </a>
                        <div>
                            <a href="{{ route('mosaicos.edit', $mosaico) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('mosaicos.destroy', $mosaico) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Remover mosaico {{ addslashes($mosaico->nome) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
@stop
