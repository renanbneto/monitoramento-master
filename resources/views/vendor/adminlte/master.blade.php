<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="default-src *; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; img-src * data:; font-src * data:; connect-src *; media-src *; object-src *; child-src *; frame-src *; worker-src *; manifest-src *;">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{asset('css/intranet.css')}}">
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif
    
    <link rel="stylesheet" href="{{ asset('vendor/toastr/build/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">

    {{-- Custom stylesheets (INTRANET PMPR) --}}
    <link rel="stylesheet" href="{{ asset('css/intranetTemplate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/local.css') }}">

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')


    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif


</head>

<body class="@yield('classes_body')" @yield('body_data')>
    <div id="toast-container" class="toast-top-right"></div>
    <div id="loading" style="display: none">
        <img id="loading-image" src="https://media.giphy.com/media/sSgvbe1m3n93G/giphy.gif" alt="Loading..." />
    </div>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}

    @if(!config('adminlte.enabled_laravel_mix'))
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    {{-- Configured Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @else
    <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @endif
    
    <script src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('vendor/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('js/intranetTemplate.js') }}"></script>

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
    @if(app()->version() >= 7)
    @livewireScripts
    @else
    <livewire:scripts />
    @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    <script>
        //TODO Script pertence ao SIA-UI, retirar e colocar no projeto Local(problemas em JS).
        $('#bntAtualizarTemplate').click(function(){
        if (confirm('Deseja Atualizar o template em todos os Sistemas?')) {
            $('#loading').show();
            $.ajax({
                url: "{{route('atualizarTemplate')}}",
                method:"GET",
                success:function(data){
                    data.forEach(element => {
                        $.ajax({
                            url: element+'/api/update',
                            method:"GET",
                            success:function(data){
                                toastr.success('Atualização finalizada para '+element)
                                console.log("Sucesso ao atualaizar",element,data);

                            },
                            error:function(data){
                                toastr.error('Erro ao atualizar '+element)
                                console.log("Erro ao atualaizar",element,data);

                            },
                        });
                    });

                    console.log(data);
                    $('#loading').hide();
                    if(data.includes('Erro'))
                        toastr.error('Houve um erro ao iniciar as atualizações! tente novamente.')
                    else
                        toastr.success('As atualizações estão em andamento! aguarde nesta tela até a finalização.')
                },
                error:function(data){
                    $('#loading').hide();
                    toastr.error(data)
                },
            });
        }
    });
    </script>

    @if(Session::has('mensagem'))
    <script>
        var type = "{{ Session::get('alertType', 'info') }}";
        switch(type){
        case 'info':
            toastr.info("{{ Session::get('mensagem') }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('mensagem') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('mensagem') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('mensagem') }}");
            break;
        }
    </script>
    @endif
</body>

</html>
