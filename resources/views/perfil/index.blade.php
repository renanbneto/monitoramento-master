@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
{{ Breadcrumbs::render('perfil') }}

@stop
@section('content')
<div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header bg-gradient-navy">
                <h3 class="card-title">Alterar Senha</h3>
                </div>
                <form id="frmAlterarSenha" method="post" action="{{route('atualizarSenhaPerfil')}}">
                    @csrf
                    <div class="card-body" >
                    {{-- Retorno da API --}}
                    @if(Session::has('frmAlterarSenhaMessage'))
                        <div class="alert alert-{{Session::get('alert-type')}}">
                            <li>{!! \Session::get('frmAlterarSenhaMessage') !!}</li>
                        </div>
                    @endif
                    {{-- Erro para validação Controller --}}
                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}
                    <div class="form-group">
                    {{-- <label for="exampleInputEmail1">Senha Atual</label> --}}
                    <input type="password" class="form-control" id="senhaAtual" name= "senhaAtual" placeholder="Senha Atual" required>
                    </div>
                    <div class="form-group">
                    {{-- <label for="exampleInputPassword1">Nova Senha</label> --}}
                    <input   type="password" class="form-control valida" id="novaSenha" name="novaSenha" placeholder="Nova Senha" required>
                    {{-- pattern="^(?=.*[\d\W])(?=.*[a-z])(?=.*[A-Z]).{8,100}$" --}}
                    </div>
                    <div class="form-group">
                    {{-- <label for="exampleInputPassword1">onfirmar Nova Senha</label> --}}
                    <input   type="password" class="form-control valida" id="reNovaSenha" name="reNovaSenha" placeholder="Confirmar Senha" required>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn bg-gradient-navy text-white" >Salvar</button>
                </div>
            </form>
            </div>
        </div>
        
        <div class="col-md-1">
        </div>
        
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header bg-gradient-navy">
                <h3 class="card-title">Email Alternativo</h3>
                </div>

                <form id="frmAtuallizaEmailAlternativo" method="post" action="{{route('atualizarEmailAlternativo')}}">
                    @csrf
                    <div class="card-body" >
                    {{-- Retorno da API --}}
                    @if(Session::has('frmAtuallizaEmailAlternativoMessage'))
                        <div class="alert alert-{{Session::get('alert-type')}}">
                            <li>{!! \Session::get('frmAtuallizaEmailAlternativoMessage') !!}</li>
                        </div>
                    @endif
                    {{-- Erro para validação Controller --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <input type="email" class="form-control" id="emailAlternativo" name= "emailAlternativo" placeholder="Email Alternativo" value="{{isset(session()->get('user')->emailAlternativo) ? session()->get('user')->emailAlternativo : old('emailAlternativo') }}" required>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn bg-gradient-navy text-white" >Atualizar</button>
                </div>
                </form>

            </div>
        </div>
</div>

@stop

@section('css')

    

<style>

        input.valida:focus:invalid {
            outline: 0;
            border: 1px solid #ee6d69;
        }
        
        input.valida:valid[type="text"], 
        input.valida:focus:valid[type="password"], 
        input.valida:valid[type="email"] {
            outline: 0;
            border: 1px solid #47cc55;
        }
        </style>
@stop

@section('js')

@stop