@extends('adminlte::page')

@section('title', 'Permissões do Usuário')

@section('content_header')
{{ Breadcrumbs::render('permissoesUsuario') }}

    {{-- <h1>Permissões do Usuário <strong>{{$nome}}</strong></h1> --}}
    {{-- <h6>{{$descricao}}</h6> --}}
@stop

@section('content')
@csrf
<div>
    Nome    : {{$nome}}
    <br>
    Usuário : {{$usuario}}
    <br>
    RG      : {{$rg}}
    <br>
    Email   : {{$email}}
    <br>
    <br>
</div>


<h3>Permissões Habilitadas</h3>
<div id="jsGridPermissoesUsuario"></div>
<br>
<br>
<br>
<h3>Permissões Disponíveis</h3>
<div id="jsGridPermissoesDisponiveis"></div>


@stop

@section('css')
    <link type="text/css" rel="stylesheet" href="{{asset('vendor/jsgrid/dist/jsgrid.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('vendor/jsgrid/dist/jsgrid-theme.min.css')}}" />
@stop

@section('js')
<script type="text/javascript" src="{{asset('vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/jsgrid/dist/jsgrid.min.js')}}"></script>
<script>

      //JSGRID para usuários Habilitados
      $("#jsGridPermissoesUsuario").jsGrid({
            width: "100%",

            searchModeButtonTooltip: "Mudar para Busca",
            insertModeButtonTooltip: "Criar registro",
            editButtonTooltip: "Editar",
            deleteButtonTooltip: "Excluir",
            searchButtonTooltip: "Procurar",
            clearFilterButtonTooltip: "Limpar Filtro",
            insertButtonTooltip: "Criar",
            updateButtonTooltip: "Atualizar",
            cancelEditButtonTooltip: "Cancelar ediçao", 
            redoButtonTooltip:"Refazer Assinatura",

            filtering:true,
            inserting: false,
            editing: false,
            sorting: true,
            paging: true,
            autoload:true,

            confirmDeleting: true,

            deleteConfirm: function(item) {
                return "Remover permissão  \"" + item.permissoes_nome + "\" do sistema \"" + item.sistemas_nome + "\"?";
            },

            pagerContainer: null,
            pageIndex: 1,
            pageSize: 20,
            pageButtonCount: 7,
            pagerFormat: "Páginas: {first} {prev} {pages} {next} {last}    {pageIndex} of {pageCount}",
            pagePrevText: "Ant",
            pageNextText: "Próx",
            pageFirstText: "Primeira",
            pageLastText: "Última",
            pageNavigatorNextText: ">>",
            pageNavigatorPrevText: "<<",

            controller: {
                loadData: function(filter) {
                    let url = "{{route('listarPermissoesUsuario',":id")}}"
                    url= `${url.replace(':id',{{$id}})}`
                    return $.ajax({
                        method:"GET",
                        url:url,
                        dataType: "json",
                        data:filter,
                        success:function(data){
                            console.log(data);
                        }
                    })
                },

                //Remove Parmissão do Usuário
                deleteItem:function(filter){
                    let url = "{{route('removerPermissaoUsuario',":id")}}"
                    url= `${url.replace(':id',{{$id}})}`
                    return $.ajax({
                        method:"POST",
                        url: url,
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:filter,
                        success:function(){
                            $("#jsGridPermissoesDisponiveis").jsGrid("loadData");
                        }
                    });
                }
            },
            
            fields: [
                { name: "id",         title: "ID",              type: "number", visible:false,},
                { name: "sistemas_nome",       title: "Sistema",            type: "text", },
                { name: "permissoes_nome",    title: "Permissão",         type: "text", },
                { name: "permissoes_descricao",         title: "Desrição",              type: "text", },
                {
                    width:60, type: "control", 
                    editButton: false, deleteButton: false,
                    itemTemplate: function(value, item) {
                         
                        //Botão remover Permissão de Usuário
                        var $iconeRemovePermissao = $("<i>").attr({class: "fas fa-minus"});
                        var $btnRemovePermissaoUsuario = $("<button>")
                            .attr({class: "btn btn-sm bg-gradient-danger text-light"})
                            .attr({role: "button"})
                            .attr({title: "Remover Permissão"})
                            .attr({id: "btn-delete-" + item.id})
                            .append($iconeRemovePermissao)
                            .click(function() {
                                $("#jsGridPermissoesUsuario").jsGrid("deleteItem",item);
                            })
                        ;
                                
                        return $("<div>").attr({class: "btn-toolbar"})
                            .append($btnRemovePermissaoUsuario)
                            ;
                        }
                    }
            ]
        });

      //JSGRID para usuários Habilitados
      $("#jsGridPermissoesDisponiveis").jsGrid({
            width: "100%",

            searchModeButtonTooltip: "Mudar para Busca",
            insertModeButtonTooltip: "Criar registro",
            editButtonTooltip: "Editar",
            deleteButtonTooltip: "Excluir",
            searchButtonTooltip: "Procurar",
            clearFilterButtonTooltip: "Limpar Filtro",
            insertButtonTooltip: "Criar",
            updateButtonTooltip: "Atualizar",
            cancelEditButtonTooltip: "Cancelar ediçao", 
            redoButtonTooltip:"Refazer Assinatura",

            filtering:true,
            inserting: false,
            editing: false,
            sorting: true,
            paging: true,
            autoload:true,

            confirmDeleting: true,

            deleteConfirm: function(item) {
                return "Adiionar a permissão \"" + item.permissoes_nome + "\" do sistema \"" + item.sistemas_nome + "\"?";
            },

            pagerContainer: null,
            pageIndex: 1,
            pageSize: 20,
            pageButtonCount: 7,
            pagerFormat: "Páginas: {first} {prev} {pages} {next} {last}    {pageIndex} of {pageCount}",
            pagePrevText: "Ant",
            pageNextText: "Próx",
            pageFirstText: "Primeira",
            pageLastText: "Última",
            pageNavigatorNextText: ">>",
            pageNavigatorPrevText: "<<",

            controller: {
                //Carrega Lista Permissões Disponíveis
                loadData: function(filter) {
                    let url = "{{route('listarPermissoesUsuario',":id")}}"
                    url= `${url.replace(':id',{{$id}})}`
                    filter.disponiveis = 'true'
                    return $.ajax({
                        method:"GET",
                        url:url,
                        dataType: "json",
                        data:filter,
                        success:function(data){
                            console.log(data);
                        }
                    })
                },

                //Adiciona Permissão ao Usuário 
                deleteItem:function(filter){
                    let url = "{{route('adicionarPermissaoUsuario',":id")}}"
                    url= `${url.replace(':id',{{$id}})}`
                    return $.ajax({
                        method:"POST",
                        url: url,
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:filter,
                        success:function(){
                            $("#jsGridPermissoesUsuario").jsGrid("loadData");
                        }
                    });
                }
            },
            
            fields: [
                { name: "id",         title: "ID",              type: "number", visible:false,},
                { name: "sistemas_nome",       title: "Sistema",            type: "text", },
                { name: "permissoes_nome",    title: "Permissão",         type: "text", },
                { name: "permissoes_descricao",         title: "Desrição",              type: "text", },
                {
                    width:60, type: "control", 
                    editButton: false, deleteButton: false,
                    itemTemplate: function(value, item) {
                         
                        //Botão para adicionar parmissão ao Usuário
                        var $iconeadicionarPermissao = $("<i>").attr({class: "fas fa-plus"});
                        var $btnAdicionarPermissao = $("<button>")
                            .attr({class: "btn btn-sm bg-gradient-success text-light "})
                            .attr({role: "button"})
                            .attr({title: "Adicionar Permissão"})
                            .attr({id: "btn-delete-" + item.id})
                            .append($iconeadicionarPermissao)
                            .click(function() {
                                $("#jsGridPermissoesDisponiveis").jsGrid("deleteItem",item);
                                $("#jsGridPermissoesUsuarios").jsGrid("loadData");
                            })
                        ;

                        return $("<div>").attr({class: "btn-toolbar"})
                            .append($btnAdicionarPermissao)
                            ;
                        }
                    }
            ]
        });

</script>

@stop


