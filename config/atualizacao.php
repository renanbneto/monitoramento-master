<?php

return [

        // inicializador
        'initsh' => ['rota' => 'api/update/initsh', 'arquivo' => 'init.sh' , 'origem' => 'projeto','destino' => 'projeto'], // Atualiza arquivo de inicialização para desenv
        'stopsh' => ['rota' => 'api/update/stopsh', 'arquivo' => 'stop.sh' , 'origem' => 'projeto','destino' => 'projeto'], // Atualiza arquivo de inicialização para desenv
        'restartsh' => ['rota' => 'api/update/restartsh', 'arquivo' => 'restart.sh' , 'origem' => 'projeto','destino' => 'projeto'], // Atualiza arquivo de inicialização para desenv
        'envexampleremoto' => ['rota' => 'api/update/envexampleremoto', 'arquivo' => '.env.example.remoto' , 'origem' => 'projeto','destino' => 'projeto'], // Exemplo de enviroment
        'routesprovider' => ['rota' => 'api/update/routesprovider', 'arquivo' => 'app/Providers/RouteServiceProvider.php' , 'origem' => 'projeto','destino' => 'projeto'], // Exemplo de enviroment

        //Controllers de API
        'controllerapis' => ['rota' => 'api/update/controllerapis', 'arquivo' => 'app/apis/Api.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisQO' => ['rota' => 'api/update/controllerapisQO', 'arquivo' => 'app/apis/Qo.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisP1' => ['rota' => 'api/update/controllerapisP1', 'arquivo' => 'app/apis/P1.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisDados' => ['rota' => 'api/update/controllerapisDados', 'arquivo' => 'app/apis/Dados.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisSiaUi' => ['rota' => 'api/update/controllerapisSiaUi', 'arquivo' => 'app/apis/Sia-Ui.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisSiaAuth' => ['rota' => 'api/update/controllerapisSiaAuth', 'arquivo' => 'app/apis/Sia-Auth.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisNotas' => ['rota' => 'api/update/controllerapisNotas', 'arquivo' => 'app/apis/Notas.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisExpresso' => ['rota' => 'api/update/controllerapisExpresso', 'arquivo' => 'app/apis/Expresso.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisP4' => ['rota' => 'api/update/controllerapisP4', 'arquivo' => 'app/apis/P4.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisNotificacoes' => ['rota' => 'api/update/controllerapisNotificacoes', 'arquivo' => 'app/apis/Notificacoes.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'controllerapisSargenteacao' => ['rota' => 'api/update/controllerapisSargenteacao', 'arquivo' => 'app/apis/Sargenteacao.php', 'origem' => 'projeto', 'destino' => 'projeto'], 
        
        
        //Login controller do template
        'logincontroller' => ['rota' => 'api/update/logincontroller', 'arquivo' => 'LoginController.php', 'origem' => 'controller', 'destino' => 'controller'], 
        'NotificacoesController' => ['rota' => 'api/update/NotificacoesController', 'arquivo' => 'NotificacoesController.php', 'origem' => 'controller', 'destino' => 'controller'], 
        
        //Controller de autorização
        'autorizacaocontroller' => ['rota' => 'api/update/autorizacaocontroller', 'arquivo' => 'Autorizacao.php', 'origem' => 'controller', 'destino' => 'controller'], 
        'gatefilteradminlte' => ['rota' => 'api/update/gatefilteradminlte', 'arquivo' => 'vendor/jeroennoten/laravel-adminlte/src/Menu/Filters/GateFilter.php' , 'origem' => 'projeto','destino' => 'projeto'],
        
        //Controller de autorização
        
        //Template Configs comum
        
        'atualizacao' => ['rota' => 'api/update/atualizacao', 'arquivo' => 'atualizacao.php' , 'origem' => 'config','destino' => 'config'], // Atualiza a lista de atuaizações
        
        'captcha' => ['rota' => 'api/update/captcha', 'arquivo' => 'captcha.php' , 'origem' => 'config','destino' => 'config'], // Atualiza a lista de atuaizações
        
        'sistemas' => ['rota' => 'api/update/sistemas', 'arquivo' => 'sistemas.php' , 'origem' => 'config','destino' => 'config'], // Rota Sia para atualização da relação de sistemas
        
        'sistemasView' => ['rota' => 'api/update/sistemasView', 'arquivo' => 'sistemas/index.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'sistemasNI' => ['rota' => 'api/update/sistemasNI', 'arquivo' => 'sistemas/naoIntegrados.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'sistemasMonitoramento' => ['rota' => 'api/update/sistemasMonitoramento', 'arquivo' => 'sistemas/monitoramento.blade.php' , 'origem' => 'views','destino' => 'views'], 
        
        
        'login' => ['rota' => 'api/update/login', 'arquivo' => 'login.blade.php', 'origem' => 'login', 'destino' => 'login'], // Rota Sia para atualização do login
        
        'adminlte' => ['rota' => 'api/update/adminlte', 'arquivo' => 'adminlte.php', 'origem' => 'config', 'destino' => 'config'], // Atualiza as configurações comuns do adminLTE
        
        //Atualização de view components
        'minhasMissoes' => ['rota' => 'api/update/minhasMissoes', 'arquivo' => 'components/minhasMissoes.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'notasParaIntranet' => ['rota' => 'api/update/notasParaIntranet', 'arquivo' => 'components/notasParaIntranet.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'indexHome' => ['rota' => 'api/update/indexHome', 'arquivo' => 'home/index.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'extrajornada' => ['rota' => 'api/update/extrajornada', 'arquivo' => 'components/extrajornada.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'indexEmail' => ['rota' => 'api/update/indexEmail', 'arquivo' => 'email/index.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'cardmissoes' => ['rota' => 'api/update/cardmissoes', 'arquivo' => 'components/cardMissoes.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'cardnotas' => ['rota' => 'api/update/cardnotas', 'arquivo' => 'components/cardNotasIntranet.blade.php' , 'origem' => 'views','destino' => 'views'], 
        'imagesbackgroundSIAF' => ['rota' => 'api/update/imagesbackgroundSIAF', 'arquivo' => 'public/images/backgroundSIAF.jpg', 'origem' => 'projeto', 'destino' => 'projeto'], 
        'imagesbackgroundTermoDEAEV' => ['rota' => 'api/update/imagesbackgroundTermoDEAEV', 'arquivo' => 'public/images/backgroundTermoDEAEV.jpg', 'origem' => 'projeto', 'destino' => 'projeto'], 
        
        //Template Controllers Comuns
        
        //Perfil
        
        'perfil' => ['rota' => 'api/update/perfil', 'arquivo' => 'PerfilController.php', 'origem' => 'controller', 'destino' => 'controller'], // Atualiza Controller Perfil

        //API SIA
        
        'apisia' => ['rota' => 'api/update/apisia', 'arquivo' => 'SiaAPI.php', 'origem' => 'controller', 'destino' => 'controller'], // Atualiza a API SIA

        //API EXPRESSO

        'apiexpresso' => ['rota' => 'api/update/apiexpresso', 'arquivo' => 'ExpressoAPI.php', 'origem' => 'controller', 'destino' => 'controller'], // Atualiza a API EXPRESSO

        'emailexpresso' => ['rota' => 'api/update/emailexpresso', 'arquivo' => 'EmailExpressoController.php', 'origem' => 'controller', 'destino' => 'controller'], // Atualiza o controlador de acesso ao email expresso
    
        'emailsjs' => ['rota' => 'api/update/emailsjs', 'arquivo' => 'js/emails.js', 'origem' => 'assets', 'destino' => 'assets'], // framework email
    //Template BreadCrumbs
    
        'breadcrumbs' => ['rota' => 'api/update/breadcrumbs', 'arquivo' => 'breadcrumbs/BreadCrumbsController.php', 'origem' => 'controller', 'destino' => 'controller'], // Atualiza a classe breadcrumb Global

    //Templates CSS e JS

        'intranetTemplatecss' => ['rota' => 'api/update/intranetTemplatecss', 'arquivo' => 'css/intranetTemplate.css', 'origem' => 'assets', 'destino' => 'assets'], // Atualiza IntranetTemplate.css do template
        
        'intranetTemplatejs' => ['rota' => 'api/update/intranetTemplatejs', 'arquivo' => 'js/intranetTemplate.js', 'origem' => 'assets', 'destino' => 'assets'], // Atualiza IntranetTemplate.js do template
        
    //Templates ADMINLTE

        'master' => ['rota' => 'api/update/master', 'arquivo' => 'master.blade.php', 'origem' => 'adminlte', 'destino' => 'adminlte'], // Atualiza AdminLTE
        
        'page' => ['rota' => 'api/update/page', 'arquivo' => 'page.blade.php', 'origem' => 'adminlte', 'destino' => 'adminlte'], // Atualiza AdminLTE
        
        'plugins' => ['rota' => 'api/update/plugins', 'arquivo' => 'plugins.blade.php', 'origem' => 'adminlte', 'destino' => 'adminlte'], // Atualiza AdminLTE
        
        'brand-logo-xl' => ['rota' => 'api/update/brand-logo-xl', 'arquivo' => 'partials/common/brand-logo-xl.blade.php', 'origem' => 'adminlte', 'destino' => 'adminlte'], // Atualiza AdminLTE
        
        'brand-logo-xs' => ['rota' => 'api/update/brand-logo-xs', 'arquivo' => 'partials/common/brand-logo-xs.blade.php', 'origem' => 'adminlte', 'destino' => 'adminlte'], // Atualiza AdminLTE
        
        'basetemplate' => ['rota' => 'api/update/basetemplate', 'arquivo' => 'base.blade.php' , 'origem' => 'views','destino' => 'views'], 
    //Arquivo de Rotas
        
        'rotasTemplate' => ['rota' => 'api/update/rotasTemplate', 'arquivo' => 'rotasTemplate.php', 'origem' => 'routes', 'destino' => 'routes'], // Atualiza AdminLTE
        'rotasServicos' => ['rota' => 'api/update/rotasServicos', 'arquivo' => 'servicos.php', 'origem' => 'routes', 'destino' => 'routes'], // Atualiza AdminLTE
    
    //Arquivo de Menu Local
        
        'menuItemLocal' => ['rota' => 'api/update/menuItemLocal', 'arquivo' => 'partials/sidebar/menu-item-local.blade.php', 'origem' => 'adminlte', 'destino' => 'adminlte'], // Atualiza template menu-item -local(menu customizado)

];
