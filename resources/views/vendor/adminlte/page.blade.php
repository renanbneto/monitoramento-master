@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container p-0' )
@else
    @php( $def_container_class = 'container-fluid p-0' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')

    {{-- Personal CSS --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>

    @media (max-width:499px){
        .navlink
        {
            display:none!important;
        }
    }
    @media (min-width:501px){
        .sidelink
        {
            display:none!important;
        }
    }
    .col-md-12{
        padding: 0;
    }
    .row-md-12{
        padding: 0;
    }
    </style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
        
    <div class="wrapper">

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <div class="content-wrapper {{ config('adminlte.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            @hasSection('content_header')
                <div class="content-header">
                    <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                        @yield('content_header')
                    </div>
                </div>
            @endif

            {{-- Main Content --}}
            <div class="content p-0">
                <div class="{{ config('adminlte.classes_content') ?: $def_container_class }} p-0">
                    @yield('content')
                </div>
            </div>

        </div>

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    
        
@stop

