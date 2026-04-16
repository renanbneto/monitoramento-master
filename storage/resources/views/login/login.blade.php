<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="/public/node_modules/bootstrap/dist/css/bootstrap.min.css">

    <script src="/public/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="/public/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/public/js/login.js"></script>
    <style>
        :root {
            --input-padding-x: 1.5rem;
            --input-padding-y: .75rem;
        }


        @keyframes  shine {
            0% {
                transform:translateX(-100%) translateY(-100%) rotate(30deg);
            }
            80% {transform:translateX(-100%) translateY(-100%) rotate(30deg);}
            100% {transform:translateX(100%) translateY(100%) rotate(30deg);}
        }

        body{
            background: rgb(255 255 255 / 60%);
            background-image: url('/public/images/fundo_pmpr.jpg');
            background-repeat: no-repeat;
            -webkit-background-size: cover;
            background-size: cover;

        }
        /* background: #a3a7aa;
        background: linear-gradient(to bottom, #ffffff,#ffffff, #eeff00); */
        }

        html {
            background: linear-gradient(to top, #ffffff,#ffffff, #eeff00);
        }

        .card-signin {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }

        .card-signin .card-title {
            margin-bottom: 2rem;
            font-weight: 300;
            font-size: 1.5rem;
        }

        .card-signin .card-body {
            padding: 2rem;
        }

        .form-signin {
            width: 100%;
        }

        .form-signin .btn {
            font-size: 80%;
            border-radius: 5rem;
            letter-spacing: .1rem;
            font-weight: bold;
            padding: 1rem;
            transition: all 0.2s;
        }

        .form-label-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-label-group input {
            height: auto;
            border-radius: 2rem;
        }

        .form-label-group>input,
        .form-label-group>label {
            padding: var(--input-padding-y) var(--input-padding-x);
        }

        .form-label-group>label {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            margin-bottom: 0;
            /* Override default `<label>` margin */
            line-height: 1.5;
            color: #495057;
            border: 1px solid transparent;
            border-radius: .25rem;
            transition: all .1s ease-in-out;
        }

        .form-label-group input::-webkit-input-placeholder {
            color: transparent;
        }

        .form-label-group input:-ms-input-placeholder {
            color: transparent;
        }

        .form-label-group input::-ms-input-placeholder {
            color: transparent;
        }

        .form-label-group input::-moz-placeholder {
            color: transparent;
        }

        .form-label-group input::placeholder {
            color: transparent;
        }

        .form-label-group input:not(:placeholder-shown) {
            padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
            padding-bottom: calc(var(--input-padding-y) / 3);
        }

        .form-label-group input:not(:placeholder-shown)~label {
            padding-top: calc(var(--input-padding-y) / 3);
            padding-bottom: calc(var(--input-padding-y) / 3);
            font-size: 12px;
            color: #777;
        }

        .btn-google {
            color: white;
            background-color: #ea4335;
        }

        .btn-facebook {
            color: white;
            background-color: #3b5998;
        }

        /* Fallback for Edge
        -------------------------------------------------- */

        @supports (-ms-ime-align: auto) {
            .form-label-group>label {
                display: none;
            }
            .form-label-group input::-ms-input-placeholder {
                color: #777;
            }
        }

        /* Fallback for IE
        -------------------------------------------------- */

        @media  all and (-ms-high-contrast: none),
        (-ms-high-contrast: active) {
            .form-label-group>label {
                display: none;
            }
            .form-label-group input:-ms-input-placeholder {
                color: #777;
            }
        }

        input {
            background-color: #ffffffd9 !important;
        }
    </style>
</head>
<body>
<div class="container" style="background: linear-gradient(180deg, white, transparent 1024px);
    min-height: 100vh;
    min-width: 100%;">

    <div class="row">

        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto" style="margin-top: 5px;">
            <img src="/public/images/logo.jpeg" style="
        max-width: 300px;
        margin: auto;
        justify-content: center;
        align-items: center;display: flex;">
            <div id="divFormLogin" class="card card-signin my-5" style="margin: auto;margin-top: 15px !important;background: #ffffff8c;<%= locals.recupera && locals.recupera == 'true' ? 'display: none;':''%>">
                <div class="card-body">

                    <% if(locals.message){ %>
                    <div class="alert alert-danger"><%- locals.message %></div>
                    <% } %>

                    <h5 class="card-title text-center">Identifique-se</h5>
                    <form class="form-signin" method="POST">
                        <div class="form-label-group">
                            <input type="text" name="usuario" id="inputUsuario" value="" class="form-control" placeholder="Usuário" required autofocus>
                            <label for="inputUsuario">Usuário</label>
                        </div>

                        <div class="form-label-group">
                            <input type="password" name="senha" id="inputPassword" value="" class="form-control" placeholder="Senha" required>
                            <label for="inputPassword">Senha</label>
                        </div>

                        <p style="text-align: end;"><a id="aExibeRecuperacaoAcesso" style="text-decoration: none;cursor: pointer;">Recuperar acesso?</a></p>


                        <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Logar</button>

                    </form>
                </div>
            </div>


            <div id="divRecuperaAcesso" class="card card-signin my-5" style="background: #ffffff8c;<%= locals.recupera && locals.recupera == 'true' ? 'display: block;':'display: none;'%>">
                <div class="card-body">

                    <% if(locals.message){ %>
                    <div class="alert alert-danger"><%- locals.message %></div>
                    <% } %>

                    <h5 class="card-title text-center">Email de recuperação</h5>
                    <form class="form-signin" action="http://10.147.20.106:8081/api/auth" method="POST">
                        <input type="text" name="url" id="url" style="display: none;" value="<%= locals.url %>">
                        <div class="form-label-group">
                            <input type="email" name="email" id="email" class="form-control" value="" placeholder="Email particular" required autofocus>
                            <label for="email">Email</label>
                        </div>

                        <p style="text-align: end;"><a id="aExibeLogin" style="text-decoration: none;cursor: pointer;">Voltar ao login</a></p>


                        <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Recuperar</button>

                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
</body>
<script src="/public/js/login.js"></script>
</html>
