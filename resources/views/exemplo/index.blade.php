@extends('adminlte::page')

@section('title', 'Exemplo')

@section('content_header')
{{-- Configurar Breadcrumb em 
    app\Http\Controllers\breadcrumbs\BreadCrumbsLocalController.php --}}


@stop

@section('content')

<div class="container mt-5">
    <h1>pagina</h1>
    
</div>


@stop

@section('css')
<link type="text/css" rel="stylesheet" href="{{ asset('vendor/jsgrid/dist/jsgrid.min.css') }}" />
<link type="text/css" rel="stylesheet" href="{{ asset('vendor/jsgrid/dist/jsgrid-theme.min.css') }}" />
<link type="text/css" rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" />

@stop

@section('js')
{{-- Imports --}}
<script type="text/javascript" src="{{ asset('vendor/moment/min/moment-with-locales.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jsgrid/dist/jsgrid.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/printThis/printThis.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/chart/chart.js') }}"></script>


<script>
//javascripts



</script>
@stop
