@extends('adminlte::page')

@section('title', 'Monitoramento')

@section('content_header')
<h1>Monitoramento de serviços ( Exibe somente para usuário master ) </h1>
{{ Breadcrumbs::render('monitoramento') }}
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
            flex-flow:column;
            flex-wrap:wrap;
        }
    </style>
@stop

@section('js')
   
    @foreach (config('sistemas') as $item)
        <script>
            $(document).ready(function(){
                $.ajax({
                    method : 'get',
                    url : '{{$item["appUrl"]}}:{{$item["porta"]}}/monitoramento',
                    success: function(data){

                        $('#divSistemas').append(`
                        <div style="width: 300px;">
                        <table style="width: 100%;" class="table table-responsive">
                            <td><a href="{{$item["appUrl"]}}:{{$item["porta"]}}/home">{{$item["titulo"]}}</a></td>
                            <td><button class="btn btn-success" value="ONLINE">ONLINE</button></td>
                        </table>
                            
                        </div>
                        `);
                    },
                    error: function (err){
                        $('#divSistemas').append(`
                        <div style="width: 300px;">
                        <table style="width: 100%;" class="table table-responsive">
                            <td><a href="{{$item["appUrl"]}}:{{$item["porta"]}}/home">{{$item["titulo"]}}</a></td>
                            <td><button class="btn btn-danger" value="OFFLINE">OFFLINE</button></td>
                        </table>
                            
                        </div>
                        `);
                    }
                });
            });
        </script>
    @endforeach
@stop
