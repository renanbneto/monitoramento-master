var paginaModal = 1;

$.validator.messages.required = 'Campo obrigatório';

function mudarPaginaModal() {

    if (!$("#formModalArmas").valid() && paginaModal == 1) {
        return;
    }

    if (paginaModal == 1) {
        $("#modal_btn_next").addClass("d-none");
        $("#modal_btn_voltar").removeClass("d-none");
        $("#modal_pag_1").addClass("d-none");
        $("#modal_pag_2").removeClass("d-none");
        $("#btn_voltar_modal").addClass("d-none");
        $("#btn_cadastrar_arma").addClass("d-none");
        $("#btn_cadastrar_arma").removeClass("d-none");
        paginaModal = 2;
    } else {
        $("#modal_btn_next").removeClass("d-none");
        $("#modal_btn_voltar").addClass("d-none");
        $("#modal_pag_2").addClass("d-none");
        $("#modal_pag_1").removeClass("d-none");
        $("#btn_voltar_modal").removeClass("d-none");
        $("#btn_cadastrar_arma").addClass("d-none");
        paginaModal = 1;
    }
}

//FUNCTIO PARA VISUALIZAR AS ARMAS CADASTRADAS DO POLICIAL
function visualizarArma(arma) {
    window.open(`/getArmaById?id=${arma.dataset.id}`, '_new');
}

var pagina = 1;
var tipo = "proprietario";

function mudarPagina() {

    var cpf = $("#cpf").val();
    var cpfEncoded = cpf.replace('.', '');
    var cpfEncoded = cpfEncoded.replace('-', '');
    var cpfEncoded = cpfEncoded.replace('.', '');
    var isValid = CPFTest(cpfEncoded);

    if (!$("#formModal").valid() && pagina == 1) {
        return;
    }

    if (!isValid) {
        //TRECHO COMENTADO APÓS REMOÇÃO DE CADASTRO DE USUÁRIO
        /* toastr.error("O CPF digitado é inválido!");
        return; */
    }

    if (pagina == 1) {
        $("#modalBtnNext").addClass("d-none");
        $("#modalBtnVoltar").removeClass("d-none");
        $("#pagina_1").addClass("d-none");
        $("#pagina_2").removeClass("d-none");
        $('#btnCadastrar').removeClass('d-none');
        pagina = 2;
    } else {
        $('#btnCadastrar').addClass('d-none');
        $("#modalBtnNext").removeClass("d-none");
        $("#modalBtnVoltar").addClass("d-none");
        $("#pagina_2").addClass("d-none");
        $("#pagina_1").removeClass("d-none");
        pagina = 1;
    }
}

/* VERIFY IF CPF IS VALID */
function CPFTest(cpf) {
    var Sum;
    var Rest;
    Sum = 0;
    if (cpf == "00000000000") return false;

    for (i = 1; i <= 9; i++) Sum = Sum + parseInt(cpf.substring(i - 1, i)) * (11 - i);
    Rest = (Sum * 10) % 11;

    if ((Rest == 10) || (Rest == 11)) Rest = 0;
    if (Rest != parseInt(cpf.substring(9, 10))) return false;

    Sum = 0;
    for (i = 1; i <= 10; i++) Sum = Sum + parseInt(cpf.substring(i - 1, i)) * (12 - i);
    Rest = (Sum * 10) % 11;

    if ((Rest == 10) || (Rest == 11)) Rest = 0;
    if (Rest != parseInt(cpf.substring(10, 11))) return false;
    return true;
}