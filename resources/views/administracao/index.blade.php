@extends('adminlte::page')

@section('title', 'Administração')

@section('content_header')
    {{ Breadcrumbs::render('administracao') }}
@stop

@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-primary ">
            <div class="card-header">
                <h3 class="card-title">Comando Geral</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/acesso/"              >COGER - SITE</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/CJ/"                  >Consultoria Jurídica</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/CPO/index.php"        >CPO - Almanaque</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/CPP/index.php"        >CPP - Almanaque</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.65:8080/arquivogeral/"   >Arquivo Geral</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/arquivo_geral/"       >Arquivo Geral - Documentos</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-secondary ">
            <div class="card-header">
                <h3 class="card-title">APMG</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.147.242.41/"                              >Controle de Aulas</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.1.8/concurso/"                         >Sistema de Concursos</a>
                {{--  <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.1.19/PMPR/em/museu/museu.html"   >Museu Online</a>  --}}
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DEP/"                             >Documentos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DEP/Requerimento/"                >Requerimentos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.65:8080/taf/"                        >CEFID - TAF</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://www.apmg.pr.gov.br/Pagina/Agenda-SEF"        >SEF - AGENDA</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/academia/APMG/"                   >Boletins Internos</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-success ">
            <div class="card-header">
                <h3 class="card-title">Estado Maior</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/PM1/Legislacao/"  >PM1 - Documentos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.147.31.136/fenix/fmi.php" >PM2 - Formulário</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/PM3/"             >PM3 - Documentos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/PM4/"             >PM4 - Documentos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/PM5/"             >PM5 - Documentos</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-info">
            <div class="card-header">
                <h3 class="card-title">DF</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DF/DF-DOCUMENTOS/"                                                                                   >DF - Documentos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://www.gms.pr.gov.br/gms//"                                                                                        >GMS</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://www.comprasparana.pr.gov.br//"                                                                                  >Compras Paraná</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://www.transparencia.pr.gov.br/pte/pages/compras/precos_registrados/listar_precos_registrados.jsf?windowId=0de/"   >Portal da Transparência</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://www.centraldeviagens.pr.gov.br/"                                                                               >SEAP - Central de Viagens</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://www.gestaofinanceira.pr.gov.br/gestaofinanceira/entrada.do?&action=login/"                                      >GRF</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://bi.redeexecutiva.pr.gov.br/qlikview/FormLogin.htm"                                                             >BI</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://intranet.pmpr.parana/?p=470740"                                                                                 >Certidões de Débitos(links)</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-warning ">
            <div class="card-header">
                <h3 class="card-title">DP</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.1.8/pm/consultapm.php"                                         >Consulta Efetivo</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.1.8/sispics"                                                   >Sistema de Fotografias</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.64:8080/operacaoverao/"                                      >Voluntários Operação Verão</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/dp"                                                       >Formulários</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://bi.celepar.parana/qlikview/FormLogin.htm"                           >BI</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.64:8080/sysbehaver/"                                         >SAS - SysBehaver</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.65:8080/inativos/"                                           >Arquivo de Inativos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.65:8080/crs/"                                                >CRS</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://drive.google.com/file/d/1eF9BunzCvb4hL2r2ck35iRy2hsPYsNA5/view"     >Manual para Reserva</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.65:8080/consulta/"                                           >Identificação</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-danger ">
            <div class="card-header">
                <h3 class="card-title">DDTQ</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://bi.celepar.parana/qlikview/FormLogin.htm"                                                   >Celepar - BI</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://intranet.pmpr.parana/?cat=9"                                                                 >Supoorte Técnico</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DDTQ/Documentos%20para%20Software"                                                >Documentos para Software</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DDTQ/Termos%20e%20Manuais"                                                        >Termos, Manuais e Gestores</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DDTQ/AVL%20-%20Helios"                                                            >Manuais AVL - Hélios</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLScTaog8XbnMs-a_bdtGlAoBz-6blbLaJUeT4wlpprqwuQDRWw/viewform">Registro Manutenção AVL</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://docs.google.com/spreadsheets/d/1RfqLiTgU14LBPp1lG3lmQi_iAjfxYuWM8SdJpq2pM0M"                >Planilha Manutenção AVL</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://helios.sesp.pr.gov.br//HeliosInstaller/Helios%20Installer.msi"                              >Download Helios AVL</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.50/2crpm/leitorsd/sdreader.jar"                                                      >Aplicativo Leitor SD CARD AVL</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://im.pm.pr.gov.br:9090/login"                                                                  >Gestor Mobile</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://drive.google.com/file/d/0B-bw1p2Ph239U1N3aDd5T1F1blE/view?usp=drivesdk%22"                  >PMPR Messenger</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-dark ">
            <div class="card-header">
                <h3 class="card-title">DAL</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DAL/"                                                  >Documentos</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.147.242.21/patrimonios/"                                       >Sistema de Patrimônio</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.1.8/viaturas/security_login/security_login.php"             >Sistema de Viaturas</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.1.8/inventario/"                                            >Inventário</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://sigap.pm.pr.gov.br"                                                 >SIGAP</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DAL/SAM/"                                              >SAM</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DAL/INTENDENCIA/"                                      >INTENDENCIA</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://www.gpm.pr.gov.br/gpm/pages/initial/initial.jsf?windowId=991"     >GPM - Patrimônio PR</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-dark ">
            <div class="card-header">
                <h3 class="card-title">Assessoria DETRAN</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Administracao"           >Administração</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Agentes%20de%20Transito" >Agentes de Trânsito</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Bateu%20-%20PMPR/"       >Bateu - PMPR</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Fiscalizacao/"           >Fiscalização</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Formularios/"            >Formulários</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Informacoes/"            >Informações</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Legislacao/"             >Legislação</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/documentos/Secretaria/"             >Secretaria</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-dark ">
            <div class="card-header">
                <h3 class="card-title">Diversos</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://intranet.pmpr.parana/?p=9396" >Banda(Hinos)</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.1.8/matrix/"            >SISCAP</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.247.121.21/siscac/"        >SISCAC</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.147.242.21/sae/"           >SAE</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.147.242.21/recop/"         >RECOP</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="hhttp://www.policiamilitar.pr.gov.br">Site PMPR</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline collapsed-card card-dark ">
            <div class="card-header">
                <h3 class="card-title">DS</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="https://hpm.pr.gov.br/"                       >HPM</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.65:8080/saudepreventiva"       >Programa de Saúde Preventiva</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.64:8080/prontuarioeletronico/" >Prontuário Eletrônico</a>
                <a class="btn btn-block btn-outline-secondary btn-xs" target="_blank" href="http://10.47.0.26/DS-JM/"                     >Junta Médica</a>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    
@stop

@section('js')

@stop

