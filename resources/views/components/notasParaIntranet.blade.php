{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<div class="card card-outline card-primary ">
  <div class="card-header">
      <h3 class="card-title">Notas para a Intranet</h3>
      <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
      </div>
  </div>
  <div class="card-body">

    <div class="row">
      <div class="col-md-12">
      
        <div style="display: flex;flex-flow: column;"> 
          <a style="width: max-content;align-self: flex-end;margin-right: 20px;" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" class="advanced btn btn-light"> <i class="fa fa-search"></i> Busca de notas <i class="fa fa-angle-down"></i> </a>
          <div class="collapse" id="collapseExample">
              <div class="card card-body" style="margin-right: 20px; margin-left: 20px;">
                  <div class="row">
                      <div class="col-md-3"> <input id="buscaTitulo" type="text" placeholder="Busca por titulo" class="form-control"> </div>
                      <div class="col-md-3 mt-1"> <input id="buscaNumero" type="number" class="form-control" placeholder="Por número"> </div>
                      <div class="col-md-2 mt-1"> <input id="buscaAno" type="number" class="form-control" placeholder="Por ano"> </div>
                      <div class="col-md-2 mt-1"> <input onclick="javascript:buscarNotas()" type="button" class="btn btn-success form-control" placeholder="Buscar" value="Buscar"> </div>
                      <div class="col-md-2 mt-1"> <input onclick="javascript:limparBusca()" type="button" class="btn btn-info form-control" placeholder="Limpar" value="Limpar"> </div>
                  </div>
              </div>
          </div>
        </div>
  
          <div class="text-center" id="cardLoading">
              <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
              </div>
          </div>
  
          <div class="timeline" style="display: none;"></div>
  
          <div class="row" style="justify-content: center;align-items: center;display:none;" id="carregarMais">
            <button onclick="javascript:carregarMais()" class="btn mb-10"><i class="fas fa-chevron-down"></i> &nbsp;&nbsp;Mais notas</button>
          </div>
  
          <br><br>
      </div>
  </div>
    {{-- <div id="notasIntranet">
     
    </div> --}}
  </div>
</div>

<style>
  .comunicados{

  }
  .comunicado{
    background-color: cadetblue;
  }

  
  .timeline > .time-label > span {
  border-radius: 4px;
  background-color: #fff;
  display: inline-block;
  font-weight: 600;
  padding: 5px;
  margin-left: 20px !important;
}

  .timeline-footer{
        display: flex;
        justify-content: flex-end;
    }
    
    .advanced {
    text-decoration: none;
    font-size: 15px;
    font-weight: 500
}

.btn-secondary,
.btn-secondary:focus,
.btn-secondary:active {
    color: #fff;
    background-color: #00838f !important;
    border-color: #00838f !important;
    box-shadow: none
}

.advanced {
    color: #00838f !important
}

.form-control:focus {
    box-shadow: none;
    border: 1px solid #00838f
}
</style>

<script src="{{ asset('vendor/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('vendor/moment/min/locales.js') }}"></script>

<script>
      
      const categorias = {
    'Informacao' : 'fas fa-info-circle bg-primary',
    'Determinacao' : 'fas fa-gavel bg-danger',
    'Convocacao' : 'fas fa-users bg-info',
    'Concursos' : 'fas fa-graduation-cap bg-success'
  }

  const colors = {
     0 : "bg-primary",
     1 : "bg-secondary",
     2 : "bg-success",
     3 : "bg-danger",
     4 : "bg-warning",
     5 : "bg-info",
     6 : "bg-dark",
     7 : "bg-primary",
     8 : "bg-secondary",
     9 : "bg-success",
     10 : "bg-danger",
     11 : "bg-warning",
     12 : "bg-info",
     13 : "bg-dark",
     15 : "bg-primary",
     16 : "bg-secondary",
     17 : "bg-success",
     18 : "bg-danger",
     19 : "bg-warning",
     20 : "bg-info",
     21 : "bg-dark",
     22 : "bg-primary",
     23 : "bg-secondary",
     24 : "bg-success",
     25 : "bg-danger",
     26 : "bg-warning",
     27 : "bg-info",
     28 : "bg-dark", 
     29 : "bg-success",
     30 : "bg-danger",
  }
  
  let next_page_url = null

function limparBusca(){
  $("#buscaTitulo").val('')
  $("#buscaNumero").val('')
  $("#buscaAno").val('')
  $(".advanced").click()
  next_page_url = "{{route('listarNotas_Notas')}}?page=1"
  $('.timeline').html('');
  carregarMais();
}

function buscarNotas(){
  /* validar filtros */
  next_page_url = "{{route('listarNotas_Notas')}}?page=1"
  next_page_url = $("#buscaTitulo").val() ? next_page_url+"&filter[titulo]="+$("#buscaTitulo").val() : next_page_url
  next_page_url = $("#buscaNumero").val() ? next_page_url+"&filter[numero]="+$("#buscaNumero").val() : next_page_url
  next_page_url = $("#buscaAno").val() ? next_page_url+"&filter[ano]="+$("#buscaAno").val() : next_page_url
  // Limpar timeline
  $('.timeline').html('');
  carregarMais();
}

function carregarMais(){
  
  $('#carregarMais').html(`
  <div class="spinner-grow spinner-grow-sm" role="status">
    <span class="sr-only">Loading...</span>
  </div>&nbsp;&nbsp;Carregando
  `)

  // se tiver mais paginas <a onclick="javascript:carregarMais()" class="btn stretched-link"><i class="fas fa-chevron-down"></i>&nbsp;&nbsp;Mais notas</a>
  //senão nada
  $.ajax({
    url : next_page_url,
    method : "GET",
    success : function(result){

      $("#cardLoading").hide();
      $(".timeline").show();

      console.log(result)

      if(result.next_page_url){
        //mostrar link mais
        next_page_url = result.next_page_url
        $('#carregarMais').html(`
        <button onclick="javascript:carregarMais()" class="btn mb-10"><i class="fas fa-chevron-down"></i> &nbsp;&nbsp;Mais notas</button>
        `);

        $('#carregarMais').show()
      }else{
        next_page_url = null
        $('#carregarMais').html(``)
        $('#carregarMais').hide()
      }        


      result.data.forEach(element => {

        

        dia = moment(element.data_publicacao).format('yyyyMMDD') // defini o dia do box
        dataLabel = moment(element.data_publicacao).format('DD MMMM. yyyy') // defini o dia do box
        
        //verificar se o box do dia não existe criar e adicionar label
        if(!$('#boxData'+dia).length){
          //criar o box do dia com a label
          box = $('<div>teste</div>')
                .attr('id','boxData'+dia)

          $('.timeline').append(`
          
          <div id="boxData${dia}" class="time-label">
            <span style="margin-left: 20px !important;" class="${colors[Math.floor(Math.random() * (30 - 0 + 1) + 0)]}">${dataLabel}</span>
          </div>

          `)

        }
        
        // Box já existe adicionar a Nota no box
        
        if(!$('.boxDataNota'+dia).length){ // valida se nenhuma nota foi adicionada ainda
          $('#boxData'+dia).after(`
        
            <div class="boxDataNota${dia}">
                <i class="${categorias[""+element.categoria] ? categorias[""+element.categoria] : "fas fa-info-circle bg-dark"}"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> ${moment(element.data_publicacao).format('HH:mm')}</span>
                  <h3 class="timeline-header"><a href="#">${element.local}</a> - ${element.titulo}</h3>

                  <div class="timeline-body">
                    ${element.conteudo}
                  </div>
                  <div class="timeline-footer">
                    <a href="{{url('/viewExibirNota')}}/${element.id}" class="btn btn-primary btn-sm">Ler nota</a>
                  </div>
                </div>
            </div>
          
          `);
        }else{
          $($('.boxDataNota'+dia)[$('.boxDataNota'+dia).length-1]).after(`
        
              <div class="boxDataNota${dia}">
                  <i class="${categorias[""+element.categoria] ? categorias[""+element.categoria] : "fas fa-info-circle bg-dark"}"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i> ${moment(element.data_publicacao).format('HH:mm')}</span>
                    <h3 class="timeline-header"><a href="#">${element.local}</a> - ${element.titulo}</h3>

                    <div class="timeline-body">
                      ${element.conteudo}
                    </div>
                    <div class="timeline-footer">
                      <a href="{{url('/viewExibirNota')}}/${element.id}" class="btn btn-primary btn-sm">Ler nota</a>
                    </div>
                  </div>
              </div>
            
            `);
        }


        //se ainda não foi adicionada nenhum nota no box usar after se não usar after ultima nota do box  $('.nota')[$('.nota').length-1] 

        

      });
    },
    error : function(err){

    }
  });
  
}

moment.locale('pt-br');

var pagina = 1;
$(document).ready(function(){
  
  $.ajax({
    url : "{{route('listarNotas_Notas')}}?page="+pagina,
    method : "GET",
    success : function(result){

      $("#cardLoading").hide();
      $(".timeline").show();

      console.log(result)

      if(result.next_page_url){
        //mostrar link mais
        next_page_url = result.next_page_url
        $('#carregarMais').show()
      }            
          
      result.data.forEach(element => {

        

        dia = moment(element.data_publicacao).format('yyyyMMDD') // defini o dia do box
        dataLabel = moment(element.data_publicacao).format('DD MMMM. yyyy') // defini o dia do box
        
        //verificar se o box do dia não existe criar e adicionar label
        if(!$('#boxData'+dia).length){
          //criar o box do dia com a label
          box = $('<div>teste</div>')
                .attr('id','boxData'+dia)

          $('.timeline').append(`
          
          <div id="boxData${dia}" class="time-label">
            <span class="${colors[Math.floor(Math.random() * (30 - 0 + 1) + 0)]}">${dataLabel}</span>
          </div>

          `)

        }
        
        // Box já existe adicionar a Nota no box
        
        if(!$('.boxDataNota'+dia).length){ // valida se nenhuma nota foi adicionada ainda
          $('#boxData'+dia).after(`
        
            <div class="boxDataNota${dia}">
                <i class="${categorias[""+element.categoria] ? categorias[""+element.categoria] : "fas fa-info-circle bg-dark"}"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> ${moment(element.data_publicacao).format('HH:mm')}</span>
                  <h3 class="timeline-header"><a href="#">${element.local}</a> - ${element.titulo}</h3>

                  <div class="timeline-body">
                    ${element.conteudo}
                  </div>
                  <div class="timeline-footer">
                    <a href="{{url('/viewExibirNota')}}/${element.id}" class="btn btn-primary btn-sm">Ler nota</a>
                  </div>
                </div>
            </div>
          
          `);
        }else{
          $($('.boxDataNota'+dia)[$('.boxDataNota'+dia).length-1]).after(`
        
              <div class="boxDataNota${dia}">
                  <i class="${categorias[""+element.categoria] ? categorias[""+element.categoria] : "fas fa-info-circle bg-dark"}"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i> ${moment(element.data_publicacao).format('HH:mm')}</span>
                    <h3 class="timeline-header"><a href="#">${element.local}</a> - ${element.titulo}</h3>

                    <div class="timeline-body">
                      ${element.conteudo}
                    </div>
                    <div class="timeline-footer">
                      <a href="{{url('/viewExibirNota')}}/${element.id}" class="btn btn-primary btn-sm">Ler nota</a>
                    </div>
                  </div>
              </div>
            
            `);
        }


        //se ainda não foi adicionada nenhum nota no box usar after se não usar after ultima nota do box  $('.nota')[$('.nota').length-1] 

        

      });
    },
    error : function(err){

    }
  });

})


</script>

