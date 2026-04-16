<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Intranet PMPR | Entrar</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        .aLinkWP :hover{
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center" style="display: flex; justify-content: center; align-items: center;">
            <img style="height: 60px;margin-right: 10px;" src="{{asset('images/logo_pm_89x113.png')}}">
            <a href="/login" class="h1"><b>Intranet</b>&nbsp;PMPR</a>
        </div>

        @if(isset($mensagem) && !empty($mensagem))
            
            @php
                $mensagem = str_replace(":linktext","Olá necessito de suporte no sistema ".env('APP_NAME'),$mensagem);
            @endphp

            <div class="alert alert-danger">{!! $mensagem !!}</div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div><br />
        @endif


        @if (isset(($otpRequerido)) && $otpRequerido)

        
        @if (empty($otpData["telefone"]) && empty($otpData["email"]))
                <div class="alert-box">
                    <div class="alert-icon">🚫 Acesso Negado</div>

                    <div class="alert-content">
                        
                        <p>
                        Por questões de <strong>segurança</strong> e para garantir a proteção dos sistemas institucionais,
                        é obrigatório que todos os usuários tenham <strong>e‑mail</strong> e <strong>telefone</strong> atualizados na base
                        de dados, pois essas informações são fundamentais para o funcionamento da autenticação segura.
                        </p>

                        <p>
                        Seu acesso <strong>não foi autorizado</strong> porque identificamos que seus dados de contato estão
                        incompletos ou desatualizados.
                        </p>

                        <p>
                        Para regularizar sua situação e retomar o acesso:
                        <br>
                        ➡️ Procure a equipe da <strong>P1</strong> ou o <strong>Gestor de TI</strong> da sua unidade.
                        </p>

                        <p>
                        Eles poderão atualizar seus dados e liberar seu acesso com segurança.<br>
                        Contamos com sua compreensão e colaboração para manter nossos sistemas seguros para todos.
                        </p>
                    </div>
                </div>
            @else

            <div class="alert alert-info">
                <p><i class="fas fa-exclamation-triangle"></i> <b>Atenção!</b> Insira o código de autenticação encaminhado para:</p>

                @if (isset($otpData['email']) && !empty($otpData['email']))
                    <p><strong>Email:</strong> {{$otpData['email']}}</p>
                @endif

                @if (isset($otpData['telefone']) && !empty($otpData['telefone']))
                    {{-- <p><strong>WhatsApp/SMS:</strong> {{$otpData['telefone']}}</p> --}}
                @endif

            </div>
            <div class="card-body">
                @if (isset($otpData['success']) && $otpData['success'])
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <b>Sucesso!</b> {{ $otpData['msg'] }}
                    </div>
                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <b>Atenção!</b> {{ $otpData['msg'] }}
                    </div>
                @endif
                <p>{{ $otpData['msg'] }}</p>
                <form method="post" >
                    @csrf
                    <input type="hidden" name="usuario" value="{{ $usuario }}">
                    <input type="hidden" name="senha" value="{{ $senha }}">
                    <input type="hidden" name="otpId" value="{{ json_encode($otpData['id']) }}">
                    <div class="input-group mb-3 justify-content-center" style="gap: 5px;">
                        @for ($i = 0; $i < 6; $i++)
                            <input 
                                name="codigo_otp[]" 
                                type="text" 
                                class="form-control text-center otp-input" 
                                maxlength="1" 
                                inputmode="numeric" 
                                pattern="[0-9]*" 
                                style="width: 40px; font-size: 1.5rem; padding: 0.5rem;"
                                autocomplete="one-time-code"
                                required
                            >
                        @endfor
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>             
                @php 
                
                $dcaptcha = "";
                $vcaptcha = "";
                if (!Session::has('tentativas_limite')) {
                    Session::put('tentativas_limite',config('captcha')['tentativas']);
                }

                if(Session::get('tentativas') < Session::get('tentativas_limite')){
                    $dcaptcha = "d-none";
                    $vcaptcha = "1";
                }
                @endphp

                    <div class="captcha form-group mt-4 mb-4 {{$dcaptcha}}">
                        <div class="captcha">
                            <span>{!! captcha_img() !!}</span>
                            <button type="button" class="btn btn-danger" class="reload" id="reload">
                                &#x21bb;
                            </button>
                        </div>
                    </div>
        
                    <div class="captcha form-group mb-4 {{$dcaptcha}}">
                        <input id="captcha" type="text" class="form-control" value="{{$vcaptcha}}" placeholder="Insira a sequência de letras acima" name="captcha">
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const inputs = document.querySelectorAll('.otp-input');
                            inputs.forEach((input, idx) => {
                                input.addEventListener('input', function(e) {
                                    if (this.value.length === 1 && idx < inputs.length - 1) {
                                        inputs[idx + 1].focus();
                                    }
                                    if (this.value.length === 1 && idx === inputs.length - 1) {
                                        const loader = document.createElement('div');
                                        loader.style.position = 'fixed';
                                        loader.style.top = 0;
                                        loader.style.left = 0;
                                        loader.style.width = '100vw';
                                        loader.style.height = '100vh';
                                        loader.style.background = 'rgba(0,0,0,0.4)';
                                        loader.style.display = 'flex';
                                        loader.style.alignItems = 'center';
                                        loader.style.justifyContent = 'center';
                                        loader.style.zIndex = 9999;
                                        loader.innerHTML = `<div style="background: #fff; padding: 2rem 2.5rem; border-radius: 1rem; box-shadow: 0 2px 16px rgba(0,0,0,0.15); display: flex; flex-direction: column; align-items: center;">
                                            <div style="border: 4px solid #e0e0e0; border-top: 4px solid #007bff; border-radius: 50%; width: 48px; height: 48px; animation: spin 1s linear infinite;"></div>
                                            <span style="margin-top: 1rem; color: #333; font-size: 1.1rem;">Validando código...</span>
                                        </div>
                                        <style>
                                        @keyframes spin { 100% { transform: rotate(360deg); } }
                                        </style>`;
                                        document.body.appendChild(loader);
                                        this.form && this.form.submit();
                                    }
                                });
                                input.addEventListener('keydown', function(e) {
                                    if (e.key === 'Backspace' && !this.value && idx > 0) {
                                        inputs[idx - 1].focus();
                                    }
                                });
                            });
                            // Optional: focus first input on load
                            if(inputs.length) inputs[0].focus();
                        });
                    </script>
                    <button type="submit" class="btn btn-primary btn-block">Validar Código</button>
                </form>
                <div id="reenvio_otp" class="mt-3 d-none">Reenviar código em <span id="timer"></span></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let timer = 120;
                        let point = 0;
                        const points = ['.', '..', '...'];
                        const timerSpan = document.getElementById('timer');
                        const reenvioDiv = document.getElementById('reenvio_otp');
                        let interval = setInterval(function() {
                            reenvioDiv.classList.remove('d-none');
                            timer--;
                            timerSpan.textContent = timer+'s'+points[point];
                            point++;
                            if(point >= 3)
                                point = 0;
                            if (timer <= 0) {
                                clearInterval(interval);
                                reenvioDiv.innerHTML = `<a data-otpId="{{ $otpData['id'] }}" id="reenviarCodigo" class="mt-3 modern-link">Reenviar código</a>`;
                                document.getElementById('reenviarCodigo').addEventListener('click', function(e) {
                                    e.preventDefault();

                                    
                                    
                                    //adicionar no form um campo de resend
                                    const form = document.querySelector('form');
                                    const resendInput = document.createElement('input');
                                    resendInput.type = 'hidden';
                                    resendInput.name = 'resend_otp';
                                    resendInput.value = 'true';
                                    form.appendChild(resendInput);                  
                                    
                                    // remover required do campo de código OTP
                                    const otpInputs = form.querySelectorAll('input[name^="codigo_otp"]');
                                    otpInputs.forEach(input => {
                                        input.removeAttribute('required');
                                    });

                                    // Submeter o formulário
                                    form.submit();

                                    /* const otpId = this.getAttribute('data-otpId');

                                    fetch('{{ route('resend.otp', ':id') }}'.replace(':id', otpId), {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ otpId: otpId })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            alert('Erro ao reenviar código.');
                                        }
                                    })
                                    .catch(() => alert('Erro ao reenviar código.')); */
                                    
                                    // Aqui você pode fazer um POST/AJAX para reenviar o código
                                    // Exemplo:
                                    // fetch('/rota-para-reenviar-codigo', { method: 'POST', body: ... })
                                    // .then(...)
                                    // Reiniciar timer se desejar:
                                    // location.reload();
                                });
                            }
                        }, 1000);
                    });
                </script>
            </div>  
            @endif
        @else
        

        <div id="formLogin" class="card-body">
            <p class="login-box-msg h5">{{env('APP_NAME')}}{{env('APP_DESCRICAO') ? ' - '.env('APP_DESCRICAO') : ''}}</p>
            <p class="login-box-msg">Insira seu usuário e senha do Expresso</p>
            
            <div class="alert alert-danger erroUsuario d-none"></div>

            <form method="post">
                @csrf
                <div class="input-group mb-3">
                    <input name="usuario" type="text" class="form-control" value="" placeholder="Usuário" id="usuario">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input name="senha" type="password" class="form-control" value="" placeholder="Senha">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                @php 
                
                $dcaptcha = "";
                $vcaptcha = "";
                if (!Session::has('tentativas_limite')) {
                    Session::put('tentativas_limite',config('captcha')['tentativas']);
                }

                if(Session::get('tentativas') < Session::get('tentativas_limite')){
                    $dcaptcha = "d-none";
                    $vcaptcha = "1";
                }
                @endphp

                    <div class="captcha form-group mt-4 mb-4 {{$dcaptcha}}">
                        <div class="captcha">
                            <span>{!! captcha_img() !!}</span>
                            <button type="button" class="btn btn-danger" class="reload" id="reload">
                                &#x21bb;
                            </button>
                        </div>
                    </div>
        
                    <div class="captcha form-group mb-4 {{$dcaptcha}}">
                        <input id="captcha" type="text" class="form-control" value="{{$vcaptcha}}" placeholder="Insira a sequência de letras acima" name="captcha">
                    </div>



                <div class="row">
                    <div class="col-8">

                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a onclick="javascript:$('#formReset').show();$('#formLogin').hide();" href="#">Recuperar acesso</a>
            </p>

        </div>
        <!-- /.card-body -->
        <div id="formReset" class="card-body" style="display: none;">
            <p class="login-box-msg">Insira seu email de recuperação</p>

            <form method="post" action="{{ route('recuperarAcesso') }}">
                @csrf
                <div class="input-group mb-3">
                    <input name="email" type="email" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">

                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a onclick="javascript:$('#formReset').hide();$('#formLogin').show();" href="">Voltar ao login</a>
            </p>

        </div>
        @endif
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->



</body>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript">
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: '{{route('reload-captcha')}}',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });

    $("#usuario").on('keyup',function(){

        if(/^[0-9]{4,}/gm.exec($(this).val())){
            $(this).val('');
            $(".erroUsuario").removeClass('d-none')
            $(".erroUsuario").html("Você não deve usar seu RG!")
        }

        if($(this).val().includes('@') || $(this).val().includes('pr.gov')){

            $(this).val('');
            $(".erroUsuario").removeClass('d-none')
            $(".erroUsuario").html("Não use seu email @pm.pr.gov.br, somente o login!")

        }

        setTimeout(() => {
            
            $( ".erroUsuario" ).fadeOut(300, function() {
                $(".erroUsuario").css('display','');
                $(".erroUsuario").addClass('d-none')
            });

        }, 10000);
    });

</script>

</html>
