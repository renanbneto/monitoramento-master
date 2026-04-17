@inject('Autorizacao', 'App\Http\Controllers\Autorizacao')
@extends('adminlte::page')

@section('title', 'Mapa')

@section('content_header')
    {{-- Configurar Breadcrumb em
    app\Http\Controllers\breadcrumbs\BreadCrumbsLocalController.php --}}
    {{-- {{ Breadcrumbs::render('nomeDaRotaBreadcrumb') }} --}}
@stop

@section('content')
    {{-- Padrão para autorização de permissões na view. --}}
    @if ($Autorizacao->can(['Administrador']))
    @endif
    <div id="mapa">

    <div class="custom-select">
        <div class="d-flex w-100 h-100">
            <div class="row" style="width: 100%;">
                <div class="col-6">
                    <div class="form-group">
                        <label for="location-options">Localizar ônibus</label>
                        <select id="location-options" class="form-control select2" style="width: 100%;">
                            <option value="">Selecione um ônibus</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="camera-select">Escolha uma câmera</label>
                        <select id="camera-select" class="form-control select2" style="width: 100%;"></select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mySidepanelLeft" class="sidepanel sidepanel-left tabs-left d-none" aria-label="side panel" aria-hidden="false">
        <div class="sidepanel-inner-wrapper">
            <nav class="sidepanel-tabs-wrapper" aria-label="sidepanel tab navigation">
                <ul class="sidepanel-tabs">
                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-geo" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"></path>
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16">
                                <path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"></path>
                                <path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"></path>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidepanel-content-wrapper">
                <div class="sidepanel-content">
                    <div class="sidepanel-tab-content" data-tab-content="tab-1">
                        <h4>Content 1</h4>

                    </div>
                    <div class="sidepanel-tab-content" data-tab-content="tab-2">
                        <h4>Content 2</h4>

                    </div>
                    <div class="sidepanel-tab-content" data-tab-content="tab-3">
                        <h4>Content 3</h4>

                    </div>
                    <div class="sidepanel-tab-content" data-tab-content="tab-4">
                        <h4>Content 4</h4>

                    </div>
                    <div class="sidepanel-tab-content" data-tab-content="tab-5">
                        <h4>Content 5</h4>

                    </div>
                </div>
            </div>
        </div>
        <div class="sidepanel-toggle-container">
            <button class="sidepanel-toggle-button" type="button" aria-label="toggle side panel"></button>
        </div>
    </div>

    <div id="mySidepanelRight" class="sidepanel sidepanel-right tabs-right" aria-label="side panel" aria-hidden="false">
        <div class="sidepanel-inner-wrapper">
            <nav class="sidepanel-tabs-wrapper" aria-label="sidepanel tab navigation">
                <ul class="sidepanel-tabs">

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-1">
                            <i class="fas fa-th"></i>&nbsp;<span>1</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-2">
                            <i class="fas fa-th"></i>&nbsp;<span>2</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-3">
                            <i class="fas fa-th"></i>&nbsp;<span>3</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-4">
                            <i class="fas fa-th"></i>&nbsp;<span>4</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-5">
                            <i class="fas fa-th"></i>&nbsp;<span>5</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-6">
                            <i class="fas fa-th"></i>&nbsp;<span>6</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-7">
                            <i class="fas fa-th"></i>&nbsp;<span>7</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-8">
                            <i class="fas fa-th"></i>&nbsp;<span>8</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-9">
                            <i class="fas fa-th"></i>&nbsp;<span>9</span>
                        </a>
                    </li>

                    <li class="sidepanel-tab">
                        <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-10">
                            <i class="fas fa-th"></i>&nbsp;<span>10</span>
                        </a>
                    </li>

                </ul>
            </nav>
            <div class="sidepanel-content-wrapper">
                <div class="sidepanel-content">

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-1" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">
                            <span>Camera 01</span>
                            <i style="float: right;margin-right: 5px;margin-top: 5px;" class="fas fa-times-circle" onclick="alert('Remover do mosaico')"></i>
                            </div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-2" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-3" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-4" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-5" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-6" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-7" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-8" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-9" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                    <div class="sidepanel-tab-content tabMosaicos" data-tab-content="tab-10" style="width: 100%;height: 100%;">
                        <div style="width: 100%;height: 100%;display:flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around !important;">
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 1</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                            <div class="" style="margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">Camera 2</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="sidepanel-toggle-container">
            <button class="sidepanel-toggle-button" type="button" aria-label="toggle side panel"></button>
        </div>
    </div>

</div>

{{-- Toolbar do mosaico inline --}}
<div id="mosaico-inline-toolbar" style="display:none;">
    <span class="text-white" style="font-size:13px;" id="mosaico-inline-count">0 câmera(s) carregada(s)</span>
    <div class="float-right">
        <button class="btn btn-sm btn-outline-warning mr-2" id="btn-selecao-area"
                title="Selecionar câmeras por área no mapa">
            <i class="fas fa-draw-polygon"></i> Selecionar por área
        </button>
        <button class="btn btn-sm btn-outline-danger" id="btn-limpar-mosaico">
            <i class="fas fa-times"></i> Limpar tudo
        </button>
    </div>
    <div style="clear:both;"></div>
</div>

{{-- Mosaico inline de câmeras --}}
<div id="mosaico-inline"></div>

@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-easybutton@2.4.0/src/easy-button.css">
    <link rel="stylesheet" href="{{ asset('vendor/leaflet-fullscreen/Control.FullScreen.css') }}">
    <link rel="stylesheet" href="{{asset('vendor/leaflet-sidepanel/dist/leaflet-sidepanel.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
    <style>

.custom-select {
      display: none; /* Initially hidden */
      position: fixed;
      max-width: 80%;
      height: auto;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1000;
      background-color: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

/* CSS para ocultar o painel de layers quando colapsado */
.leaflet-control-layers.collapsed {
    display: none;
}

/* Estilo para o botão de layers */
.leaflet-bar .leaflet-control-custom button {
    background-color: #fff;
    color: #333;
    padding: 5px;
    border: none;
    font-size: 14px;
    cursor: pointer;
}

.leaflet-bar .leaflet-control-custom button:hover {
    background-color: #f4f4f4;
}

        .busca-onibus-control {
            margin-top: 50px; /* Ajuste conforme necessário */
        }

   /*      .leaflet-touch .leaflet-control-geocoder{
            //min-width: 95%;
        }  
        
        .leaflet-touch .leaflet-control-geocoder-icon{
            //min-width: 95%;
            background-position-x: 0;
        }
 */
        #mapa {
            height: 60vh;
            width: 100%;
        }

        #mosaico-inline-toolbar {
            background: #2d2d2d;
            padding: 6px 12px;
        }

        #mosaico-inline {
            display: none;
            flex-wrap: wrap;
            background: #1a1a1a;
            padding: 4px;
            min-height: 220px;
        }

        #mosaico-inline .camera-tile {
            position: relative;
            background: #000;
            border: 1px solid #333;
            padding: 0;
        }

        #mosaico-inline .camera-tile img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        #mosaico-inline .camera-tile .tile-header {
            position: absolute;
            top: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.65);
            color: #fff;
            font-size: 11px;
            padding: 2px 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        #mosaico-inline .camera-tile .tile-header button {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 0 2px;
            line-height: 1;
        }

        #btn-selecao-area.ativo {
            background-color: #f39c12;
            color: #fff;
        }

        #mySidepanelLeft {
            width: 90%;
        }

        #mySidepanelRight {
            width: 90%;
        }

        .marker-context-menu {
            display: none;
            position: absolute;
            background-color: #f2f2f2;
            border: solid 1px #d4d4d4;
            padding: 10px;
            z-index: 10000;
        }
    </style>
@stop

@section('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-easybutton@2.4.0/src/easy-button.js"></script>
<script src="{{ asset('vendor/leaflet-fullscreen/Control.FullScreen.min.js') }}"></script>
<script src="{{asset('vendor/leaflet-sidepanel/dist/leaflet-sidepanel.min.js')}}"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.full.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>


    var tracker;
    var layersCameras = L.layerGroup();
    var mosaicoCameras = {};
    var MAX_CAMERAS_MOSAICO = 9;

var mosaicos = {
        m1:[],
        m2:[],
        m3:[],
        m4:[],
        m5:[],
        m6:[],
        m7:[],
        m8:[],
        m9:[],
        m10:[]
    };

$.ajax({
    url:'{{route('mosaicos')}}',
    success: function(data) {
        try {
            var raw = (data && data.mosaico != null) ? data.mosaico : '{}';
            if (typeof raw !== 'string') {
                raw = JSON.stringify(raw);
            }
            mosaicosBanco = JSON.parse(raw || '{}');
        } catch (e) {
            mosaicosBanco = {};
        }
        if (!mosaicosBanco || typeof mosaicosBanco !== 'object') {
            mosaicosBanco = {};
        }

        for (let index = 1; index <= 10; index++) {

            if (!mosaicosBanco.hasOwnProperty(`m${index}`)) {
               mosaicosBanco[`m${index}`] = [];
            }

        }

        mosaicos = mosaicosBanco;

        try {
            localStorage.setItem('mosaicos', JSON.stringify(mosaicosBanco));
        } catch (e) {
            /* Tracking Prevention / modo privado pode bloquear storage */
        }
    },
    error: function () {
        mosaicosBanco = {};
        for (let index = 1; index <= 10; index++) {
            mosaicosBanco['m' + index] = [];
        }
        mosaicos = mosaicosBanco;
    }
});

function showAddMosaicoView(el){
    camera = JSON.parse(atob($(el).data().camera))

    corpo = `<div class="d-flex flex-wrap justify-content-around">

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link active" role="tab" data-mosaico="m1" data-tab-link="tab-1">
        <i class="fas fa-th"></i>&nbsp;<span>1</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m2" data-tab-link="tab-2">
        <i class="fas fa-th"></i>&nbsp;<span>2</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m3" data-tab-link="tab-3">
        <i class="fas fa-th"></i>&nbsp;<span>3</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m4" data-tab-link="tab-4">
        <i class="fas fa-th"></i>&nbsp;<span>4</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m5" data-tab-link="tab-5">
        <i class="fas fa-th"></i>&nbsp;<span>5</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m6" data-tab-link="tab-6">
        <i class="fas fa-th"></i>&nbsp;<span>6</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m7" data-tab-link="tab-7">
        <i class="fas fa-th"></i>&nbsp;<span>7</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m8" data-tab-link="tab-8">
        <i class="fas fa-th"></i>&nbsp;<span>8</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m9" data-tab-link="tab-9">
        <i class="fas fa-th"></i>&nbsp;<span>9</span>
    </a>
</div>

<div class="sidepanel-tab">
    <a href="#" class="sidebar-tab-link" role="tab" data-mosaico="m10" data-tab-link="tab-10">
        <i class="fas fa-th"></i>&nbsp;<span>10</span>
    </a>
</div></div>`;
    Swal.fire({
        width: '50%',
        title: "Selecione o mosaico para adicionar a câmera",
        html:corpo,
        showDenyButton: false,
        showConfirmButton: false,
        showCancelButton: true,
        confirmButtonText: "Adicionar",
        cancelButtonText: `Cancelar`,
        willOpen: () =>{
            $('.sidepanel-tab a').click(function(){
                addToMosaico($(this).data().mosaico,camera)
                Swal.close();
            });
        },
    }).then((result) => {

        if (result.isConfirmed) {
            Swal.fire("Adicionada!", "", "success");
        }
    });

    console.log(camera)
}

function updateMosaicos(){
    $.ajax({
    url:'{{route('atualizaMosaicos')}}',
    method:'POST',
    data:{mosaico:JSON.stringify(mosaicos)},
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function(data) {
        console.log("Mosaicos atualizados no perfil do usuário!");
    },
    error: function(){
        console.log("Erro ao atualizar os mosaicos no perfil do usuário!");
    }
});
}

function refreshMosaicos(){

    for (let index = 1; index <= 10; index++) {

        // Seleciona a div correspondente
        let divMosaicoAtual = $('[data-tab-content="tab-' + index + '"].tabMosaicos > div')

        //Limpar view do mosaico index
        $(divMosaicoAtual).html('')

        //Iterar pela view do mosaico index carregando na tela sem ativar o source

        mosaicos["m"+index].forEach(camera => {

            $(divMosaicoAtual).append(`
            <div class="" style="display:flex;flex-flow:column;margin-bottom: 10px; margin-right: 10px;min-width: 300px;min-height:200px;color:white;background-color:black;">
                <div>
                <span>${camera.camera} - ${camera.cidade}</span>
                <i style="float: right;margin-right: 5px;margin-top: 5px;" class="fas fa-times-circle" onclick="removeFromMosaico('m${index}','${camera.id}')"></i>
                </div>
                <img src="${camera.link}"></img>
            </div>
            `);

        });


    }

}

function removeFromMosaico(mosaico,id){
    mosaicos[mosaico] = mosaicos[mosaico].filter(item => item.id != id);
    localStorage.setItem('mosaicos', JSON.stringify(mosaicosBanco));
    updateMosaicos();
    refreshMosaicos();
}

function addToMosaico(mosaico,camera){
    mosaicos[mosaico].push(camera);
    localStorage.setItem('mosaicos', JSON.stringify(mosaicosBanco));
    updateMosaicos();
    refreshMosaicos();
}

function telaCheia(el) {

  if (el.requestFullscreen) {
    el.requestFullscreen();
  } else if (el.mozRequestFullScreen) { /* Firefox */
    el.mozRequestFullScreen();
  } else if (el.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
    el.webkitRequestFullscreen();
  } else if (el.msRequestFullscreen) { /* IE/Edge */
    el.msRequestFullscreen();
  }

}


$(document).ready(function(){

    $(".sidepanel-toggle-button").click(refreshMosaicos);

    $('.sidebar-tab-link').on('dblclick',function(){

        var div = document.getElementById('mySidepanelRight');

        if (div.requestFullscreen) {
            div.requestFullscreen();
        } else if (div.mozRequestFullScreen) { /* Firefox */
            div.mozRequestFullScreen();
        } else if (div.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
            div.webkitRequestFullscreen();
        } else if (div.msRequestFullscreen) { /* IE/Edge */
            div.msRequestFullscreen();
        }

    });
});


// RASTREAMENTO
// Variáveis de controle
let trackingInterval = null;
let trackingTime = 0;
const maxTrackingTime = 5 * 60 * 1000; // 5 minutos em milissegundos

// Função para iniciar o rastreamento do marker
function startTracking(veiculo) {
    let marker = veiculosDataFetcher.markers[veiculo];
    markerLatLng = marker.getLatLng();
    mapa.setView(markerLatLng, 30); // Altera o zoom e centraliza no marker
    marker.openPopup();
    
    
    if (trackingInterval) return; // Impede iniciar múltiplos rastreamentos

    trackingInterval = setInterval(() => {
        trackingTime += 5000; // Incrementa o tempo de rastreamento a cada 5 segundos

        // Reposiciona o zoom e a visualização para as coordenadas atuais do marker
        const markerLatLng = marker.getLatLng();
        mapa.setView(markerLatLng, 30); // Altera o zoom e centraliza no marker
        // Abre o popup se ele não estiver visível
        if (!marker.getPopup().isOpen()) {
                    marker.openPopup();
                }
        // Cancela o rastreamento após 5 minutos
        if (trackingTime >= maxTrackingTime) {
            stopTracking();
            alert("Rastreamento automático encerrado após 5 minutos.");
        }
    }, 5000);
}

// Função para parar o rastreamento
function stopTracking() {
    if (trackingInterval) {
        clearInterval(trackingInterval);
        trackingInterval = null;
        trackingTime = 0;
    }
}


(function() {
        // save these original methods before they are overwritten
        var proto_initIcon = L.Marker.prototype._initIcon;
        var proto_setPos = L.Marker.prototype._setPos;

        var oldIE = (L.DomUtil.TRANSFORM === 'msTransform');

        L.Marker.addInitHook(function () {
            var iconOptions = this.options.icon && this.options.icon.options;
            var iconAnchor = iconOptions && this.options.icon.options.iconAnchor;
            if (iconAnchor) {
                iconAnchor = (iconAnchor[0] + 'px ' + iconAnchor[1] + 'px');
            }
            this.options.rotationOrigin = this.options.rotationOrigin || iconAnchor || 'center bottom' ;
            this.options.rotationAngle = this.options.rotationAngle || 0;

            // Ensure marker keeps rotated during dragging
            this.on('drag', function(e) { e.target._applyRotation(); });
        });

        L.Marker.include({
            _initIcon: function() {
                proto_initIcon.call(this);
            },

            _setPos: function (pos) {
                proto_setPos.call(this, pos);
                this._applyRotation();
            },

            _applyRotation: function () {
                if(this.options.rotationAngle) {
                    this._icon.style[L.DomUtil.TRANSFORM+'Origin'] = this.options.rotationOrigin;

                    if(oldIE) {
                        // for IE 9, use the 2D rotation
                        this._icon.style[L.DomUtil.TRANSFORM] = 'rotate(' + this.options.rotationAngle + 'deg)';
                    } else {
                        // for modern browsers, prefer the 3D accelerated version
                        this._icon.style[L.DomUtil.TRANSFORM] += ' rotateZ(' + this.options.rotationAngle + 'deg)';
                    }
                }
            },

            setRotationAngle: function(angle) {
                this.options.rotationAngle = angle;
                this.update();
                return this;
            },

            setRotationOrigin: function(origin) {
                this.options.rotationOrigin = origin;
                this.update();
                return this;
            }
        });
    })();

            document.body.classList.remove('sidebar-mini');

            var mapa = L.map('mapa',{
                fullscreenControl: true,
            }).setView([-25.4284, -49.2733], 13);

            /* L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(mapa); */

                // Camada base - Mapa de ruas
            var streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(mapa);

            // Camada base - Mapa Satélite (exemplo adicional)
            var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
            });




            // API Key do OpenWeatherMap
        var apiKey = '151e3e479ee106c8d760dabe4d6604ee';

        // Adiciona uma camada de nuvens (clouds) usando WMS do OpenWeatherMap
        var cloudsLayer = L.tileLayer(`https://tile.openweathermap.org/map/clouds_new/{z}/{x}/{y}.png?appid=151e3e479ee106c8d760dabe4d6604ee`, {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openweathermap.org/">OpenWeatherMap</a>'
        });

        // Adiciona uma camada de precipitação (precipitation)
        var precipitationLayer = L.tileLayer(`https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid=151e3e479ee106c8d760dabe4d6604ee`, {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openweathermap.org/">OpenWeatherMap</a>'
        });

        // Adiciona uma camada de temperatura
        var temperatureLayer = L.tileLayer(`https://tile.openweathermap.org/map/temp_new/{z}/{x}/{y}.png?appid=151e3e479ee106c8d760dabe4d6604ee`, {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openweathermap.org/">OpenWeatherMap</a>'
        });

        // Adiciona uma camada de ventos (wind)
        var windLayer = L.tileLayer(`https://tile.openweathermap.org/map/wind_new/{z}/{x}/{y}.png?appid=151e3e479ee106c8d760dabe4d6604ee`, {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openweathermap.org/">OpenWeatherMap</a>'
        });



 // Lista de cidades com coordenadas (exemplo)
 const cidades = [
    { nome: "Curitiba", lat: -25.4284, lon: -49.2733 },
    { nome: "Londrina", lat: -23.304, lon: -51.1696 },
    { nome: "Maringá", lat: -23.4205, lon: -51.9333 },
    { nome: "Ponta Grossa", lat: -25.0916, lon: -50.1668 },
    { nome: "Cascavel", lat: -24.9578, lon: -53.459 },
    { nome: "São José dos Pinhais", lat: -25.5317, lon: -49.2031 },
    { nome: "Foz do Iguaçu", lat: -25.5163, lon: -54.5854 },
    { nome: "Colombo", lat: -25.2922, lon: -49.2262 },
    { nome: "Guarapuava", lat: -25.3902, lon: -51.4623 },
    { nome: "Paranaguá", lat: -25.5161, lon: -48.5221 },
    { nome: "Araucária", lat: -25.5934, lon: -49.4103 },
    { nome: "Toledo", lat: -24.7246, lon: -53.7412 },
    { nome: "Apucarana", lat: -23.5500, lon: -51.4606 },
    { nome: "Campo Mourão", lat: -24.0463, lon: -52.378 },
    { nome: "Pato Branco", lat: -26.2292, lon: -52.6706 },
    { nome: "Umuarama", lat: -23.7656, lon: -53.3206 },
    { nome: "Paranavaí", lat: -23.0816, lon: -52.4614 },
    { nome: "Francisco Beltrão", lat: -26.0813, lon: -53.0536 },
    { nome: "Cambé", lat: -23.275, lon: -51.2783 },
    { nome: "Rolândia", lat: -23.3101, lon: -51.3696 },
    { nome: "Castro", lat: -24.7891, lon: -50.0116 },
    { nome: "Irati", lat: -25.4697, lon: -50.6492 },
    { nome: "Palmas", lat: -26.4836, lon: -51.9901 },
    { nome: "Telêmaco Borba", lat: -24.3244, lon: -50.6176 },
    { nome: "Assis Chateaubriand", lat: -24.4163, lon: -53.5211 },
    { nome: "Jacarezinho", lat: -23.1591, lon: -49.9736 },
    { nome: "Cianorte", lat: -23.6598, lon: -52.6054 },
    { nome: "Ibiporã", lat: -23.265, lon: -51.0483 },
    { nome: "Sarandi", lat: -23.4445, lon: -51.876 },
    { nome: "Mandaguari", lat: -23.5445, lon: -51.6725 },
    { nome: "Marechal Cândido Rondon", lat: -24.5576, lon: -54.0561 },
    { nome: "Guaratuba", lat: -25.88121, lon: -48.574075 },
    { nome: "Matinhos", lat: -25.807210, lon: -48.5351 },
    { nome: "Pontal do paraná", lat: -25.5737353, lon: -48.363876 },
    { nome: "Praia de Leste", lat: -25.697760, lon: -48.4763145 },
    { nome: "Ilha do Mel", lat: -25.533792, lon: -48.3120 }
];

var temperatureMarkersLayer = L.layerGroup();

// Função para buscar a temperatura e exibir no mapa
function exibirTemperaturaNoMapa(cidade) {
    const url = `https://api.openweathermap.org/data/2.5/weather?lat=${cidade.lat}&lon=${cidade.lon}&appid=${apiKey}&units=metric&lang=pt`;

    fetch(url)
        .then(response => response.json())
        .then(data => {

            const { main, sys, name } = data;
            const temperatura = main.temp;
            const descricao = data.weather[0].description;
            const feels_like = main.feels_like;
            const temp_min = main.temp_min;
            const temp_max = main.temp_max;
            const humidity = main.humidity;
            const pressure = main.pressure;
            const sea_level = main.sea_level || 'N/A';
            const grnd_level = main.grnd_level || 'N/A';
            const sunrise = new Date(sys.sunrise * 1000).toLocaleTimeString("pt-BR");
            const sunset = new Date(sys.sunset * 1000).toLocaleTimeString("pt-BR");
            
            // Cria um ícone personalizado para exibir temperatura
            const iconeTemperatura = L.divIcon({
                className: 'custom-div-icon',
                html: `
                    <div style="text-align: center; background: #fff; padding: 5px; border-radius: 8px;  box-shadow: 0px 0px 5px rgba(0,0,0,0.3);">
                        <strong>${cidade.nome}</strong><br>
                        <span style="color: #007bff; font-size: 16px;">${temperatura}°C</span><br>
                        <small>${descricao}</small>
                    </div>
                `,
                iconSize: [80, 50]
            });

            // Adiciona o marcador no mapa
            marker = L.marker([cidade.lat, cidade.lon], { icon: iconeTemperatura })
                .bindPopup(`
                    <b>${name}, ${sys.country}</b><br>
                    <b>Temperatura Atual:</b> ${temperatura}°C<br>
                    <b>Sensação Térmica:</b> ${feels_like}°C<br>
                    <b>Temperatura Mínima:</b> ${temp_min}°C<br>
                    <b>Temperatura Máxima:</b> ${temp_max}°C<br>
                    <b>Umidade:</b> ${humidity}%<br>
                    <b>Pressão:</b> ${pressure} hPa<br>
                    <b>Nível do Mar:</b> ${sea_level} hPa<br>
                    <b>Nível do Solo:</b> ${grnd_level} hPa<br>
                    <b>Amanhecer:</b> ${sunrise}<br>
                    <b>Pôr do Sol:</b> ${sunset}
                `);
            // Adiciona o marcador à camada de temperatura
            temperatureMarkersLayer.addLayer(marker);
        })
        .catch(error => console.error('Erro ao buscar dados da API:', error));
}

// Chama a função para cada cidade
cidades.forEach(cidade => exibirTemperaturaNoMapa(cidade));

            
            var onibusUrbs = L.layerGroup();
            var guarnicoes = L.layerGroup();
            var radiocom = L.layerGroup();

            // Adicionando controle de camadas com labels personalizados
            var baseLayers = {
                "Mapa de Ruas": streets,
                "Mapa Satélite": satellite,
            };

            var overlays = {
                "<b><img src=\"/images/vtr.png\" width=\"25\" height=\"25\" style=\"margin-top:-3px;\"> Equipes PM</b>": guarnicoes,  // Label personalizado com HTML
                "<b><img src=\"/images/radio.png\" width=\"25\" height=\"25\" style=\"margin-top:-3px;\"> Rádios/HT</b>": radiocom,  // Label personalizado com HTML
                "<b><img src=\"/images/cam.png\" width=\"25\" height=\"25\" style=\"margin-top:-3px;\"> Câmeras</b>": layersCameras,  // Label personalizado com HTML
                "<b><img src=\"/images/onibus.png\" width=\"25\" height=\"25\" style=\"margin-top:-3px;\"> Ônibus de Curitiba</b>": onibusUrbs,  // Label personalizado com HTML
                //"Nuvens": cloudsLayer,
                "Precipitação": precipitationLayer,
                "Temperatura": temperatureLayer,
                //"Ventos": windLayer
            };


            // Adiciona o controle de camadas (base e sobreposições) ao mapa
            L.control.layers(baseLayers, overlays, {collapsed: false}).addTo(mapa);

                    // Função para colapsar o painel de layers
                    // Função para colapsar o painel de layers
            function toggleLayersPanel() {
                const layersPanel = document.querySelector('.leaflet-control-layers');
                if (layersPanel.classList.contains('collapsed')) {
                    layersPanel.classList.remove('collapsed');
                } else {
                    layersPanel.classList.add('collapsed');
                }
            }

            // Cria um botão de toggle para colapsar/expandir o painel de layers
            const toggleButton = L.control({ position: 'topright' });
            toggleButton.onAdd = function () {
                const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                div.innerHTML = '<button id="toggleLayers" class="leaflet-control-layers-toggle" title="Mostrar/Ocultar Layers"></button>';
                div.onclick = toggleLayersPanel;
                return div;
            };
            toggleButton.addTo(mapa);

            // Inicialmente colapsa o painel em dispositivos móveis
            document.querySelector('.leaflet-control-layers').classList.add('collapsed');

            // Desativa a propagação de eventos para impedir que o mapa responda ao toque no painel de layers
            L.DomEvent.disableClickPropagation(document.querySelector('.leaflet-control-layers'));

            L.Control.geocoder({
                placeholder: 'Digite o endereço ou nome do local...',
                defaultMarkGeocode: true,
                position: 'topleft'
            }).addTo(mapa);

             // Personaliza o botão de pesquisa
            var geocoderIcon = document.querySelector('.leaflet-control-geocoder-icon');
            //geocoderIcon.innerHTML = 'Localizar endereço';

            // Reference to the select element
  const customSelect = document.querySelector('.custom-select');
  const locationOptions = document.getElementById('location-options');

  // Create a Leaflet EasyButton
  const button = L.easyButton('fa-chevron-down', function() {
    // Toggle the display of the select dropdown
    customSelect.style.display = customSelect.style.display === 'block' ? 'none' : 'block';

    $(locationOptions).html('')
    Object.keys(veiculosDataFetcher.markers).forEach(el => {
        $(locationOptions).append(`
            <option value="${el}">${el} - ${veiculosDataFetcher.markers[el].options.linha} - ${veiculosDataFetcher.markers[el].options.categoria}</option>
        `);
    });
    $(locationOptions).select2({
        placeholder:'Digite o prefixo'
    });


    // Initialize Select2 with AJAX search
  $('#camera-select').select2({
    placeholder: 'Busque uma câmera pelo endereço',
    ajax: {
      url: '/cameras',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          'fields[cameras]': 'camera,local_nome,lat,lng,cidade',
          'filter[local_nome]': params.term, // search term
          'termo': params.term // search term
        };
      },
      processResults: function (data) {
        return {
          results: data.map(item => ({
            id: item.camera,
            text: `${item.local_nome} (${item.cidade})`,
            lat: item.lat,
            lng: item.lng
          }))
        };
      },
      cache: true
    }
  });

  // Função para encontrar e disparar o clique no marcador usando coordenadas
function triggerMarkerClick(lat, lng) {
    // Encontre o marcador em layersCameras com as coordenadas fornecidas
    layersCameras.eachLayer(function(layer) {
        if (layer instanceof L.Marker) {
            const markerLatLng = layer.getLatLng();
            
            // Verifica se as coordenadas coincidem
            if (markerLatLng.lat == lat && markerLatLng.lng == lng) {
                layer.fire('click');
                // Dispara o evento 'click' no marcador encontrado
            }
        }
    });
}

  // On camera selection, center map on the chosen location
  $('#camera-select').on('select2:select', function (e) {
    if (customSelect.style.display === 'block') {
        customSelect.style.display = 'none';
    }
    const selectedData = e.params.data;
    console.log(selectedData);
    mapa.setView([selectedData.lat, selectedData.lng], 17);
    // Exemplo de chamada com as coordenadas desejadas
    triggerMarkerClick(selectedData.lat, selectedData.lng);
  });


  }, 'Choose an option').addTo(mapa);


    $(locationOptions).on("select2:select", function (e) { 
        if ($(locationOptions).val()) {
            //acionar a layer se não estiver acionadas
            mapa.addLayer(onibusUrbs);
            stopTracking();
            startTracking($(locationOptions).val())
            customSelect.style.display = 'none'; // Hide dropdown after selection
            //locationOptions.value = ''; // Reset selection
        }
    });

  // Handle selection change
  locationOptions.addEventListener('change', () => {
    
  });




  

            // Função para controlar a ativação/desativação conjunta das camadas
mapa.on('overlayadd', function (event) {
    if (event.name === "Temperatura") {
        mapa.addLayer(temperatureMarkersLayer);  // Ativa marcadores ao ativar o layer de temperatura
    }
});

mapa.on('overlayremove', function (event) {
    if (event.name === "Temperatura") {
        mapa.removeLayer(temperatureMarkersLayer); // Desativa marcadores ao desativar o layer de temperatura
    }
});

            mapa.on('click', function() {
                
                
                $('.marker-context-menu').remove()


            });

            mapa.on('contextmenu', function(event){

                $('.marker-context-menu').remove(); // Remove anteriores

                console.log(event.originalEvent);
                //Adiciona Novo
                $('#mapa').append(`<div class="marker-context-menu" id="markerMenu" style="left: ${event.originalEvent.pageX-230}px;top: ${event.originalEvent.pageY-60}px;display:block;">
                    <ul>
                    @if ($Autorizacao->can(['Administrador']))
                        <li><a href="#action1">Abrir mosaico desta região</a></li>
                        <li><a href="/cameras/create?lat=${event.latlng.lat}&lng=${event.latlng.lng}">Cadastrar camera nesta posição</a></li>
                    @endif
                    </ul>
                    <span>Latitude: ${event.latlng.lat}, Longitude: ${event.latlng.lng}</span>
                </div>`);

                event.originalEvent.preventDefault();
            });

            const panelLeft = L.control.sidepanel('mySidepanelLeft', {
                panelPosition: 'left',
                hasTabs: true,
                tabsPosition: 'top',
                pushControls: true,
                darkMode: false,
                startTab: 'tab-1'
            }).addTo(mapa);

            const panelRight = L.control.sidepanel('mySidepanelRight', {
                panelPosition: 'right',
                hasTabs: true,
                tabsPosition: 'top',
                pushControls: true,
                darkMode: false,
                startTab: 'tab-1'
            }).addTo(mapa);

            const veiculosDataFetcher = {
  url: '/onibus',
  intervalo: 10000, // 1 minuto em milissegundos
  veiculos: {}, // Objeto para armazenar os dados dos veículos
  markers: {}, // Objeto para armazenar os marcadores dos veículos no mapa

   // URL do ícone do ônibus
   busIconUrl: '{{asset('images/bus.png')}}', // Substitua pelo URL da sua imagem

// Ícone personalizado para o ônibus
busIconoffline: L.icon({
  iconUrl: '{{asset('images/bus.png')}}', // Substitua pelo URL da sua imagem
  iconSize: [38, 38], // Tamanho do ícone
  iconAnchor: [19, 38], // Ponto do ícone que irá corresponder à posição do marcador
  popupAnchor: [0, -38] // Ponto do popup em relação ao ícone
}),
// Ícone personalizado para o ônibus
busIcononline: L.icon({
  iconUrl: '{{asset('images/busonline.png')}}', // Substitua pelo URL da sua imagem
  iconSize: [38, 38], // Tamanho do ícone
  iconAnchor: [19, 38], // Ponto do ícone que irá corresponder à posição do marcador
  popupAnchor: [0, -38] // Ponto do popup em relação ao ícone
}),
// Ícone personalizado para o ônibus
busIcondesconhecido: L.icon({
  iconUrl: '{{asset('images/busdesconhecido.png')}}', // Substitua pelo URL da sua imagem
  iconSize: [38, 38], // Tamanho do ícone
  iconAnchor: [19, 38], // Ponto do ícone que irá corresponder à posição do marcador
  popupAnchor: [0, -38] // Ponto do popup em relação ao ícone
}),
// Ícone personalizado para o ônibus
busIconatrasado: L.icon({
  iconUrl: '{{asset('images/busatrasado.png')}}', // Substitua pelo URL da sua imagem
  iconSize: [38, 38], // Tamanho do ícone
  iconAnchor: [19, 38], // Ponto do ícone que irá corresponder à posição do marcador
  popupAnchor: [0, -38] // Ponto do popup em relação ao ícone
}),

  // Função para buscar dados da API
  buscarDados: function () {
    fetch(this.url, { credentials: 'same-origin' })
      .then(response => {
        if (!response.ok) {
          throw new Error('Erro ao buscar os dados');
        }
        return response.json();
      })
      .then(data => {
        this.atualizarVeiculos(data);
      })
      .catch(error => {
        console.error('Erro na requisição:', error);
      });
  },

  // Atualizar o objeto de veículos e marcadores
  atualizarVeiculos: function (data) {
    if (!data || typeof data !== 'object' || Array.isArray(data)) {
      return;
    }
    this.veiculos = data;

    Object.keys(this.veiculos).forEach(cod => {
      const veiculo = this.veiculos[cod];
      if (!veiculo || typeof veiculo !== 'object') {
        return;
      }
      const lat = parseFloat(String(veiculo.LAT || '').replace(',', '.'));
      const lon = parseFloat(String(veiculo.LON || '').replace(',', '.'));
      if (!isFinite(lat) || !isFinite(lon) || lat < -90 || lat > 90 || lon < -180 || lon > 180) {
        return;
      }

      if (this.markers[cod]) {
        this.markers[cod].setLatLng([lat, lon]);
      } else {
        var st = veiculo.STATUS || 'desconhecido';
        var iconM = this['busIcon' + st] || this.busIcondesconhecido;
        this.markers[cod] = L.marker([lat, lon],{ 
            icon: iconM,
            categoria: veiculo.CATEGORIA_LINHA || 'RECOLHENDO',
            linha: veiculo.NOME_LINHA || 'RECOLHENDO',
         }).addTo(onibusUrbs)
          .bindPopup(`
          <b>Veículo:</b>${veiculo.CATEGORIA_LINHA || 'RECOLHENDO'} - ${veiculo.COD}<br>
          <b>Cor:</b>${veiculo.COR_LINHA || 'RECOLHENDO'}<br>
          <b>Linha:</b> ${veiculo.NOME_LINHA || 'RECOLHENDO'} - ${veiculo.CODIGOLINHA}<br>
          <b>Sentido:</b> ${veiculo.SENT}<br>
          <b>Última atualização:</b> ${veiculo.REFRESH}<br>
          <b>Situação:</b> ${veiculo.SITUACAO2}<br>
          <b>Status:</b> ${veiculo.STATUS}<br>
          <b><a class="" onclick="javascript:mapa.setZoom(18);startTracking('${veiculo.COD}')">Rastrear este ônibus</a></b><br>
          <b><a class="" onclick="javascript:stopTracking();mapa.setZoom(16)">Parar rastreamento</a></b>
          `);
      }
    });
  },

  // Iniciar a busca periódica de dados
  iniciar: function () {
    this.buscarDados(); // Buscar imediatamente ao iniciar
    setInterval(() => this.buscarDados(), this.intervalo); // Buscar a cada intervalo
  }
};

// Iniciar o fetch periódico
veiculosDataFetcher.iniciar();
// Exibir ônibus por padrão (marcadores são adicionados em onibusUrbs; sem isso a camada fica desligada até o usuário marcar no controle)
mapa.addLayer(onibusUrbs);
    </script>


    <script>
        function getCameraPoits(pontoA){  // retorna os pontos para definiço do tamanho do video no mapa

            var pontoB = [((pontoA[0])-(-0.0045)),((pontoA[1])-(-0.0085))]

            console.log("pontoA",pontoA);
            console.log("pontoB",pontoB);

            var northEast = [((pontoA[0]+pontoB[0])/2),((pontoA[1]+pontoB[1])/2)]
            var southWest = [((northEast[0])+(-0.0045)),((northEast[1])+(-0.0085))]

            console.log(northEast,southWest);
            return [southWest,northEast]
        }

        //Insere viatura no mapa
        function isValidCameraLatLng(lat, lng) {
            if (lat === null || lat === undefined || lng === null || lng === undefined) {
                return false;
            }
            var sLat = String(lat).trim();
            var sLng = String(lng).trim();
            if (sLat === '' || sLng === '') {
                return false;
            }
            // Erros comuns de planilha (fórmula quebrada)
            if (/^#/.test(sLat) || /^#/.test(sLng)) {
                return false;
            }
            var la = parseFloat(sLat.replace(',', '.'));
            var lo = parseFloat(sLng.replace(',', '.'));
            if (!isFinite(la) || !isFinite(lo)) {
                return false;
            }
            if (la < -90 || la > 90 || lo < -180 || lo > 180) {
                return false;
            }
            return true;
        }

        function addCameraMark(obj){

            if (!isValidCameraLatLng(obj.lat, obj.lng)) {
                return;
            }

            if(obj.ativo){

                var camIcon = L.icon({
                    iconUrl:'{{asset('images/cam.png')}}',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });

            }else{
                var camIcon = L.icon({
                    iconUrl:'{{asset('images/camoff.png')}}',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });
            }

            var marker = L.marker([parseFloat(String(obj.lat).replace(',', '.')), parseFloat(String(obj.lng).replace(',', '.'))], {
                contextmenu: true,
                contextmenuItems: [{
                    text: 'Circle 1',
                    callback: function() {
                        showCharts(circle);
                    }
                }],
                icon: camIcon,
                rotationAngle: 0
            }).on('click',function(){

                adicionarCameraAoMosaico(obj);

            }).on('contextmenu', function(event){


                $('.marker-context-menu').remove(); // Remove anteriores

                console.log(event);
                //Adiciona Novo
                $('#mapa').append(`<div class="marker-context-menu" id="markerMenu" style="left: ${event.originalEvent.pageX-230}px;top: ${event.originalEvent.pageY-60}px;display:block;">
                    <ul>
                        @if ($Autorizacao->can(['Administrador']))
                            <li><a href="/cameras/${obj.id}/edit">Editar</a></li>
                            <li><a href="#action2">Habilitar/Desabilitar</a></li>
                        @endif
                        <li><a data-camera="${btoa(JSON.stringify(obj))}" onclick="showAddMosaicoView(this);" href="#">Adicionar ao mosaico</a></li>
                    </ul>
                    <span>Latitude: ${event.latlng.lat}, Longitude: ${event.latlng.lng}</span>
                </div>`);

                event.originalEvent.preventDefault();

            })
            .setRotationOrigin('center');

            marker.cameraData = obj;
            layersCameras.addLayer(marker)

        }


        function TimerBuscaCameras()
        {


            $.ajax({
                url:'{{route('cameras.index')}}',
                success: function(cameras){
                    console.log(cameras);
                    cameras.forEach(element => {
                        addCameraMark(element)
                    });
                    mapa.addLayer(layersCameras);
                },
                error: function(err){
                    alert("Erro ao obter câmeras")
                }
            });

            /*     var request = new XMLHttpRequest();
                const method = 'GET'
                const url = 'cameras.json'
 */
                /* new Promise(function(resolv,reject){

                    request.onreadystatechange = function() {

                        if (this.readyState == 4 && this.status == 200) {

                            var json = JSON.parse(this.responseText);

                            if(json.length > 0){
                                resolv(json)
                            }
                            else{

                                reject(Error('Erro ao buscar cameras'))
                            }

                        }

                    };

                    // Setup our HTTP request
                    request.open(method || 'GET', url, true);

                    // Setup request headers
                    //request.setRequestHeader('Authorization', 'Basic aW50ZWdyYWNhbzpzaXNjb3A=');

                    // On network error
                    request.onerror = function() {
                        reject(Error("{\"status\":\"Erro de rede\"}"));
                    };

                    // Send the request
                    request.send()

                }).then(function(dados){
                   alert(1);
                    var cameras = dados

                    dados.forEach(element => {
                        addCameraMark(element)
                    });


                }),function(err){
                    alert(err)
                    setTimeout(TimerBuscaCameras, 3000);
                } */

        }


       TimerBuscaCameras();
    </script>

    <script>
        // ─── Mosaico Inline ───────────────────────────────────────────────

        function adicionarCameraAoMosaico(camera) {
            if (!camera.link || camera.link === '#' || String(camera.link).trim() === '') {
                toastr.warning('Esta câmera não possui link de stream configurado.');
                return;
            }
            if (mosaicoCameras[camera.id]) {
                var tile = document.getElementById('camera-tile-' + camera.id);
                if (tile) tile.scrollIntoView({ behavior: 'smooth', block: 'center' });
                toastr.info('Câmera já está no mosaico.');
                return;
            }
            if (Object.keys(mosaicoCameras).length >= MAX_CAMERAS_MOSAICO) {
                toastr.warning('Limite de ' + MAX_CAMERAS_MOSAICO + ' câmeras no mosaico atingido. Remova uma para adicionar outra.');
                return;
            }

            mosaicoCameras[camera.id] = camera;
            renderizarMosaicoInline();
            atualizarLayoutMosaico();

            document.getElementById('mosaico-inline-toolbar').style.display = 'block';
            document.getElementById('mosaico-inline').style.display = 'flex';
            setTimeout(function () {
                document.getElementById('mosaico-inline').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }

        function removerCameraDoMosaico(cameraId) {
            delete mosaicoCameras[cameraId];
            var count = Object.keys(mosaicoCameras).length;
            if (count === 0) {
                document.getElementById('mosaico-inline').style.display = 'none';
                document.getElementById('mosaico-inline-toolbar').style.display = 'none';
                document.getElementById('mosaico-inline').innerHTML = '';
            } else {
                renderizarMosaicoInline();
            }
            atualizarLayoutMosaico();
        }

        function renderizarMosaicoInline() {
            var container = document.getElementById('mosaico-inline');
            var cameras   = Object.values(mosaicoCameras);
            var count     = cameras.length;

            var colClass = count <= 1 ? 'col-12'
                         : count <= 4 ? 'col-12 col-md-6'
                         : 'col-12 col-md-4';

            container.innerHTML = '';
            cameras.forEach(function (cam) {
                var div = document.createElement('div');
                div.className = colClass + ' camera-tile';
                div.id = 'camera-tile-' + cam.id;
                div.innerHTML =
                    '<div class="tile-header">' +
                        '<span title="' + cam.local_nome + '">' + cam.local_nome + '</span>' +
                        '<button onclick="removerCameraDoMosaico(' + cam.id + ')" title="Remover">' +
                            '<i class="fas fa-times"></i>' +
                        '</button>' +
                    '</div>' +
                    '<img src="' + cam.link + '" alt="' + cam.local_nome + '" ' +
                         'onerror="this.src=\'{{ asset(\'images/camoff.png\') }}\'">';
                container.appendChild(div);
            });

            document.getElementById('mosaico-inline-count').textContent =
                count + ' câmera(s) carregada(s)';
        }

        function atualizarLayoutMosaico() {
            var count = Object.keys(mosaicoCameras).length;
            document.getElementById('mapa').style.height = count > 0 ? '60vh' : '93vh';
            if (typeof mapa !== 'undefined') {
                setTimeout(function () { mapa.invalidateSize(); }, 50);
            }
        }

        document.getElementById('btn-limpar-mosaico').addEventListener('click', function () {
            mosaicoCameras = {};
            document.getElementById('mosaico-inline').style.display = 'none';
            document.getElementById('mosaico-inline-toolbar').style.display = 'none';
            document.getElementById('mosaico-inline').innerHTML = '';
            atualizarLayoutMosaico();
        });

        // ─── Seleção por área (Leaflet.draw) ─────────────────────────────

        var drawLayer   = null;
        var drawControl = null;

        // Inicializado após o mapa estar pronto
        window.addEventListener('load', function () {
            drawLayer = new L.FeatureGroup().addTo(mapa);
        });

        document.getElementById('btn-selecao-area').addEventListener('click', function () {
            if (drawControl) {
                drawControl.disable();
                drawControl = null;
                this.classList.remove('ativo');
                return;
            }

            if (!drawLayer) drawLayer = new L.FeatureGroup().addTo(mapa);

            this.classList.add('ativo');
            toastr.info('Desenhe um retângulo no mapa para selecionar câmeras.', '', { timeOut: 4000 });

            drawControl = new L.Draw.Rectangle(mapa, {
                shapeOptions: { color: '#f39c12', weight: 2, fillOpacity: 0.1 }
            });
            drawControl.enable();
        });

        mapa.on(L.Draw.Event.CREATED, function (event) {
            var bounds = event.layer.getBounds();
            drawLayer.clearLayers();
            drawControl = null;
            document.getElementById('btn-selecao-area').classList.remove('ativo');

            var selecionadas = 0;
            layersCameras.eachLayer(function (marker) {
                if (marker.cameraData && bounds.contains(marker.getLatLng())) {
                    adicionarCameraAoMosaico(marker.cameraData);
                    selecionadas++;
                }
            });

            if (selecionadas === 0) {
                toastr.warning('Nenhuma câmera encontrada na área selecionada.');
            } else {
                toastr.success(selecionadas + ' câmera(s) adicionada(s) ao mosaico.');
            }
        });
    </script>
@stop
