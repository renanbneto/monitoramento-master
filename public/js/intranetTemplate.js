// Função Para alterar value and trigger chang event
$.fn.changeVal = function(v){
    return this.val(v).trigger("change");
}

//mask para CPF
$.fn.maskCpf = function(el){
    this.mask('000.000.000-00', {
        reverse: true
    });
}

$('#urgencia').on('select2:select', function (e) {
    var selectedData = e.params.data;
    if (selectedData.cor) {
        $('#corSelecionada').val(selectedData.cor);
    }
});

function atualizarPrazo() {
    var urgenciaTexto = $('#urgencia').select2('data')[0]?.diasConfirmacao;

        var novaData = moment().add(urgenciaTexto, 'days').format('YYYY-MM-DD HH:mm');

        var novaData = moment().add(urgenciaTexto, 'days').set({'hour': 23, 'minute': 59}).format('YYYY-MM-DD HH:mm');

        flatpickr("#prazo", {
            locale: {
                firstDayOfWeek: 0,
                weekdays: {
                    shorthand: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                    longhand: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]
                },
                months: {
                    shorthand: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    longhand: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
                }
            },
            defaultDate: moment().set({'hour': 23, 'minute': 59}).format('YYYY-MM-DD HH:mm'),

            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minuteIncrement: 1,
            altInput: true,
            altFormat: "d-m-Y / H:i",
            defaultDate: novaData  ,
            minDate: "today",
        });
}

//mask para RG
$.fn.maskRg = function(el){
    var options = {
        onKeyPress: function(rg, options) {
            var masks = ['0.000.000-0', '00.000.000-0'];
            var mask = (rg.length < 12) ? masks[1] : masks[0];
            this.mask(mask, options);
        }
    };

    this.mask('00.000.000-0', options);
}

//mask CTPS
$.fn.maskCtps = function(el){
    this.mask('00000000', {
        reverse: true
    });
}

//mask cam
$.fn.maskCtps = function(el){
    this.mask('0000000', {
        reverse: true
    });
}

//Mask para placa de carro
$.fn.maskPlaca = function(el){
    this.mask('AAA-9X99',
        {
            'translation': {
                A: {
                    pattern: /[A-Za-z]/,
                },
                9: {pattern: /[0-9]/},
                X: {pattern: /[0-9A-Za-z]/,uppercase : true}
            },
            onKeyPress: function (value, event) {
                event.currentTarget.value = value.toUpperCase();
            }
        }
    );
}

$('#btnCadastrarUrgencia').click(function(e) {
    e.preventDefault();

    var cor = $('#cor').val();
    var descricao = $('#descricaoUrgencia').val();
    var diasConfirmacao = $('#diasConfirmacao').val();

    if(!descricao) {
        Swal.fire({
            title: "Erro!",
            text: "Adicione a urgência!",
            icon: "error"
        });
        return;
    }

    if(!diasConfirmacao) {
        Swal.fire({
            title: "Erro!",
            text: "Adicione os Dias de Confirmacao!",
            icon: "error"
        });
        return;
    }

    $.ajax({
        type: "POST",
        url: "/cadastrarUrgencia",
        data: {
            descricao: descricao,
            cor: cor,
            diasConfirmacao: diasConfirmacao,
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(urgencia) {
            var novaLinha = '<tr>' +
                    '<td>' + urgencia.descricao + '</td>' +
                        '<td>' +
                            '<div style="height: 30px; background-color: ' + urgencia.cor + ';"></div>' +
                        '</td>' +
                        '<td>' + urgencia.diasConfirmacao + '</td>' +
                        '<td>' +
                            "<button class='btn btn-success btnEditarUrgencia' style='margin-right: 5px;' " +
                            "data-id='" + urgencia.id + "' " +
                            "data-dias='" + urgencia.diasConfirmacao + "' " +
                            "data-descricao='" + urgencia.descricao + "' " +
                            "data-cor='" + urgencia.cor + "'>" +
                            "<i class='fa-regular fa-pen-to-square'></i>Editar" +
                            "</button>" +
                            "<button class='btn btn-danger btnExcluirUrgencia' data-id='" + urgencia.id + "'>" +
                            "<i class='fas fa-trash' style='margin-right: 5px;'></i>Excluir" +
                            "</button>" +
                        '</td>' +
                    '</tr>';
            $('#tabelaUrgencia').append(novaLinha);
            $('#descricaoUrgencia').val('');
            $('#diasConfirmacao').val('');
            Swal.fire({
                title: "Sucesso!",
                text: "Urgência criada!",
                icon: "success"
            });
        },
        error: function () {
            Swal.fire({
                title: "Erro!",
                text: "Erro ao cadastrar Urgência!",
                icon: "error"
            });
        }
    });
});


$('#btnSalvarEdicaoUrgencia').click(function () {
    let id = $('#idEditarUrgencia').val();
    let descricao = $('#descricaoEditarUrgencia').val();
    var cor = $('#corEditarUrgencia').val();
    var diasConfirmacao = $('#diasConfirmacaoEditarUrgencia').val();

    if (!cor) {
        alert('Por favor, escolha uma cor!');
        return;
    }

    $.ajax({
        method: "PUT",
        url: "/editarUrgencia/" + id,
        data: {
            descricao: descricao,
            cor: cor,
            diasConfirmacao: diasConfirmacao,
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            $('#modalEditarUrgencia').modal('hide');
            $('#modalEditarTipo').modal('hide');
            Swal.fire({
                title: "Sucesso!",
                text: "Urgência atualizada!",
                icon: "success"
            });

            var row = $('button[data-id="' + id + '"]').closest('tr');

            row.find('td:first').text(descricao);
            row.find('td:nth-child(2) div').css('background-color', cor);
        },
        error: function () {
            Swal.fire({
                title: "ERRO!",
                text: "Preencha todos os campos.",
                icon: "error"
            });
        }
    });

});

// Tipo de notificação
$('#btnCadastrarTipoNotificacao').click(function (e) {
    e.preventDefault();

    $.ajax({
        method: "POST",
        url: "/cadastrarTipoNotificacao",
        data: {
            descricao: $('#descricao').val(),
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (tipoNotificacao) {
            var novaLinha = '<tr>' +
                                '<td>' + tipoNotificacao.descricao + '</td>' +
                                    '<td>' +
                                        "<button class='btn btn-success btnEditarTipoNotificacao' style='margin-right: 5px;' data-id='" + tipoNotificacao.id + "' data-descricao='" + tipoNotificacao.descricao + "'>" +
                                            "<i class='fa-regular fa-pen-to-square'></i>Editar" +
                                        "</button>" +
                                    "<button class='btn btn-danger btnExcluirTipoNotificacao' data-id='" + tipoNotificacao.id + "'>" +
                                            "<i class='fas fa-trash' style='margin-right: 5px;'></i>Excluir" +
                                        "</button>"
                                    '</td>' +
                             '</tr>';
            $('#tabelaTipo').append(novaLinha);
            $('#descricao').val('');
            Swal.fire({
                title: "Sucesso!",
                text: "Tipo de notificação criada!",
                icon: "success"
            });
        },
        error: function () {
            Swal.fire({
                title: "Erro",
                text: "Adicione o tipo de notificação.",
                icon: "error"
            });
        }
    });
});

$('#btnSalvarEdicaoTipoNotificacao').click(function () {
    let id = $('#idEditar').val();
    let descricao = $('#descricaoEditar').val();

    $.ajax({
        method: "PUT",
        url: "/editarTipoNotificacao/" + id,
        data: {
            descricao: descricao
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            $('#modalEditarTipo').modal('hide');

            Swal.fire({
                title: "Sucesso!",
                text: "Tipo de notificação atualizada!",
                icon: "success"
            });

            $('button[data-id="' + id + '"]').closest('tr').find('td:first').text(descricao);
        },
        error: function () {
            Swal.fire({
                title: "ERRO!",
                text: "Adicione o tipo da notificação.",
                icon: "error"
            });
        }
    });
});

$(document).on('click', '.btnExcluirTipoNotificacao', function (e) {
    e.preventDefault();

    var tipoNotificacaoId = $(this).data('id');

    Swal.fire({
        title: "Você tem certeza?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sim, deletar!"
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: '/removerTipoNotificacao/' + tipoNotificacaoId,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        title: "Excluída!",
                        text: "Tipo de notificação excluído.",
                        icon: "success"
                    });
                    $('button[data-id="' + tipoNotificacaoId + '"]').closest('tr').remove();
                },
                error: function (xhr) {
                    Swal.fire({
                        title: "Erro!",
                        text: "Erro ao excluir Tipo de Notificação.",
                        icon: "error"
                    });
                }
            });
        }
      });

});


$(document).on('click', '.btnExcluirUrgencia', function (e) {
    e.preventDefault();

    var urgenciaId = $(this).data('id');

    Swal.fire({
        title: "Você tem certeza?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sim, deletar!"
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: '/removerUrgencia/' + urgenciaId,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('button[data-id="' + urgenciaId + '"]').closest('tr').remove();
                    Swal.fire({
                        title: "Erro!",
                        text: "Urgência excluída.",
                        icon: "success"
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: "Erro!",
                        text: "Erro ao excluir Urgência.",
                        icon: "error"
                    });
                }
            });
        }
      });

});

let distribuicao = [];

let exibeDistribuicao = async function() {

    $('.distribuicoes').html('');

    distribuicao.forEach(function(el){

        let elementos = '';

        //distribuir a tod
        if(el.todos){
            elementos += `Todos usuários receberão a notificação`;
        }

        //distribuir a usuários específicos
        else if(el.user_id) {
            elementos += `O usuáro ${el.user_nome} receberá a notificação`;
        }

        //Distribuir para OPM e Local
        else if(el.opm_id.length > 1 && el.cargo.length){

            if(el.suborinadas)
                elementos += `${el.opm_nome} e subordinadas | Posto/Grad ${el.cargo_nome}`;
            else
            elementos += `${el.opm_nome} | ${el.cargo_nome}`;
        }

        //Distribuir para Posto Grad função
        else if(el.cargo.length > 0){
            elementos += ` Posto/Grad ${el.cargo_nome} | `;
        }

        //Distribuir para OPM e Local
        else if(el.opm_id){

            if(el.suborinadas)
                elementos += `${el.opm_nome} e subordinadas | `;
            else
            elementos += `${el.opm_nome} |`;
        }

        else if(el.local_id) {
            elementos += `Local ${el.local_nome}`;

        }

        //Distribuir para Função
        else if(el.funcao){
            elementos += `Função ${el.funcao_nome} | `;
        }

        if(elementos != "") {
            $('.distribuicoes').append(`
                <tr>
                    <td style="padding-right: 250px; text-align:center">${el.id_temp}</td>
                    <td style="text-align:center">${elementos}</td>
                    <td>
                        <button style="margin-left:254px;" class="btn btn-danger" onclick="removerDistribuicao(${el.id_temp})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            Swal.fire({
                title: "SUCESSO!",
                text: "Destinatário adicionado.",
                icon: "success"
            });
        } else {
            Swal.fire({
                title: "ERRO!",
                text: "Adicione o destinatário da notificação.",
                icon: "error"
            });
            distribuicao.length--;
        }
    });
}
function removerDistribuicao(id){
    distribuicao = distribuicao.filter(el => el.id_temp != id);
    Swal.fire({
        title: "Você tem certeza?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sim, deletar!"
      }).then((result) => {
        if (result.isConfirmed) {
            exibeDistribuicao();
          Swal.fire({
            title: "Excluido!",
            text: "O destinatário foi excluído.",
            icon: "success"
          });
        }
      });
}

let contadorDistribuicoes = 0;

class DistribuicaoNotificacoes {
    constructor(opmId = null, cargoParam = null) {
        this.id_temp = ++contadorDistribuicoes;
        this.todos = $('#todos').prop("checked");
        // Subtrai 3 horas da data atual
        this.data_com_3_horas_a_menos = new Date(new Date().setHours(new Date().getHours() - 3));

        if (opmId) {
            this.opm_id = opmId;
            this.opm_nome = $(`#opm_id option[value="${opmId}"]`).text();
            this.meta4 = $(`#opm_id option[value="${opmId}"]`).data('meta4');
        } else {
            this.opm_id = $("#opm_id").val();
            if (Array.isArray(this.opm_id)) {
                this.opm_nome = this.opm_id.map(id => $(`#opm_id option[value="${id}"]`).text());
            } else {
                this.opm_nome = $(`#opm_id option[value="${this.opm_id}"]`).text();
            }
            this.meta4 = $(`#opm_id option[value="${this.opm_id}"]`).data('meta4');
        }

        this.suborinadas = $('#subordinadas').prop("checked");
        this.local_id = $("#local_id").val();
        this.local_nome = $("#local_id option:selected").text();

        if (cargoParam) {
            this.cargo = [cargoParam];
            this.cargo_nome = $(`#cargo option[value="${cargoParam}"]`).text();
        } else {
            this.cargo = $("#cargo").val();
            this.cargo_nome = $("#cargo").find("option:selected").map(function() {
                return $(this).text();
            }).get();
        }

        this.funcao = $("#funcao").val();
        this.funcao_nome = $("#funcao option:selected").text();

        this.email = $('#email').prop("checked");
        this.chat = $('#chat').prop("checked");
        this.push = $('#push').prop("checked");
        this.distribuido = false;
    }

    exibeDistribuicao = exibeDistribuicao;
}

class DistribuicaoNotificacoesUsuarioEspecifico {

    constructor(user_id, user_nome) {
        this.id_temp = ++contadorDistribuicoes;

        // Subtrai 3 horas da data atual
        this.data_com_3_horas_a_menos = new Date(new Date().setHours(new Date().getHours() - 3));

        this.user_id = user_id;
        this.user_nome = user_nome;

        this.email = $('#email').prop("checked");
        this.chat = $('#chat').prop("checked");
        this.push = $('#push').prop("checked");
        this.distribuido = false;
    }

    exibeDistribuicao = exibeDistribuicao;
}


/*
confirmar leitura Notificações
*/
function confirmarLeituraNotificacao(id,btn){

    $(`.notificacao-${id}.col`).hide()
    $('body').append(`<style>
        .loadingSpinner {
        display: inline-block;
        width: 50px;
        height: 50px;
        border: 3px solid rgba(164, 190, 253, 0.4);
        border-radius: 50%;
        border-top-color: #5c97e3;
        animation: spin 1s ease-in-out infinite;
        -webkit-animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
        to { -webkit-transform: rotate(360deg); }
        }
        @-webkit-keyframes spin {
        to { -webkit-transform: rotate(360deg); }
        }
        </style>`);
    $("#modalNotificacoesBody").prepend(`<div class="loadingSpinner"></div>`);


    $.ajax({
        url:'/servicos/Notificacoes/confirmarLeitura',
        method:'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: 'json',
        data:{
            id:id,
            leituraObrigatoria: $(btn).data('obrigatoria')
        },
        success: function(data){
            $(".loadingSpinner").remove();
            $('span.badge.badge-danger.badge-pill').removeClass('badge-danger').removeClass('badge-pill')

            $(`.notificacao-${id}.col`).remove()
            updateNotification(nLink);

            Swal.fire({
                toast: true,
                position: "top-end",
                timer: 3000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Sucesso!',
                text: "A leitura da notificação foi confirmada!",
            });


            if( $("#modalNotificacoesBody").children('.col').length == 0 ){
                $('.modal').modal('hide')
            }

        },
        error: function(err){
            $(".loadingSpinner").remove();
            console.log(err);
            $(`.notificacao-${id}.col`).show()

            Swal.fire({
                toast: true,
                position: "top-end",
                timer: 3000,
                timerProgressBar: true,
                icon: 'danger',
                title: 'Erro!',
                text: err.responseText,
                icon: "danger"
            });
        },
        finally: function () {
            $(".loadingSpinner").remove();
        }
    });
}

function htmlDecode(input) {
    var doc = new DOMParser().parseFromString(input, "text/html");
    return doc.documentElement.textContent;
}

/*
Exibir Notificações
*/
function exibirNotificacoes(data,el){

    $('#modalNotificacoes').remove();

    $('body').append(`

        <div class="modal fade" id="modalNotificacoes" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Notificações</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body custom-scroll " style="overflow-y: auto; border-radius: 4px;">
                        <div id="modalNotificacoesBody" style="display: flex;
                            flex-flow: column;
                            justify-content: center;
                            align-items: center;
                            width: 100%;
                            gap: 8px;
                            overflow-wrap: break-word;
                            word-wrap: break-word;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    `);


    $('#modalNotificacoes').modal();

    console.log(data);
    Object.values(data).forEach(notificacao => {
        $('#modalNotificacoesBody').append(`
            <div class="notificacao-${notificacao.id} col">
                <div class="notificacao-${notificacao.id} position-relative p-3 bg-gray"
                    style="min-height: 150px;overflow-y: auto; overflow-x: hidden; border-radius: 8px 8px 0px 0px; padding: 10px 17px;">
                    <style>
                        .notificacao-${notificacao.id}::-webkit-scrollbar {
                            width: 8px;
                        }
                        .notificacao-${notificacao.id}::-webkit-scrollbar-track {
                            background: #f1f1f1;
                            border-radius: 8px;
                        }
                        .notificacao-${notificacao.id}::-webkit-scrollbar-thumb {
                            background: #888;
                            border-radius: 8px;
                        }
                        .notificacao-${notificacao.id}::-webkit-scrollbar-thumb:hover {
                            background: #555;
                        }
                    </style>
                    <div class="ribbon-wrapper ribbon-lg" style="">
                        <div class="ribbon" style="background-color: ${notificacao.urgenciaCor};right: 14px;top: 37px;width: 180px;">
                            ${notificacao.tipo}
                        </div>
                    </div>
                    <div style="width:633px; font-size:large;">
                        ${notificacao.titulo} <br>
                    </div>
                    <br>
                    <div class="notificacao-conteudo">
                        <div style="width:677px;" class="conteudo-html">${notificacao.conteudo}</div>
                    </div>
                    </div>
                    <div style="background-color: #6c757d !important; color: white; border-radius: 0 0 10px 10px; padding: 10px; display: flex; justify-content: flex-end; align-items: center">
                    ${notificacao.prazo ? '<small>Prazo para confirmação' + moment(notificacao.prazo).format('DD/MM/yyyy HH:mm') + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + '</small>' : ''}

                        <button class="btn btn-primary"
                            onclick="javascript:confirmarLeituraNotificacao(${notificacao.id}, this, ${el})">
                            Confirmar leitura
                        </button>
                    </div>
            </div><br>
        `);
    });

    $('#modalNotificacoes').on('hidden.bs.modal', function () {
        $.ajax({
            type: "get",
            url: 'servicos/Notificacoes/listarNotificacoes',
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                $('.adminlte-dropdown-content').html(response.dropdown);

                $('#notificationCount').text(response.label);
            },
        });
    });


}

$(document).ready(function(){

    $('#todos').click(function(){

        if($('#todos').prop("checked")){

            $("#funcao").select2("val", 0);
            $("#cargo").select2("val", 0);
            $("#selectuser").select2("val", 0);
            $("#opm_id").select2("val", 0);
            $("#local_id").select2("val", 0);
            $('#subordinadas').prop('checked',false);

        }

    });

    //Focus para class Select2.js
    $(document).on('select2:open', () => {
        document.querySelector('.select2-container--open .select2-search__field').focus();
    });

    //Executa o select2 caso tenha a class .select2
    $('.select2').each(function( index, element ) {

        //Se está dentro do modal cria dropdownparent
        if ($(element).parents('.modal').length != 0) {
            let parentName = $(element).parents('.modal')
            $(element).select2({
                dropdownParent: $(parentName[0]),
                placeholder: "Selecione",
                allowClear: false,
            });
        }else{
            $(element).select2({
                placeholder: "Selecione",
                allowClear: false,
            })
        }
    });

    //Executa o mask para Placa
    $('.maskPlaca').each(function( index, element ) {
        $(element).maskPlaca()
    });

    //Executa o mask para Cpf
    $('.maskCpf').each(function( index, element ) {
        $(element).maskCpf()
    });

    //Executa o mask para Rg
    $('.maskRg').each(function( index, element ) {
        $(element).maskRg()
    });

    //Executa o mask para CTPS
    $('.maskCtps').each(function( index, element ) {
        $(element).maskCtps()
    });

    //Executa o mask para cam
    $('.maskCam').each(function( index, element ) {
        $(element).maskCam()
    });

    $('.select2').on('select2:opening', function (e) {
        $(this).select2("val", 0);
        $('#todos').prop('checked',false);
    });

    $('#btnAdicionar').click(async function(e) {
        e.preventDefault();
    
        const cargos = $('#cargo').val();   
        const opms = $('#opm_id').val();     
    
        if ((!cargos || cargos.length === 0) && (!opms || opms.length === 0)) {
            Swal.fire({
                title: "ERRO!",
                text: "Você deve adicionar uma distribuição.",
                icon: "error"
            });
            return;
        }
    
        if (opms && opms.length > 0) {
            if (cargos && cargos.length > 0) {
                for (const opm of opms) {
                    for (const cargo of cargos) {
                        let dist = new DistribuicaoNotificacoes(opm, cargo);
                        distribuicao.push(dist);
                    }
                }
            } else {
                for (const opm of opms) {
                    let dist = new DistribuicaoNotificacoes(opm, null);
                    distribuicao.push(dist);
                }
            }
        } else {
            for (const cargo of cargos) {
                let dist = new DistribuicaoNotificacoes(null, cargo);
                distribuicao.push(dist);
            }
        }
    
        await exibeDistribuicao();
        
        $('#opm_id').val(null).trigger('change');
        $('#cargo').val(null).trigger('change');
    });

    $('#btnAdicionarUsuarioEspecifico').click(async function(e) {
        e.preventDefault();

        if ($('#selectedUser').val() == null) {
            Swal.fire({
                title: "ERRO!",
                text: "Você deve adicionar um usuário.",
                icon: "error"
            });
        }

        if($('#selectedUser') == null) {
            Swal.fire({
                title: "ERRO!",
                text: "Você deve adicionar uma opção.",
                icon: "error"
            });
        }

        const selectedUsers = $('#selectuser').val();

        for (const userId of selectedUsers) {
            const userNome = $('#selectuser option[value="' + userId + '"]').text();

            const dist = new DistribuicaoNotificacoesUsuarioEspecifico(userId, userNome);
            distribuicao.push(dist);

            await dist.exibeDistribuicao();
        }

        $('#selectuser').val(null).trigger('change');
    });
$('#btnEnviar').click(async function(e) {
    e.preventDefault();

    // Se a opção "todos" estiver selecionada, adiciona a distribuição para todos os usuários
    if ($('#todos').prop("checked")) {
        const dist = new DistribuicaoNotificacoes();
        distribuicao.push(dist);
        await dist.exibeDistribuicao();
        $('#selectuser').select2("val", 0);
        distribuicao.length = 1;
    }

    // Validações dos campos obrigatórios
    if ($('#titulo').val() == "") {
        $('#titulo').focus();
        Swal.fire({
            title: "ERRO!",
            text: "Você deve adicionar um título.",
            icon: "error"
        });
        return;
    }

    if ($("#conteudo").trumbowyg('html') == '' || $("#conteudo").trumbowyg('html') == '<p><br></p>') {
        $('#conteudo').trumbowyg('focus');
        Swal.fire({
            title: "ERRO!",
            text: "Você deve adicionar um conteúdo.",
            icon: "error"
        });
        return;
    }

    if ($('#tipoNotificacao').val() == null) {
        $('#tipoNotificacao').focus();
        Swal.fire({
            title: "ERRO!",
            text: "Você deve adicionar um tipo.",
            icon: "error"
        });
        return;
    }

    if ($('#urgencia').val() == null) {
        $('#urgencia').focus();
        Swal.fire({
            title: "ERRO!",
            text: "Você deve adicionar a urgência!",
            icon: "error"
        });
        return;
    }

    if ($('#prazo').val() == "" || $('#hora').val() == "") {
        $('#prazo').focus();
        Swal.fire({
            title: "ERRO!",
            text: "Você deve adicionar um prazo para confirmação!",
            icon: "error"
        });
        return;
    }

    if (distribuicao.length < 1) {
        Swal.fire({
            title: "ERRO!",
            text: "Você deve adicionar a distribuição desta notificação!",
            icon: "error"
        });
        return;
    }

    const dados = {
        titulo: $('#titulo').val() || 'Sem Título',
        conteudo: $('#conteudo').trumbowyg('html') || 'Sem Conteúdo',
        tipo: $('#tipoNotificacao').val(),
        urgencia: 0,
        prazo: $('#checkbox').is(':checked') ? $('#prazo').val() : null,
        geral: $('#geral').val() || false,
        opmTopo: $('#opmTopo').val() || null,
        cpf: $('#cpfUser').val() || null,
        urgenciaCor: $('#urgencia').select2('data')[0].cor,
        leituraObrigatoria: $('#leituraObrigatoria').prop('checked'),
        distribuicao: distribuicao.map(dist => {
            return {
                todos: dist.todos || false,
                opm_id: dist.opm_id || null,
                suborinadas: dist.suborinadas || false,
                local_id: dist.local_id || null,
                cargo: dist.cargo ? dist.cargo.join(', ') : null,
                funcao: dist.funcao || null,
                user_id: dist.user_id || null,
                email: dist.email || true,
                chat: dist.chat || false,
                push: dist.push || false,
                meta4: dist.meta4 || null,
            };
        })
    };

    Swal.fire({
        title: "Você tem certeza?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sim, enviar!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: 'POST',
                url: '/servicos/Notificacoes/adicionarNotificacao',
                data: dados,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Enviando...',
                        text: 'Isso pode levar alguns minutos...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(data) {
                    Swal.fire({
                        title: "SUCESSO!",
                        text: "Notificação enviada!",
                        icon: "success"
                    });
                    distribuicao = [];
                    $('.distribuicoes').html('');
                },
                error: function(err) {
                    Swal.fire({
                        title: "ERRO!",
                        text: "Erro ao enviar notificação, tente novamente!",
                        icon: "error"
                    });
                }
            });
        }
    });
});

});
