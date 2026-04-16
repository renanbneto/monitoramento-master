@extends('adminlte::page')

@section('title', 'Sistemas')

@section('content_header')
<div class="input-group shadow-sm d-flex flex-row flex-nowrap col-6" style="float:left;padding:0px;">
    <span class="input-group-text bg-primary text-white">
        <i class="fas fa-search"></i>
    </span>
    <input type="text" class="form-control" id="filtroSistemas" placeholder="Filtrar sistemas...">
</div>
{{ Breadcrumbs::render('sistemas') }}

@stop

@section('content')

    @if(session('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
    @endif
    <div id="divSistemas">
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .divSistema{
            min-width: 190px;
            /* height: 100px; */
            /* background-color: #0c84ff; */

        }
        .divSistema img{
            max-width: 135px;
            height:auto;
        }
        #divSistemas{
            display:flex;
            flex-flow:row;
            flex-wrap:wrap;
            padding-left: 45px;
            padding-right: 45px;
        }


        .sistema-card {
  width: 140px;
  height: 140px;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 1px 10px 1px rgb(8 1 255 / 16%);
  text-align: center;
  overflow: hidden;
  margin: 8px;
  display: inline-block;
  transition: transform 0.2s ease;
}

.sistema-card a {
  text-decoration: none;
  color: #1f1f1f;
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: space-between;
  padding: 8px;
}

.sistema-thumb img {
  width: 100%;
  height: 64px;
  object-fit: fill;
  margin: 0 auto;
  margin-top: 8px;
}

.sistema-title {
  font-size: 14px;
  font-weight: 500;
  margin-top: 10px;
  text-align: center;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
    
    </style>
@stop

@section('js')
    <script>

        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt){
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        const addSistema = (sistema,target) => {


            let url = sistema.porta ? sistema.appUrl+':'+sistema.porta : sistema.appUrl

            url += sistema.authRoute && sistema.authRoute !== "" ? sistema.authRoute+'?token='+sistema.token : '';


            $('#divSistemas').append(`
            <div class="sistema-card" data-titulo="${sistema.titulo}">
                <a target="${target}" title="${sistema.descricao}" href="${url}" data-titulo="${sistema.titulo}">
                <div class="sistema-thumb" data-titulo="${sistema.titulo}">
                    <img data-titulo="${sistema.titulo}" src="${sistema.img}" alt="Sem Imagem" onerror="this.onerror=null; this.src='https://dummyimage.com/16:9x200/8ba5c4/ffffff.png&text=${toTitleCase(sistema.titulo)}'" >
                </div>
                <div class="sistema-title" data-titulo="${sistema.titulo}">
                    ${toTitleCase(sistema.titulo)}
                </div>
                </a>
            </div>
            `);
        }

        setTimeout(()=>{
            document.location.reload(true);
        },1000*60*5)

        $(document).ready(function(){

            $("#filtroSistemas").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#divSistemas *").filter(function() {
                    $(this).toggle($(this).data('titulo').toLowerCase().indexOf(value) > -1)
                });
            });

            $.ajax({
                method : 'get',
                url : '{{route('userSistemas')}}',
                success: function(data){
                    data.forEach((el)=>{
			addSistema(el,el.integrado ? '_self':'_blank');
                    });

                },
                error: function (err){
                    toastr.error('Houve um problema ao carregar os sistemas do usuário. Tente atualizar a página! [CTRL]+F5')
                }
            });


        })
    </script>
@stop
