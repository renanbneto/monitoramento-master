<link rel="stylesheet" href="{{ asset('vendor/ion-rangeslider/css/ion.rangeSlider.min.css') }}"/>
<script type="text/javascript" src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script> 

<style>
    .overlay{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 999999999999999999;
        background-color: rgba(0,0,0,0.5); /*dim the background*/
    }
    time.icon
  {
    font-size: 1em; /* change icon size */
    display: block;
    position: relative;
    width: 5em;
    height: 5em;
    background-color: #fff;
    border-radius: 0.6em;
    box-shadow: 0 1px 0 #bdbdbd, 0 2px 0 #fff, 0 3px 0 #bdbdbd, 0 4px 0 #fff, 0 5px 0 #bdbdbd, 0 0 0 1px #bdbdbd;
    overflow: hidden;
  }
  
  time.icon *
  {
    display: block;
    width: 100%;
    font-size: 1em;
    font-weight: bold;
    font-style: normal;
    text-align: center;
  }
  
  time.icon strong
  {
    position: absolute;
    top: 0;
    color: #fff;
    background-color: #fd9f1b;
  }
  
  time.icon em
  {
    position: absolute;
    bottom: -0.1em;
    color: #fd9f1b;
  }
  
  time.icon span
  {
    font-size: 1.6em;
    letter-spacing: -0.05em;
    padding-top: 0.9em;
    color: #2f2f2f;
  }
</style>

<div id="containerEscalas" class="col-md-12"></div>

<script>

    const getContainer = (regime) => {
        switch(regime){
            case 1:{
                return `
                <div class="col">
                    <div class="card" id="containerRegime${regime}">
                        <div class="card-header">
                          <h3 class="card-title">Escalas Extrajornadas - Voluntariado Diário</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Minimizar">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0" style="display: block;">
                            <table class="table table-striped projects">
                                <thead>
                                    
                                </thead>
                                <tbody id="containerRegimeBody${regime}">                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                `;
                break;
            }
            case 7:{
                return `
                <div class="col">
                    <div class="card" id="containerRegime${regime}">
                        <div class="card-header">
                          <h3 class="card-title">Escalas Extrajornadas - Voluntariado Semanal</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Minimizar">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0" style="display: block;">
                            <table class="table table-striped projects">
                                <thead>
                                    
                                </thead>
                                <tbody id="containerRegimeBody${regime}">                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                `;
                break;
            }
            case 15:{
                return `
                <div class="col">
                    <div class="card" id="containerRegime${regime}">
                        <div class="card-header">
                          <h3 class="card-title">Escalas Extrajornadas - Voluntariado Quinzenal</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Minimizar">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0" style="display: block;">
                            <table class="table table-striped projects">
                                <thead>
                                    
                                </thead>
                                <tbody id="containerRegimeBody${regime}">                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                `;
                break;
            }
            case 30:{
                return `
                <div class="col">
                    <div class="card" id="containerRegime${regime}">
                        <div class="card-header">
                          <h3 class="card-title">Escalas Extrajornadas - Voluntariado Mensal</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Minimizar">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0" style="display: block;">
                            <table class="table table-striped projects">
                                <thead>
                                    
                                </thead>
                                <tbody id="containerRegimeBody${regime}">                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                `;
                break;
            }
        }
    }

    const addRegimeContainer = (regime) => {

        if( !$('#containerRegime'+regime).length ){
            // adicionar o container do regime
            let containerRegime = $.parseHTML(getContainer(regime));
            $("#containerEscalas").append(containerRegime);
        }

        return $('#containerRegime'+regime+' #containerRegimeBody'+regime);

    }

    const geraElemento = (tipo,escala) => {

        const data = moment(escala.data);
        const data_final  = escala.data_final ? moment(escala.data_final) : null;
        const dia = data.format("DD");
        const dia_fim  = data_final ? data_final.format("DD") : null;
        const ano = data.format("YYYY");
        const mes = data.format("MMMM");
        const hora = data.format("HH:mm");
        let diaSemana = data.format('dddd').split('-')[0];
        diaSemana = diaSemana.charAt(0).toUpperCase() + diaSemana.slice(1);
        
        if(tipo == 0 && escala.regime == 1){
            return `
            <tr>
              <td>
                <form id="form${escala.id}" class="formVoluntariar">
                    @csrf
                    <input type="hidden" id="escala_id" value="${escala.id}">
                    <input type="hidden" id="quantidade" value="1">
                  <div class="card">
                      
                      <div class="card-body">

                        <div class="row">
                          
                          <div class="col-md-2 d-inline-flex justify-content-center align-items-center">
                            <time datetime="2014-09-20" class="icon">
                              <em>${mes}</em>
                              <strong>${diaSemana}</strong>
                              <span>${dia}</span>
                            </time>
                          </div>

                          <div class="col-md-10 d-inline-flex flex-column justify-content-center align-items-center">
                            <h5 class="w-100 card-header bg-info" style="text-align: center;justify-content: center;align-items: center;">${escala.descricao}   -  às ${hora} horas</h5>
                            <p class="w-100" style="margin-top: 0;margin-bottom: 5px;">Fechamento do voluntariado dia ${escala.fechamento}</p>
                            <p class="w-100" style="margin-top: 0;margin-bottom: 0;">Apresentação ${escala.local}</p>
                          </div>
                        </div>

                        
                        <div style="margin: 8px;"><small id="prefLink${escala.id}" onclick="javascript:(() => {$('#pref${escala.id}').show();$(this).hide();})();" style="cursor: pointer;"><i class="fas fa-plus"></i> Preferências</small></div>
                        
                        <div id="pref${escala.id}" class="card" style="display: none;">
                          <div class="card-header bg-info p-1">
                            <h3 class="card-title mb-0">Preferências</h3>
                            
                            <div class="card-tools">
                              <button onclick="javascript:(() => {$('#pref${escala.id}').hide();$('#prefLink${escala.id}').show();})();" type="button" class="btn btn-tool" data-card-widget="remove" title="Fechar">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>
  
                          <div class="card-body" style="background-color: aliceblue;">
  
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Minha previsão de escala</label>
                            <input id="previsao_escala" type="text" class="form-control dateSingle" placeholder="Clique e informe os dias que estará escalado">
                          </div>
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Cidade ou localidade de preferência</label>
                            <input id="localidade" type="text" class="form-control" placeholder="Digite o nome da cidade ou localidade que gostaria de cumprir a escala">
                          </div>
                         
                          <div class="form-group">
                            <label for="exampleInputEmail1">Período</label>
                            <select name="" id="periodo" class="form-control" placeholder="Periodo preferêncial">
                            </div>
                           
                              <option value="Qualquer" selected>Qualquer</option>
                              <option value="Matutino">Matutino</option>
                              <option value="Vespertino">Vespertino</option>
                              <option value="Noturno">Noturno</option>
                            </select>
                          </div>
  
                          </div>
  
                        </div>
                       
                        
                        <div style="display: flex;align-items: center;justify-content: center;">
                          <input type="submit" class="btn btn-primary" value="Quero ser voluntário">
                        </div>
                      </div>
                    </div>
                </form>
              </td>
            </tr>
            `;
        }

        if(tipo == 1 && escala.regime == 1){
            return `
            <tr>
                <td>
                    <form class="formRemoverVoluntario">
                        @csrf
                        <input type="hidden" id="escala_id" value="${escala.id}">
                    <div class="card">
                        
                        <div class="card-body">

                            <div class="row">
                            
                                <div class="col-md-2 d-inline-flex justify-content-center align-items-center">
                                    <time datetime="2014-09-20" class="icon">
                                        <em>${mes}</em>
                                        <strong>${diaSemana}</strong>
                                        <span>${dia}</span>
                                    </time>
                                </div>

                           

                            <div class="col-md-10 d-inline-flex flex-column justify-content-center align-items-center">
                                <h5 class="w-100 card-header bg-success" style="text-align: center;justify-content: center;align-items: center;">${escala.descricao}  -  às ${hora} horas</h5>
                                <p class="w-100" style="margin-top: 0;margin-bottom: 5px;">Fechamento do voluntariado dia ${escala.fechamento}</p>
                                <p class="w-100" style="margin-top: 0;margin-bottom: 0;">Apresentação ${escala.local}</p>
                            </div>

                            </div>
                            <div style="display: flex;align-items: center;justify-content: center;">
                            <input type="submit"class="btn btn-danger" value="Deixar de ser voluntário">
                            </div>

                        </div>
                    </div>
                    </form>
                </td>
            </tr>
            `;
        }

        if(tipo == 0 && escala.regime == 7){
            return `

            <tr>
                <td>
                    <form id="form${escala.id}" class="formVoluntariar">
                    
                        @csrf
                        <input type="hidden" id="escala_id" value="${escala.id}">

                    <div class="card">
                        <h5 class="card-header bg-info">${escala.escala_periodo} de ${mes} de ${ano}</h5>
                        <div class="card-body">
                          <h5 class="card-title">${escala.descricao} para os dias ${dia} à ${dia_fim}</h5>
                          <p class="card-text">Fechamento do voluntariado dia ${escala.fechamento}</p>
                          <div class="form-group" style="margin-bottom: 0px;">
                            <input class="js-range-sliderSemanal" type="text" id="quantidade" name="quantidade" value="${escala.quantidade}" class="irs-hidden-input" tabindex="-1" readonly="">
                            <small id="cotasHelp" class="form-text text-muted">Selecione a quantidade máxima de escalas que deseja tirar para o mês.</small>
                          </div>
    
                          <div id="pref${escala.id}" class="card" style="display: none;">
                          <div class="card-header bg-info p-1">
                            <h3 class="card-title mb-0">Preferências</h3>
                            
                            <div class="card-tools">
                              <button onclick="javascript:(() => {$('#pref${escala.id}').hide();$('#prefLink${escala.id}').show();})();" type="button" class="btn btn-tool" data-card-widget="remove" title="Fechar">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>
  
                          <div class="card-body" style="background-color: aliceblue;">
  
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Minha previsão de escala</label>
                            <input id="previsao_escala" type="text" class="form-control dateSingle" placeholder="Clique e informe os dias que estará escalado">
                          </div>
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Cidade ou localidade de preferência</label>
                            <input id="localidade" type="text" class="form-control" placeholder="Digite o nome da cidade ou localidade que gostaria de cumprir a escala">
                          </div>
                         
                          <div class="form-group">
                            <label for="exampleInputEmail1">Período</label>
                            <select name="" id="periodo" class="form-control" placeholder="Periodo preferêncial">
                            </div>
                           
                              <option value="Qualquer" selected>Qualquer</option>
                              <option value="Matutino">Matutino</option>
                              <option value="Vespertino">Vespertino</option>
                              <option value="Noturno">Noturno</option>
                            </select>
                          </div>
  
                          </div>
  
                        </div>
                       
                        
                        <div style="display: flex;align-items: center;justify-content: center;">
                          <input type="submit" class="btn btn-primary" value="Quero ser voluntário">
                        </div>
                          
                        </div>
                    </div>
                    </form>
                </td>
            </tr>

            `;
        }
                      
        if(tipo == 1 && escala.regime == 7){
            return `
            <tr>
                <td>
                    <form class="formRemoverVoluntario">
                        @csrf
                        <input type="hidden" id="escala_id" value="${escala.id}">
                    
                        <div class="card">
                            <h5 class="card-header bg-success">${escala.escala_periodo} de ${mes} de ${ano}</h5>
                            <div class="card-body bg">
                                <h5 class="card-title">${escala.descricao} para os dias ${dia} à ${dia_fim}</h5>
                                <p class="card-text">Você é voluntário para ${escala.quantidade} escalas extras na ${escala.escala_periodo} de ${mes} ${ano}</p>
                                <div style="display: flex;align-items: center;justify-content: center;">
                                    <input type="submit"class="btn btn-danger" value="Deixar de ser voluntário">
                                </div>
                            </div>
                        </div>  
                    </form>
                </td>
            </tr>
            `;
        }

        if(tipo == 0 && escala.regime == 15){
            return `

            <tr>
                <td>
                    <form id="form${escala.id}" class="formVoluntariar">
                    
                        @csrf
                        <input type="hidden" id="escala_id" value="${escala.id}">

                    <div class="card">
                        <h5 class="card-header bg-info">${escala.escala_periodo} de ${mes} de ${ano}</h5>
                        <div class="card-body">
                          <h5 class="card-title">${escala.descricao} para os dias ${dia} à ${dia_fim}</h5>
                          <p class="card-text">Fechamento do voluntariado dia ${escala.fechamento}</p>
                          <div class="form-group" style="margin-bottom: 0px;">
                            <input class="js-range-sliderQuinzenal" type="text" id="quantidade" name="quantidade" value="${escala.quantidade}" class="irs-hidden-input" tabindex="-1" readonly="">
                            <small id="cotasHelp" class="form-text text-muted">Selecione a quantidade máxima de escalas que deseja tirar para o mês.</small>
                          </div>
    
                          <div id="pref${escala.id}" class="card" style="display: none;">
                          <div class="card-header bg-info p-1">
                            <h3 class="card-title mb-0">Preferências</h3>
                            
                            <div class="card-tools">
                              <button onclick="javascript:(() => {$('#pref${escala.id}').hide();$('#prefLink${escala.id}').show();})();" type="button" class="btn btn-tool" data-card-widget="remove" title="Fechar">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>
  
                          <div class="card-body" style="background-color: aliceblue;">
  
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Minha previsão de escala</label>
                            <input id="previsao_escala" type="text" class="form-control dateSingle" placeholder="Clique e informe os dias que estará escalado">
                          </div>
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Cidade ou localidade de preferência</label>
                            <input id="localidade" type="text" class="form-control" placeholder="Digite o nome da cidade ou localidade que gostaria de cumprir a escala">
                          </div>
                         
                          <div class="form-group">
                            <label for="exampleInputEmail1">Período</label>
                            <select name="" id="periodo" class="form-control" placeholder="Periodo preferêncial">
                            </div>
                           
                              <option value="Qualquer" selected>Qualquer</option>
                              <option value="Matutino">Matutino</option>
                              <option value="Vespertino">Vespertino</option>
                              <option value="Noturno">Noturno</option>
                            </select>
                          </div>
  
                          </div>
  
                        </div>
                       
                        
                        <div style="display: flex;align-items: center;justify-content: center;">
                          <input type="submit" class="btn btn-primary" value="Quero ser voluntário">
                        </div>
                          
                        </div>
                    </div>
                    </form>
                </td>
            </tr>

            `;
        }
                      
        if(tipo == 1 && escala.regime == 15){
            return `
            <tr>
                <td>
                    <form class="formRemoverVoluntario">
                        @csrf
                        <input type="hidden" id="escala_id" value="${escala.id}">
                    
                        <div class="card">
                            <h5 class="card-header bg-success">${escala.escala_periodo} de ${mes} de ${ano}</h5>
                            <div class="card-body bg">
                                <h5 class="card-title">${escala.descricao} para os dias ${dia} à ${dia_fim}</h5>
                                <p class="card-text">Você é voluntário para ${escala.quantidade} escalas extras na ${escala.escala_periodo} de ${mes} de ${ano}</p>
                                <div style="display: flex;align-items: center;justify-content: center;">
                                    <input type="submit"class="btn btn-danger" value="Deixar de ser voluntário">
                                </div>
                            </div>
                        </div>  
                    </form>
                </td>
            </tr>
            `;
        }


        if(tipo == 0 && escala.regime == 30){
            return `

            <tr>
                <td>
                    <form id="form${escala.id}" class="formVoluntariar">
                    
                        @csrf
                        <input type="hidden" id="escala_id" value="${escala.id}">

                    <div class="card">
                        <h5 class="card-header bg-info">${escala.escala_periodo} de ${mes} de ${ano}</h5>
                        <div class="card-body">
                          <h5 class="card-title">${escala.descricao} para os dias ${dia} à ${dia_fim}</h5>
                          <p class="card-text">Fechamento do voluntariado dia ${escala.fechamento}</p>
                          <div class="form-group" style="margin-bottom: 0px;">
                            <input class="js-range-sliderMensal" type="text" id="quantidade" name="quantidade" value="${escala.quantidade}" class="irs-hidden-input" tabindex="-1" readonly="">
                            <small id="cotasHelp" class="form-text text-muted">Selecione a quantidade máxima de escalas que deseja tirar para o mês.</small>
                          </div>
    
                          <div id="pref${escala.id}" class="card" style="display: none;">
                          <div class="card-header bg-info p-1">
                            <h3 class="card-title mb-0">Preferências</h3>
                            
                            <div class="card-tools">
                              <button onclick="javascript:(() => {$('#pref${escala.id}').hide();$('#prefLink${escala.id}').show();})();" type="button" class="btn btn-tool" data-card-widget="remove" title="Fechar">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>
  
                          <div class="card-body" style="background-color: aliceblue;">
  
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Minha previsão de escala</label>
                            <input id="previsao_escala" type="text" class="form-control dateSingle" placeholder="Clique e informe os dias que estará escalado">
                          </div>
                          
                          <div class="form-group">
                            <label for="exampleInputEmail1">Cidade ou localidade de preferência</label>
                            <input id="localidade" type="text" class="form-control" placeholder="Digite o nome da cidade ou localidade que gostaria de cumprir a escala">
                          </div>
                         
                          <div class="form-group">
                            <label for="exampleInputEmail1">Período</label>
                            <select name="" id="periodo" class="form-control" placeholder="Periodo preferêncial">
                            </div>
                           
                              <option value="Qualquer" selected>Qualquer</option>
                              <option value="Matutino">Matutino</option>
                              <option value="Vespertino">Vespertino</option>
                              <option value="Noturno">Noturno</option>
                            </select>
                          </div>
  
                          </div>
  
                        </div>
                       
                        
                        <div style="display: flex;align-items: center;justify-content: center;">
                          <input type="submit" class="btn btn-primary" value="Quero ser voluntário">
                        </div>
                          
                        </div>
                    </div>
                    </form>
                </td>
            </tr>

            `;
        }
                      
        if(tipo == 1 && escala.regime == 30){
            return `
            <tr>
                <td>
                    <form class="formRemoverVoluntario">
                        @csrf
                        <input type="hidden" id="escala_id" value="${escala.id}">
                    
                        <div class="card">
                            <h5 class="card-header bg-success">${escala.escala_periodo} de ${mes} de ${ano}</h5>
                            <div class="card-body bg">
                                <h5 class="card-title">${escala.descricao} para os dias ${dia} à ${dia_fim}</h5>
                                <p class="card-text">Você é voluntário para ${escala.quantidade} escalas extras na ${escala.escala_periodo} de ${mes} de ${ano}</p>
                                <div style="display: flex;align-items: center;justify-content: center;">
                                    <input type="submit"class="btn btn-danger" value="Deixar de ser voluntário">
                                </div>
                            </div>
                        </div>  
                    </form>
                </td>
            </tr>
            `;
        }



        
    }

    const insereEscala = (escala) => {

        let container = addRegimeContainer(escala.regime);

        if(escala.voluntario){
            $(container).append(geraElemento(1,escala));
        }else{
            $(container).append(geraElemento(0,escala));
        }

    }

    function loadExtrajornada(){

        $.ajax({
        url : "/servicos/Sargenteacao/listarEscalasExtrajornadaDisponiveis",
        method : "GET",
        success : function(result){

            console.log(result)
            
            $("#containerEscalas").html('');

            result.forEach(element => {
                
                insereEscala(element);

            });

            $(".dateSingle").on("keypress", function(e) {
                $(".dateSingle").val('');
            });
    
            $('.dateSingle').datepicker({
                multidate: true,
                disableTouchKeyboard: true,
                datesDisable: true,
                format: {
                    toDisplay: function (date, format, language) {
                        var d = new Date(date);
                        d.setMinutes( d.getMinutes() + d.getTimezoneOffset() );
                        return moment(d).format('DD/MM/yyyy')
                    },
                    toValue: function (date, format, language) {
                        var d = new Date(date);
                        d.setMinutes( d.getMinutes() + d.getTimezoneOffset() );
                        return moment(d).format('yyyy-MM-DD')
                    }
                },
                language: 'pt-BR'
            });

            $(".js-range-sliderSemanal").ionRangeSlider({
                skin: "round",
                type: "single",
                min: 1,
                max: 3,
                from: 1
            });

            $(".js-range-sliderQuinzenal").ionRangeSlider({
                skin: "round",
                type: "single",
                min: 1,
                max: 5,
                from: 2
            });

            $(".js-range-sliderMensal").ionRangeSlider({
                skin: "round",
                type: "single",
                min: 1,
                max: 10,
                from: 4
            });

            $('.formRemoverVoluntario').submit(function(e){
                e.preventDefault();

                const escala_id = $('#escala_id', $(this).parent()).val()

                $.ajax({
                    method: 'POST',
                    url: `/servicos/Sargenteacao/removerVoluntarioExtrajornada/${escala_id}`,
                    data: {
                        escala_id : escala_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        console.log(data)
                        //toastr.success(JSON.stringify(data))
                        toastr.success('Sucesso! Você não é mais voluntário para esta escala');
                        loadExtrajornada();
                    },
                    error: function(err){
                        toastr.error(err)
                    }

                });

            });

            $('.formVoluntariar').submit(function(e){
                
                e.preventDefault();

                const escala_id = $('#escala_id', $(this).parent()).val()
                const quantidade = $('#quantidade', $(this).parent()).val()
                const previsao_escala = $('#previsao_escala', $(this).parent()).val()
                const localidade = $('#localidade', $(this).parent()).val()
                const periodo = $('#periodo', $(this).parent()).val()
                const voluntario = true
                
                $.ajax({
                    method: 'POST',
                    url: '/servicos/Sargenteacao/cadastrarVoluntarioExtrajornada',
                    data: {
                        escala_id : escala_id,
                        quantidade : quantidade,
                        previsao_escala : previsao_escala,
                        localidade : localidade,
                        periodo : periodo,
                        voluntario : voluntario
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        console.log(data)
                        //toastr.success(JSON.stringify(data))
                        toastr.success('Sucesso! Você é voluntário para esta escala.')
                        loadExtrajornada();
                    },
                    error: function(err){
                        toastr.error(err)
                    }

                });
                
                //alert($(this).prop('id'))
            });


        },
        error : function(err){

        }
    });
    }

    $(document).ready(function(){

        loadExtrajornada();

    })
</script>