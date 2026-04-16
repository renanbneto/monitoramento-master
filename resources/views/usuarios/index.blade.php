@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
{{ Breadcrumbs::render('usuarios') }}

    <div class="row">
        <div class="col-sm-12">
        {{-- <h1>Usuários  <span id="spanModoUsuarios">ATIVOS</span></h1> --}}
            <button type="button" id="btnModoUsuarios" class="btn bg-gradient-navy btn-sm text-white" data-ativos="true"  >Visualizar Inativos</button>
        </div>
    </div>

@stop

@section('content')
@csrf
  
<div id="jsGrid"></div>
<br>
<div id="jsGridInativos" style="display: none" ></div>

@stop

@section('css')
<link type="text/css" rel="stylesheet" href="{{asset('vendor/jsgrid/dist/jsgrid.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('vendor/jsgrid/dist/jsgrid-theme.min.css')}}" />
 
@stop

@section('js')
<script type="text/javascript" src="{{asset('vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/jsgrid/dist/jsgrid.min.js')}}"></script>
<script>

        $(document).ready(function(){
        
            $('#btnModoUsuarios').click(function(){
                
                let modo = $(this).attr('data-ativos');
                if (modo == 'true') {
                    //altera tela para mostrar INATIVOS
                    $(this).attr('data-ativos',false)
                    $('#jsGrid').hide('slow');
                    $('#jsGridInativos').show('slow');
                    $('#spanModoUsuarios').html('INATIVOS');
                    $(this).html('Visualizar Ativos');
                } else {
                    //altera tela para mostrar ATIVOS
                    $(this).attr('data-ativos',true)
                    $('#jsGrid').show('slow');
                    $('#jsGridInativos').hide('slow');
                    $('#spanModoUsuarios').html('ATIVOS');
                    $(this).html('Visualizar Inativos');
                }
            })
        });

       //JSGRID para usuários Habilitados
        $("#jsGrid").jsGrid({
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
            inserting: true,
            editing: true,
            sorting: true,
            paging: true,
            autoload:true,

            confirmDeleting: true,

            deleteConfirm: function(item) {
                return "O Usuário \"" + item.nome + "\" será desabilitado. Confirmar?";
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

            // data:clients,
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        url: "{{route('listarUsuarios')}}",
                        dataType: "json",
                        data:filter,
                    });
                },

                //TODO não utilizado
                insertItem:function(filter){
                    return $.ajax({
                        method:"POST",
                        url: "{{route('habilitarUsuario')}}",
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:filter,
                    });
                },
                //TODO não utilizado
                // editItem:function(filter){
                //     return $.ajax({
                //         method:"POST",
                //         url: "{{route('habilitarUsuario')}}",
                //         dataType: "json",
                //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                //         data:filter,
                //         success:function(e){
                //         },
                //     });
                // },

                //Atualiza usuário via JsGrid            
                updateItem:function(filter){
                    return $.ajax({
                        method:"POST",
                        url: "{{route('habilitarUsuario')}}",
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:filter,
                        
                    });
                },

                //Desabilita Usuário 
                deleteItem:function(filter){
                    return $.ajax({
                        method:"POST",
                        url: "{{route('desabilitarUsuario')}}",
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:filter,
                        success:function(){
                            $("#jsGridInativos").jsGrid("loadData");
                        }
                    });
                }
            },
            
            fields: [
                { name: "id",         title: "ID",              type: "number", visible:false,},
                { name: "nome",       title: "Nome",            type: "text", },
                { name: "usuario",    title: "Usuário",         type: "text", },
                { name: "rg",         title: "RG",              type: "text", },
                { name: "email",      title: "Email",           type: "text", },
                {
                    width:60, type: "control", 
                    editButton: false, deleteButton: false,
                    itemTemplate: function(value, item) {
                        
                        var $iconeCopy = $("<i>").attr({class: "far fa-copy"});
                        var $customCopyButton = $("<button>")
                            .attr({class: "btn btn-primary btn-sm popup"})
                            .attr({role: "button"})
                            .attr({title: "Copiar Assinatura"})
                            .attr({id: "btn-copy-" + item.id})
                            .append($iconeCopy)
                            .append('<span class="popuptext" id="copy'+item.id+'">Assinatura Copiada</span>')
                            .click(function(e) {
                                const assinatura = document.createElement('textarea');
                                assinatura.value = item.assinatura;
                                document.body.appendChild(assinatura);
                                assinatura.select();
                                document.execCommand('copy');
                                document.body.removeChild(assinatura);
                                e.stopPropagation();
                                popupCopy(item.id);
                            })
                        ;

                        var $iconePencil = $("<i>").attr({class:"fas fa-pencil-alt"});
                        var $customEditButton = $("<button>")
                            .attr({class: "btn btn-sm bg-gradient-info"})
                            .attr({role: "button"})
                            .attr({title: "Editar Usuário"})
                            .attr({id: "btn-edit-" + item.id})
                            .append($iconePencil)
                        ;
                         
                        //Botão desabilitar Usuário
                        var $iconeTrash = $("<i>").attr({class: "fas fa-user-lock"});
                        var $customDeleteButton = $("<button>")
                            .attr({class: "btn btn-sm bg-gradient-secondary text-warning"})
                            .attr({role: "button"})
                            .attr({title: "Desabilitar Usuário"})
                            .attr({id: "btn-delete-" + item.id})
                            .append($iconeTrash)
                            .click(function() {
                                $("#jsGrid").jsGrid("deleteItem",item);
                            })
                        ;
                            
                        var $iconePermissoes = $("<i>").attr({class: "fas fa-user-cog"});
                        var $customPermissoesButton = $("<button>")
                            .attr({class: "btn btn-sm  bg-success"})
                            .attr({role: "button"})
                            .attr({title: "Permissões do Usuário"})
                            .attr({id: "btn-permissoes-" + item.id})
                            .append($iconePermissoes)
                            .click(function() {
                                let url = "{{route('usuarios.show',":id")}}"
                                url = `${url.replace(':id',item.id)}`
                                url += '?nome='+item.nome+"&usuario="+item.usuario+"&rg="+item.rg+"&email="+item.uemail
                                window.location.href = url;
                                
                            })
                        ;
                                
                        return $("<div>").attr({class: "btn-toolbar"})
                            .append($customEditButton)
                            .append($customDeleteButton)
                            .append($customPermissoesButton)
                            ;
                        }
                    }
            ]
        });

        //JSGRID Usuários Desabilitados
        $("#jsGridInativos").jsGrid({
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
                return "O Usuário \"" + item.nome + "\" será habilitado. Confirmar?";
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
                //Carrega os dados da tabela de Desabilitados
                loadData: function(filter) {
                    filter.inativos = true;
                    return $.ajax({
                        url: "{{route('listarUsuarios')}}",
                        dataType: "json",
                        data:filter,
                    });
                },

                //Habilita o Usuário que está Desablitado
                deleteItem:function(filter){
                    return $.ajax({
                        method:"POST",
                        url: "{{route('habilitarUsuario')}}",
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:filter,
                        success:function(){
                            $("#jsGrid").jsGrid("loadData");
                        }
                    });
                }
            },
            fields: [
                { name: "id",         title: "ID",              type: "number", visible:false,},
                { name: "nome",       title: "Nome",            type: "text", },
                { name: "usuario",    title: "Usuário",         type: "text", },
                { name: "rg",         title: "RG",              type: "text", },
                {
                    width:60, type: "control", 
                    editButton: false, deleteButton: false,
                    itemTemplate: function(value, item) {

                        //Botão de Editar (desabilitado)
                        var $iconePencil = $("<i>").attr({class:"fas fa-pencil-alt"});
                        var $customEditButton = $("<button>")
                            .attr({class: "btn btn-sm bg-gradient-info"})
                            .attr({role: "button"})
                            .attr({title: "Editar"})
                            .attr({id: "btn-edit-" + item.id})
                            .append($iconePencil)
                            .click(function(e) {
                                //Chama tela de edição do Usuário
                                let url = "{{route('usuarios.show',":id")}}"
                                url = `${url.replace(':id',item.id)}`
                                window.location.href = url;
                            })
                        ;
                        
                        //Botão de Habilitar o Usuário
                        var $iconeTrash = $("<i>").attr({class: "fas fa-user-check"});
                        var $customDeleteButton = $("<button>")
                            .attr({class: "btn btn-sm bg-gradient-secondary text-warning"})
                            .attr({role: "button"})
                            .attr({title: "Habilitar Usuário"})
                            .attr({id: "btn-delete-" + item.id})
                            .append($iconeTrash)
                            .click(function() {
                                $("#jsGridInativos").jsGrid("deleteItem",item);
                            })
                        ;
                             
                        //Renderiza os botões
                        return $("<div>").attr({class: "btn-toolbar"})
                            // .append($customEditButton)
                            .append($customDeleteButton)
                            ;
                        }
                    }
            ]
        });
</script>
@stop


