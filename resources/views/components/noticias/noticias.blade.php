{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<div class="card card-outline card-info">
    <div class="card-header">
      <h3 class="card-title">Últimas Notícias</h3>
      <div class="card-tools">
        <span id="qtdeComunicados" class="badge badge-primary">0</span>
      </div>
    </div>
    <div class="card-body">
      <div id="comunicados">

      </div>
    </div>
    <div class="card-footer">
      xxxxx
    </div>
</div>

<style>
  .comunicados{

  }
  .comunicado{
    background-color: cadetblue;
  }
</style>

<script>
      
$(document).ready(function(){
  $.ajax({
    method : 'get',
    url : '{{url(route('comunicados'))}}',
    success: function(data){
        if(typeof data === 'array'){
          $('#qtdeComunicados').html(data.lenght)
          data.forEach((el)=>{
            var com = $("<div>")
              .attr({class: "comunicado"})
              .attr({id: "btn-copy-" + el.id})
              .append(`
              <div>
              ${el.titulo} - ${el.descricao}
              </div>
              `)
              .click(function(e) {
                  alert('abrir comunicado! desenv')
              }).appendTo($('#comunicados'))
              //.append(com)
          });
        }else{
          $('#qtdeComunicados').html('1')
          $("<div>")
              .attr({class: "comunicado"})
              .attr({id: "btn-copy-" + data.id})
              .append(`
              <div>
              ${data.titulo} - ${data.descricao}
              </div>
              `)
              .click(function(e) {
                  alert('abrir comunicado! desenv')
              }).appendTo($('#comunicados'))
        }

    },
    error: function (err){
        alert(err)
    }
  });
});
</script>

