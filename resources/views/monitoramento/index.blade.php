@extends('adminlte::page')

@section('title', 'Softwares')

@section('content_header')
    {{ Breadcrumbs::render('cameras') }}
@stop

@section('content')
    @csrf
    <div id="jsGrid"></div>

    <div class="modal fade" id="modalSenhaDb" tabindex="-1" role="dialog" aria-labelledby="modalSenhaDb" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titulomodalSenhaDb">Credenciais banco de dados do software</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomeSoftware">Usuário</label>
                                <input disabled class="form-control" type="text" id="usuarioDb">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="senhaDb">Senha</label>
                                <input disabled class="form-control" type="text" id="senhaDb">
                            </div>
                        </div>
                        <input disabled class="form-control" type="text" id="nomeSoftware" hidden>
                    </div>
                    <button type="button" class="btn btn-template" onclick="gerarSenhaDb()">GERAR</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/jsgrid/dist/jsgrid.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/jsgrid/dist/jsgrid-theme.min.css') }}" />

    <style>
        .jsgrid-row {
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 5px;
            cursor: grab;
        }

        .dragging {
            opacity: 0.5;
        }

        /* Popup container - can be anything you want */
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        /* Popup arrow */
        .popup .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class - hide and show the popup */
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsgrid/dist/jsgrid.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>


    <script>
        function mostraModalDb(item) {

            $("#titulomodalSenhaDb").val('Credenciais banco de dados do software ' + item.nome)
            $("#modalSenhaDb").modal('show')

            $("#nomeSoftware").val(item.nome);
            $("#usuarioDb").val(item.nome.toLowerCase().replace('-', '').replace('_', '').replace('/', ''));
            $("#senhaDb").val(item.dbpass);

        }

        function popupCopy(id) {
            var popup = document.getElementById("copy" + id);
            popup.classList.toggle("show");
        }

        var clients = [{
                "nome": "Otto Clay",
                "id": 25,
                "assinatura": 1,
                "categoria": "asdasdasd",
                "desricao": 'wadsdfalse'
            },


        ];

        var countries = [{
                Name: "",
                Id: 0
            },
            {
                Name: "United States",
                Id: 1
            },
            {
                Name: "Canada",
                Id: 2
            },
            {
                Name: "United Kingdom",
                Id: 3
            }
        ];

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
            redoButtonTooltip: "Refazer Assinatura",

            filtering: true,
            inserting: true,
            editing: true,
            sorting: true,
            paging: true,
            autoload: true,

            confirmDeleting: true,

            onItemUpdated: function(args) {
        // Recarrega os dados na tabela
        $("#jsGrid").jsGrid("loadData");
    },
            deleteConfirm: function(item) {
                return "O Sistema \"" + item.nome + "\" será removido. Confirmar?";
            },

            pagerContainer: null,
            pageIndex: 1,
            pageSize: 100,
            pageButtonCount: 15,
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
                        url: "{{ route('cameras.index') }}",
                        dataType: "json",
                        data: filter,
                    });

                },
                insertItem: function(filter) {
                    return $.ajax({
                        method: "POST",
                        url: "{{ route('cameras.store') }}",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: filter,
                    });
                },

                updateItem: function(filter) {
                    let url = "{{ route('cameras.update', ':id') }}"
                    return $.ajax({
                        method: "PUT",
                        url: `${url.replace(':id',filter.id)}`,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: filter,
                    });
                },
                deleteItem: function(filter) {
                    let url = "{{ route('cameras.destroy', ':id') }}"
                    return $.ajax({
                        method: "DELETE",
                        url: `${url.replace(':id',filter.id)}`,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: filter,

                    });
                }
            },

            onRefreshed: function() {
                Sortable.create(document.querySelector('.jsgrid-grid-body tbody'), {
                    handle: '.jsgrid-cell',
                    animation: 150,
                    onEnd: function(evt) {
                        // Obtenha os dados atuais do jsGrid
                        var data = $("#jsGrid").jsGrid("option", "data");

                        // Se o item foi movido para uma posição diferente
                        if (evt.oldIndex !== evt.newIndex) {
                            // Obtenha o item movido
                            var itemMoved = data[evt.oldIndex];
                            var itemSub = data[evt.newIndex];

                            console.log('Item movido de:', evt.oldIndex, 'para:', evt.newIndex);



                            console.log(itemMoved);
                            console.log(itemSub);

                            // Remove o item da posição antiga
                            //    data.splice(evt.oldIndex, 1);

                            // Insere o item na nova posição
                            //    data.splice(evt.newIndex, 0, itemMoved);

                            // Atualize o jsGrid com os dados modificados
                            //    $("#jsGrid").jsGrid("option", "data", data);
                        }

                    }
                });
            },

            fields: [
                {
                    name: "id",
                    title: "ID",
                    type: "number",
                    visible: false,
                    width: 100,
                },
                {
                    name: "servidor",
                    title: "Servidor",
                    type: "text",
                    width: 100,
                },
                {
                    name: "cidade",
                    title: "Cidade",
                    type: "text",
                    width: 100,
                },
                {
                    name: "ip",
                    title: "IP",
                    type: "text",
                    width: 100,
                },
                {
                    name: "porta",
                    title: "Porta",
                    type: "number",
                    width: 100,
                },
                {
                    name: "camera",
                    title: "Câmera",
                    type: "text",
                    width: 100,
                },
                {
                    name: "local_nome",
                    title: "Endereço/Sentido",
                    type: "text",
                    width: 100,
                },
                {
                    name: "lat",
                    title: "Latitude",
                    type: "text",
                    width: 100,
                },
                {
                    name: "lng",
                    title: "Longitude",
                    type: "text",
                    width: 100,
                },
                {
                    name: "usuario",
                    title: "Usuário",
                    type: "text",
                    width: 100,
                },
                {
                    name: "senha",
                    title: "Senha",
                    type: "text",
                    width: 100,
                },
                {
                    name: "protocolo",
                    title: "Protocolo",
                    type: "text",
                    width: 100,
                },
                {
                    name: "vms",
                    title: "VMS",
                    type: "text",
                    width: 100,
                },
                {
                    name: "vms",
                    title: "VMS",
                    type: "text",
                    width: 100,
                },
                {
                    name: "formato",
                    title: "Formato",
                    type: "text",
                    width: 100,
                },
                {
                    name: "hostname",
                    title: "Hostname",
                    type: "text",
                    width: 100,
                },
                {
                    name: "link",
                    title: "Url",
                    type: "text",
                    editing: true
                },
        {
            name: "ativo",
            type: "select",
            filtering: false,
            items: [
                {},
                { Name: "Sim", Id: true },
                { Name: "Não", Id: false }
            ],
            valueField: "Id",
            textField: "Name",
            selectedIndex: -1,
            editTemplate: function(value) {
                var $select = $("<select>");

                // Adicionando opções ao select
                $.each(this.items, function(index, item) {
                    var $option = $("<option>")
                        .attr("value", item[this.valueField])
                        .text(item[this.textField])
                        .prop("selected", item[this.valueField] === value);

                    $select.append($option);
                }.bind(this));

                // Armazenando a referência ao controle de edição
                this._$editControl = $select;

                return $select;
            },
//            insertValue: function() {
                // Convertendo o valor para boolean antes de enviar
  //              return this._$editControl.val() === "true";
    //        },
            editValue: function() {
                // Convertendo o valor para boolean antes de enviar
                return this._$editControl.val() === "true";
            }
        },
                {
                    name: "descricao",
                    title: "Descrição",
                    type: "textarea",
                },
                {
                    width: 110,
                    type: "control",
                    editButton: false,
                    deleteButton: false,
                    itemTemplate: function(value, item) {


                        var $iconeRedo = $("<i>").attr({
                            class: "fas fa-redo"
                        });
                        var $customRedoButton = $("<button>")
                            .attr({
                                class: "btn btn-warning btn-sm"
                            })
                            .attr({
                                role: "button"
                            })
                            .attr({
                                title: "Refazer Assinatura"
                            })
                            .attr({
                                id: "btn-redo-" + item.id
                            })
                            .append($iconeRedo)
                            .click(function(e) {

                                e.stopPropagation();
                            });

                        var $iconeCopy = $("<i>").attr({
                            class: "far fa-copy"
                        });
                        var $customCopyButton = $("<button>")
                            .attr({
                                class: "btn bg-gradient-lightblue btn-sm popup"
                            })
                            .attr({
                                role: "button"
                            })
                            .attr({
                                title: "Copiar Assinaturas"
                            })
                            .attr({
                                id: "btn-copy-" + item.id
                            })
                            .append($iconeCopy)
                            .append('<span class="popuptext" id="copy' + item.id +
                                '">Assinatura Copiada</span>')
                            .click(function(e) {
                                const assinatura = document.createElement('textarea');
                                assinatura.value = item.assinatura;
                                document.body.appendChild(assinatura);
                                assinatura.select();
                                document.execCommand('copy');
                                document.body.removeChild(assinatura);
                                e.stopPropagation();
                                popupCopy(item.id);

                            });

                        var $iconeDataBase = $("<i>").attr({
                            class: "fas fa-database"
                        });
                        var $customDataBaseButton = $("<button>")
                            .attr({
                                class: "btn btn-template btn-sm"
                            })
                            .attr({
                                role: "button"
                            })
                            .attr({
                                title: "Visualizar Senha DB"
                            })
                            .attr({
                                id: "btn-db-" + item.id
                            })
                            .append($iconeDataBase)
                            .click(function(e) {
                                e.stopPropagation();
                                mostraModalDb(item);
                            });

                        var $iconePencil = $("<i>").attr({
                            class: "fas fa-pencil-alt"
                        });
                        var $customEditButton = $("<button>")
                            .attr({
                                class: "btn btn-sm bg-gradient-info"
                            })
                            .attr({
                                role: "button"
                            })
                            .attr({
                                title: jsGrid.fields.control.prototype.editButtonTooltip
                            })
                            .attr({
                                id: "btn-edit-" + item.id
                            })
                            .append($iconePencil)
                            .click(function(e) {
                                $("#jsGrid").jsGrid("editItem", item);
                            });

                        var $iconeTrash = $("<i>").attr({
                            class: "fas fa-trash"
                        });
                        var $customDeleteButton = $("<button>")
                            .attr({
                                class: "btn bg-gradient-danger btn-sm"
                            })
                            .attr({
                                role: "button"
                            })
                            .attr({
                                title: jsGrid.fields.control.prototype.deleteButtonTooltip
                            })
                            .attr({
                                id: "btn-delete-" + item.id
                            })
                            .append($iconeTrash)
                            .click(function(e) {
                                $("#jsGrid").jsGrid("deleteItem", item);
                            });

                        var $iconePermissao = $("<i>").attr({
                            class: "fas fa-user-cog"
                        });
                        var $customPermissaoButton = $("<button>")
                            .attr({
                                class: "btn btn-sm bg-gradient-secondary text-warning"
                            })
                            .attr({
                                role: "button"
                            })
                            .attr({
                                title: "Editar Permissões"
                            })
                            .attr({
                                id: "btn-permissao-" + item.id
                            })
                            .append($iconePermissao)
                            .click(function(e) {
                                let url = "{{ route('cameras.show', ':id') }}"
                                url = `${url.replace(':id',item.id)}`
                                url += '?nome=' + item.nome + "&descricao=" + item.descricao
                                window.location.href = url;
                            });

                        return $("<div>").attr({
                                class: "btn-toolbar"
                            })
                            .append($customEditButton)
                            .append($customDeleteButton)
                            .append($customRedoButton)
                            .append($customCopyButton)
                            .append($customPermissaoButton)
                            .append($customDataBaseButton);
                    }
                }
            ]
        });
    </script>
@stop
