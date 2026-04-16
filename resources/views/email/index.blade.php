@extends('adminlte::page')

@section('title', 'Emails')

@section('content_header')
    {{ Breadcrumbs::render('emails') }}
@stop

@section('content')
    @csrf

    <div class="row p-2">
        <div class="col-md-3">
            <a id="btnEscrever" class="btn btn-primary btn-block mb-3">Escrever</a>
            <a id="btnInbox" class="btn btn-primary btn-block mb-3" style="margin-top: 0px;display: none;">Caixa de
                Entrada</a>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pastas</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item ">
                            <a href="javascript:exibirInbox()" class="nav-link">
                                <i class="fas fa-inbox"></i> Entrada
                                <span id="qtdeInbox" class="badge bg-primary float-right"></span>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="javascript:exibirOutbox()" class="nav-link">
                                <i class="far fa-envelope"></i> Saída
                                <span id="qtdeOutbox" class="badge bg-success float-right"></span>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="javascript:exibirDraft()" class="nav-link">
                                <i class="far fa-file-alt"></i> Rascunhos
                                <span id="qtdeDraft" class="badge bg-secondary float-right"></span>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="javascript:exibirSpam()" class="nav-link">
                                <i class="fas fa-filter"></i> Spam
                                <span id="qtdeSpam" class="badge bg-danger float-right"></span>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="javascript:exibirTrash()" class="nav-link">
                                <i class="far fa-trash-alt"></i> Lixeira
                                <span id="qtdeTrash" class="badge bg-warning float-right"></span>
                              </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="emailContainer" class="col-md-9 p-2" style="padding: 0;">
        </div>
        {{-- NOVO EMAIL --}}

        {{-- NOVO EMAIL --}}

        {{-- LER EMAIL --}}

        {{-- LER EMAIL --}}
    </div>

@stop

@section('css')
    <link href="{{ asset('vendor/summernote/dist/summernote.min.css') }}" rel="stylesheet">

    <style>
        .mailbox-read-message{
            overflow: scroll !important;
        }
        .navbar{
          z-index: 1 !important;
        }

        .note-toolbar{
          background-color: lavenderblush !important;
        }
        /* Popup container - can be anything you want */
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        /* Popup arrow */
        .popup .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class - hide and show the popup */
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

    </style>
    <link href="{{ asset('vendor/summernote/dist/summernote.min.css') }}" rel="stylesheet">
@stop

@section('js')

    <script type="text/javascript" src="{{ asset('vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsgrid/dist/jsgrid.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/min/locales.js') }}"></script>
    <script src="{{ asset('vendor/summernote/dist/summernote.min.js') }}"></script>
    <script src="{{ asset('vendor/summernote/dist/lang/summernote-pt-BR.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/emails.js') }}"></script>

@stop
