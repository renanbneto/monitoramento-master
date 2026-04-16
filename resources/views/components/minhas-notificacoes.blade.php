
@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')
@extends('adminlte::page')

@section('title', 'Minhas Notificações')

@section('content_header')
    {{-- Configurar Breadcrumb em
    app\Http\Controllers\breadcrumbs\BreadCrumbsLocalController.php --}}

@stop

@section('content')
{{-- Padrão para autorização de permissões na view. --}}
@if ($Autorizacao->can(['Administrador']))
    {{-- Código --}}
@endif

<div class="col-md-12">
    <div class="row-md-12">

        <div class="card-header bg-primary">
            <h3 class="text-center p-0 m-0">Minhas Notificações</h3>
        </div>

        <div id="alertaSistemas" class="alert alert-warning alert-dismissible blinking-border" style="display:none;color: #1c4697;background-color: #fff;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle" style="color:#ffe600;"></i> ATENÇÃO, AVISO IMPORTANTE!</h5>
            Você possui notificações obrigatórias não lidas. Para continuar utilizando o sistema, é necessário que leia e confirme a leitura das notificações listadas abaixo. O acesso será bloqueado até que todas as notificações sejam devidamente lidas.
        </div>

        <div class="container mt-3" id="minhasNotificacoes">
        </div>

        <div class="modal fade" id="detalhesModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Detalhes da Notificação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="modalNotificacoesHistorico" style="display: flex;
                            flex-flow: column;
                            justify-content: center;
                            align-items: center;
                            width: 100%;
                            gap: 8px;
                            overflow-wrap: break-word;
                            word-wrap: break-word;">
                        <div>
                            <div class="modalContent position-relative p-3 bg-gray" style="min-height: 150px;overflow-y: auto; width:755px; overflow-x: hidden; border-radius: 8px; padding: 10px 17px;">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon" id="ribbon" style="right: 5px; top: 33px; width: 160px;">
                                        <p style="margin: 0px"><span id="tipo"></span></p>
                                    </div>
                                </div>
                                <div style="width: 656px">
                                    <p ><span style="font-size: large" id="titulo"></span></p>
                                </div>
                                <div class="notificacao-conteudo modal-minhas-notificacoes">
                                    <div style="width:678px;" class="conteudo-html"><p><span id="conteudo"></span></p></div>
                                </div>
                            </div>
                        </div><br>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-2 mt-2">
            <div class="card-header bg-success">
                <h3 class="text-center p-0 m-0">Histório de Notificações </h3>
            </div>
            <div class="card-body">
                <table id="notificacoesHistorico" class="table table-bordered">
                    <thead class="bg-secondary">
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Data de Distribuição</th>
                            <th>Prazo de Confirmação</th>
                            <th>Data de Leitura</th>
                            <th>Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>

.width-tabela {
    max-width: 400px;
}

.vertical-align {
    vertical-align: middle !important;
}

.modal-minhas-notificacoes {
    word-wrap: break-word; 
    overflow-wrap: break-word; 
    white-space: normal; 
    height: auto;
    width: 1007px !important;
}

.btn:focus, .btn.focus {
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
}

.btn-primary:focus, .btn-primary.focus {
    color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
    box-shadow: 0 0 0 0 rgba(38, 143, 255, 0.5);
}


    .blinking-border {
        border: 4px solid #ffe600;
        animation: blink 1s 8;
    }

    @keyframes blink {
        0% {
        border-color: #ffe600;
        }
        50% {
        border-color: transparent;
        }
        100% {
        border-color: #ffe600;
        }
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.0/js/bootstrap.bundle.min.js"></script>
<!-- Inclua o arquivo intranetTemplate.js -->
<script src="public/js/intranetTemplate.js"></script>

<script>
    $().ready(() => {

        $.ajax({
            type: "get",
            url: 'servicos/Notificacoes/listarNotificacoes',
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                let container = $('#minhasNotificacoes');
                console.log(data.notificacoes.length < 1);
                if (data.notificacoes.length < 1) {
                    $('#minhasNotificacoes').append(`
                        <div class="semNotificacoes" style="display: flex; justify-content: center; align-items: center; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                            <p style="font-size: 16px; color: #6c757d; margin: 0;">Não há notificações pendentes</p>
                        </div>
                    `);
                }

                data.notificacoes.forEach(function(notificacao) {


                    if(notificacao.leituraObrigatoria){
                        $('#minhasNotificacoes').prepend(`

                            <div style="padding-bottom: 17px; border-radius: 10px;" class="notificacao-${notificacao.id} col bg-danger">
                                ${notificacao.leituraObrigatoria ? `<div style="font-size: large"; class="bg-danger text-center">ESTA NOTIFICAÇÃO É DE LEITURA OBRIGATÓRIA</div>`:``}
                                <div class="notificacao-${notificacao.id} position-relative p-3 bg-gray"
                                    style="min-height: 150px;overflow-y: auto; overflow-x: hidden; border-radius: 8px 8px 0px 0px; padding: 10px 17px;">
                                    <div class="ribbon-wrapper ribbon-lg" style="">
                                        <div class="ribbon" style="background-color: ${notificacao.urgenciaCor}; right: 14px; top: 37px; width: 180px;">
                                            ${notificacao.tipo}
                                        </div>
                                    </div>
                                    <div style="max-width:983px; font-size: large;" class="modal-minhas-notificacoes">
                                        ${notificacao.titulo} 
                                    </div>
                                    <br>
                                    <div class="notificacao-conteudo modal-minhas-notificacoes">
                                        <div" class="conteudo-html">${notificacao.conteudo}</div>
                                    </div>
                                    <div style="background-color: #6c757d !important; color: white; border-radius: 0 0 10px 10px; padding: 10px; display: flex; justify-content: center; align-items: center; flex-direction: column">
                                        ${notificacao.prazo ? '<small>Prazo para confirmação ' + moment(notificacao.prazo).format('DD/MM/yyyy HH:mm') + '</small>' : ''}
                                        <button class="btn btn-primary" data-obrigatoria="true" onclick="javascript:confirmarLeituraNotificacao(${notificacao.id}, this)">
                                                Confirmar leitura
                                        </button>
                                    </div>
                                </div>
                            </div><br>
                            `);
                    }else{

                        $('#minhasNotificacoes').append(`

                            <div class="notificacao-${notificacao.id} col">
                                ${notificacao.leituraObrigatoria ? `<div class="bg-danger">ESTA NOTIFICAÇÃO É DE LEITURA OBRIGATÓRIA</div>`:``}
                                <div class="notificacao-${notificacao.id} position-relative p-3 bg-gray"
                                    style="min-height: 150px; overflow-y: auto; overflow-x: hidden; border-radius: 8px 8px 0px 0px; padding: 10px 17px;">
                                    <div class="ribbon-wrapper ribbon-lg" style="">
                                        <div class="ribbon" style="background-color: ${notificacao.urgenciaCor}; right: 14px; top: 37px; width: 180px;">
                                            ${notificacao.tipo}
                                        </div>
                                    </div>
                                    <div style="max-width:983px; font-size: large;" class="modal-minhas-notificacoes">
                                        ${notificacao.titulo} 
                                    </div>
                                    <br>
                                    <div class="notificacao-conteudo modal-minhas-notificacoes">
                                        <div" class="conteudo-html">${notificacao.conteudo}</div>
                                    </div>
                                    <div style="background-color: #6c757d !important; color: white; border-radius: 0 0 10px 10px; padding: 10px; display: flex; justify-content: center; align-items: center; flex-direction: column">
                                        ${notificacao.prazo ? '<small>Prazo para confirmação ' + moment(notificacao.prazo).format('DD/MM/yyyy HH:mm') + '</small>' : ''}
                                        <button class="btn btn-primary text-center" onclick="javascript:confirmarLeituraNotificacao(${notificacao.id}, this)">
                                                Confirmar leitura
                                        </button>
                                    </div>
                                </div>
                            </div><br>
                        `);
                    }
                });
            }

        });

        @if(session()->get('user') && session()->get('user')->notificacao_obrigatoria > 0)
            setTimeout(() => {
                $( "#alertaSistemas:hidden" ).fadeIn( "slow" );
            },500);
        @endif

        $('#notificacoesHistorico').DataTable({
            language: {
                processing: "Processando...",
                search: "Buscar:",
                lengthMenu: "Exibir _MENU_ registros por página",
                info: "",
                infoEmpty: "Nenhum registro disponível",
                infoFiltered: "(filtrado de _MAX_ registros no total)",
                loadingRecords: "Carregando...",
                zeroRecords: "Nenhum registro encontrado",
                emptyTable: "Nenhum dado disponível na tabela",
                aria: {
                    sortAscending: ": ativar para ordenar a coluna de forma ascendente",
                    sortDescending: ": ativar para ordenar a coluna de forma descendente"
                },
                paginate: {
                    first: "Primeira",
                    previous: "Anterior",
                    next: "Próxima",
                    last: "Última"
                }
            },
            columnDefs: [
                { targets: 0, className: 'text-center width-tabela vertical-align' },
                {targets: 1, className: 'text-center vertical-align'},
                {targets: 2, className: 'text-center vertical-align'},
                {targets: 3, className: 'text-center vertical-align'},
                {targets: 4, className: 'text-center vertical-align'},
                {targets: 5, className: 'text-center vertical-align'}
            ],
            ordering: false,
            ajax: '{{ route('listarNotificacoesHistorico_Notificacoes') }}',
            columns: [
                { data: 'distribuicao.notificacao.titulo', name: 'distribuicao.notificacao.titulo' },
                { data: 'distribuicao.notificacao.tipo', name: 'distribuicao.notificacao.tipo' },
                {
                    data: 'distribuicao.notificacao.data',
                    name: 'distribuicao.notificacao.data',
                    render: function(data) {
                        return data ? moment(data).format('DD/MM/YYYY [às] HH:mm') : '';
                    }
                },
                {
                    data: 'distribuicao.notificacao.prazo',
                    name: 'distribuicao.notificacao.prazo',
                    render: function(data) {
                        return data ? moment(data).format('DD/MM/YYYY [às] HH:mm') : 'Sem prazo para confirmação';
                    }
                },
                {
                    data: 'data_confirmacao',
                    name: 'data_confirmacao',
                    render: function(data) {
                        if(data == '1970-01-01 00:00:00') {
                            return '<div class="bg-danger rounded">Não lida no prazo</div>';
                        }
                        return moment(data).format('DD/MM/YYYY [às] HH:mm');
                    }
                },
                { data: 'Detalhes', name: 'Detalhes' },
            ],
        });


        $(document).on('click', '#botaoDetalhes', function () {
            let rowData = $('#notificacoesHistorico').DataTable().row($(this).parents('tr')).data();

            $('#titulo').text(rowData.distribuicao.notificacao.titulo);
            $('#tipo').text(rowData.distribuicao.notificacao.tipo);
            $('#conteudo').html(
                $('<div>').html(rowData.distribuicao.notificacao.conteudo).text()
            );
            $('#prazo').text(rowData.distribuicao.notificacao.prazo);
            $('#ribbon').css('background-color', rowData.distribuicao.notificacao.urgenciaCor);
            $('#dataEnvio').text(rowData.distribuicao.notificacao.data);
            $('#dataConfirmacao').text(rowData.data_confirmacao);

            $('#detalhesModal').modal('show');
        });


    });
</script>

@stop
