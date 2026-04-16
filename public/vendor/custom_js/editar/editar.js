//FUNÇÃO PARA EDITAR O PROPRIETÁRIO DE ACORDO COM O ID
function editarProprietario(btn){
    window.open(`/getUserById?id=${btn.dataset.id}`, '_new');
}

function editarArma(btn, event){
    window.open(`/getArmaById?id=${btn.dataset.id}`, '_blank');
}

function transferirArma(btn){
    window.open(`/transferirArma?id=${btn.dataset.id}`, '_new');
}