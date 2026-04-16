@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')
@extends('adminlte::page')

@section('title', 'Cadastrar Câmera')

@section('content_header')
    {{-- Configurar Breadcrumb em
    app\Http\Controllers\breadcrumbs\BreadCrumbsLocalController.php --}}
{{--     {{ Breadcrumbs::render('nomeDaRotaBreadcrumb') }} --}}
@stop

@section('content')
{{-- Padrão para autorização de permissões na view. --}}
@if ($Autorizacao->can(['Administrador']))
    {{-- Código --}}
@endif

<div class="container">
    <h2 class="bg-primary d-flex justify-content-center w-100 mt-5 mb-3">Listagem de câmeras disponíveis</h2>

    <table id="tblCameras" class="hover stripe">
        <thead>
            <tr>
                <th>Cidade</th>
                <th>Câmera</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cameras as $camera)
            <tr>
                <td>{{$camera->cidade}}</td>
                <td>{{$camera->camera}}</td>
                <td>{{$camera->local_nome}}</td>
                <td>
                    <a href="/cameras/{{$camera->id}}/edit" title="Editar" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                    <a href="/cameras/{{$camera->id}}/edit" title="Excluir" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                    @if ($camera->ativo)
                    <a href="/cameras/{{$camera->id}}/edit" title="Habilitada" class="btn btn-success"><i class="fas fa-toggle-on"></i></a>
                    @endif

                    @if (!$camera->ativo)
                    <a href="/cameras/{{$camera->id}}/edit" title="Desabilitada" class="btn btn-danger"><i class="fas fa-toggle-off"></i></a>
                    @endif
                    <a href="/cameras/{{$camera->id}}/edit" data-camera="{{json_encode($camera)}}" onclick="-+-" title="Adicionar ao mosaico" class="btn btn-secondary"><i class="fas fa-th"></i></a>
                    <a href="/cameras/{{$camera->id}}/edit" title="Mostrar no mapa" class="btn btn-"><i class="fas fa-map"></i></a>
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

var mosaicos = {
        m1:[],
        m2:[],
        m3:[],
        m4:[],
        m5:[],
        m6:[],
        m7:[],
        m8:[],
        m9:[],
        m10:[]
    };

$.ajax({
    url:'{{route('mosaicos')}}',
    success: function(data) {
        mosaicosBanco = JSON.parse(data.mosaico);

        for (let index = 1; index <= 10; index++) {

            if (!mosaicosBanco.hasOwnProperty(`m${index}`)) {
               mosaicosBanco[`m${index}`] = [];
            }

        }

        mosaicos = mosaicosBanco;

        localStorage.setItem('mosaicos', JSON.stringify(mosaicosBanco));
    }
});

function updateMosaicos(){
    $.ajax({
    url:'{{route('atualizaMosaicos')}}',
    method:'POST',
    data:{mosaico:JSON.stringify(mosaicos)},
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function(data) {
        console.log("Mosaicos atualizados no perfil do usuário!");
    },
    error: function(){
        console.log("Erro ao atualizar os mosaicos no perfil do usuário!");
    }
});
}

function refreshMosaicos(){
    console.log('Atualizando mosaicos nas views!');
}

function addToMosaico(mosaico,camera){
    mosaicos[mosaico].push(camera);
    localStorage.setItem('mosaicos', JSON.stringify(mosaicosBanco));
    updateMosaicos();
    refreshMosaicos();
}

    $(document).ready(function(){

        new DataTable('#tblCameras',{
            responsive:true,
            paging:true,
            select: true,
            language: {
            processing:     "Processando...",
            search:         "Buscar&nbsp;:",
            lengthMenu:    "Exibir _MENU_ registros",
            info:           "Exibindo _START_ a _END_ de _TOTAL_ câmeras",
            infoEmpty:      "Exibindo 0 a 0 de 0 câmeras",
            infoFiltered:   "(filtrado de _MAX_ câmeras no total)",
            infoPostFix:    "",
            loadingRecords: "Carregando...",
            zeroRecords:    "Nenhum registro encontrado",
            emptyTable:     "Nenhuma câmera cadastrada",
            paginate: {
                first:      "Primeiro",
                previous:   "Anterior",
                next:       "Próximo",
                last:       "Último"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        }
        })

/*
         $("#cidade").select2({
            minimumInputLength: 2,
            ajax: {
                url: '{{route('cidades')}}',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function (term) {
                    return {
                        term: term
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.map(el => {return {id:el.text,text:el.text}})
                    };
                }
            }
        }); */
    });
</script>
@stop
