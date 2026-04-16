let emailAtual = null;
moment.locale('pt-br');


function adicionarAnexos(email){

  console.log('email',email)
      
    if(email.msgHasAttachments < 1)
        return '';
    else{

        let anexos = ''; 
        
        for (const anexo of email.msgAttachments){
            
            icon = 'fa-file'

            icon = anexo.attachamentType.includes('pdf') ? 'fa-file-pdf' : icon
            icon = anexo.attachamentType.includes('video') ? 'fa-file-video' : icon
            icon = anexo.attachamentType.includes('image') ? 'fa-file-image' : icon
            icon = anexo.attachamentType.includes('audio') ? 'fa-file-audio' : icon
            icon = anexo.attachamentType.includes('zip') ? 'fa-file-archive' : icon
            icon = anexo.attachamentType.includes('rar-compressed') ? 'fa-file-archive' : icon
            icon = anexo.attachamentType.includes('7z-compressed') ? 'fa-file-archive' : icon
            icon = anexo.attachamentType.includes('csv') ? 'fa-file-csv' : icon
            icon = anexo.attachamentType.includes('msword') ? 'fa-file-word' : icon
            icon = anexo.attachamentType.includes('opendocument.text') ? 'fa-file-word' : icon
            icon = anexo.attachamentType.includes('rtf') ? 'fa-file-word' : icon
            icon = anexo.attachamentType.includes('ms-excel') ? 'fa-file-excel' : icon
            icon = anexo.attachamentType.includes('opendocument.spreadsheet') ? 'fa-file-excel' : icon
            icon = anexo.attachamentType.includes('spreadsheetml.sheet') ? 'fa-file-excel' : icon
            icon = anexo.attachamentType.includes('opendocument.presentation') ? 'fa-file-powerpoint' : icon
            icon = anexo.attachamentType.includes('ms-powerpoint') ? 'fa-file-powerpoint' : icon

            anexos += `
            <li>
                <span class="mailbox-attachment-icon"><i class="far ${icon}"></i></span>
                
                <div class="mailbox-attachment-info">
                    <a href="javascript:downloadAnexo('${email.folderID}','${email.msgID}','${anexo.attachmentID}','${anexo.attachamentType}','${anexo.attachmentEncoding}','${anexo.attachmentName}')" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> ${anexo.attachmentName}</a>
                    <span class="mailbox-attachment-size clearfix mt-1">
                    <span>${anexo.attachmentSize} KB</span>
                        <a href="javascript:downloadAnexo('${email.folderID}','${email.msgID}','${anexo.attachmentID}','${anexo.attachamentType}','${anexo.attachmentEncoding}','${anexo.attachmentName}')" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                    </span>
                </div>
            </li>
            `;
        }

        return `<!-- Anexos -->
        <div class="card-footer bg-white" style="overflow-y: scroll;">
            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
            ${anexos}
            </ul>
        </div>
        <!-- /Anexos -->`
    }
    return ``;
 }

function criarElementoEmail(email){
    
    return $.parseHTML(`
        <div class="col-md-12 p-2" style="padding: 0;">
            <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Ler Email</h3>

                <div class="card-tools">
                <a href="javascript:exibirEmail(${parseInt(email.msgID)-1},'${email.folderID}',true,false)" class="btn btn-tool" title="Anterior"><i class="fas fa-chevron-left"></i></a>
                <a href="javascript:exibirEmail(${parseInt(email.msgID)+1},'${email.folderID}',true,true)" class="btn btn-tool" title="Próximo"><i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="mailbox-read-info">
                <h5>${email.msgSubject}</h5>
                <h6>Enviado por: ${email.msgFrom.fullName} - ${email.msgFrom.mailAddress}
                    <span class="mailbox-read-time float-right">${moment(email.msgDate,'DD/MM/yyyy HH:mm').format('DD/MM/yyyy HH:mm')}</span></h6>
                </div>
                <!-- /.mailbox-read-info -->
                <div class="mailbox-controls with-border text-center">
                <div class="btn-group">
                    <button onclick="javascript:del(${email.msgID},'${email.folderID}');" type="button" class="btn btn-default btn-sm" data-container="body" title="Excluir">
                    <i class="far fa-trash-alt"></i>
                    </button>
                    <button onclick="javascript:reply(${email.msgID});" type="button" class="btn btn-default btn-sm" data-container="body" title="Responder">
                    <i class="fas fa-reply"></i>
                    </button>
                    <button onclick="javascript:foward(${email.msgID},'${email.folderID}');" type="button" class="btn btn-default btn-sm" data-container="body" title="Encaminhar">
                    <i class="fas fa-share"></i>
                    </button>
                </div>
                <!-- /.btn-group -->
                <button onclick="javascript:PrintElem('emailContainer');" type="button" class="btn btn-default btn-sm" title="Imprimir">
                    <i class="fas fa-print"></i>
                </button>
                </div>
                <!-- /.mailbox-controls -->
                <div class="mailbox-read-message">
                ${email.msgBody}
                </div>
                <!-- /.mailbox-read-message -->
            </div>
            <!-- /.card-body -->
            
            ${adicionarAnexos(email)}
            
            <div class="card-footer">
                <div class="float-right">
                <button onclick="javascript:reply(${email.msgID});" type="button" class="btn btn-default"><i class="fas fa-reply"></i> Responder</button>
                <button onclick="javascript:foward(${email.msgID},'${email.folderID}');" type="button" class="btn btn-default"><i class="fas fa-share"></i> Encaminhar</button>
                </div>
                <button onclick="javascript:del(${email.msgID},'${email.folderID}');" type="button" class="btn btn-default"><i class="far fa-trash-alt"></i> Deletar</button>
                <button onclick="javascript:PrintElem('emailContainer');" type="button" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</button>
            </div>

        </div>
    </div>
`);
}

function exibirEmail(msgID,pasta,navegacao = false,proxima = false){

  $.ajax({
    url : `/servicos/Expresso/listarEmails?msgID=${msgID}&pasta=${pasta}`,
    method : 'GET',
    success : function(data){

      emailAtual = data.messages[0];

      let email = criarElementoEmail(data.messages[0]);

      $("#emailContainer").html(email);
      
    },
    error : function(error){
      if(navegacao){
        id = proxima ? msgID+1 : msgID-1;
        exibirEmail(id,pasta,navegacao, proxima);
      }
      else
        toastr.error('Houve um erro ao buscar email no servidor!');
    }
  });


    
    return
    //TODO exibir spinner

   /*  $.ajax({
        url: 'emails/'+id,
        method: 'get',
        data: {email_id:id},
        success: function(data){
            //dados do email recebidos com sucesso
            let email = criarElementoEmail(data);

            $("#emailContainer").html(email);
            // TODO remover spinner
        },
        error: function(err){
            //Erro ao buscar dados do email
            toastr.error('Erro ao buscar email! '+err);
            let data = {
                conteudo : $.parseHTML('<h1>TESTE</h1>'),
                remetente : 'ezequiel.haccourt@pm.pr.gov.br',
                data : moment(),
                assunto : 'Teste de leitura',
                anexos : [
                    {
                        nome:'teste',
                    }
                ]
            };

            let email = criarElementoEmail(data);

            $("#emailContainer").html(email);
            // TODO remover spinner
        }
    }); */
}

function criarElementoDraft(pagina,primeira,ultima){
    // return inboxComponente
     return `
     <div class="col-md-12 p-2" style="padding: 0;">
       <div class="card card-primary card-outline">
         <div class="card-header">
           <h3 class="card-title">Rascunhos</h3>
 
           <div class="card-tools">
             <div class="input-group input-group-sm">
             <input id="ibusca" type="text" class="form-control" placeholder="Buscar emails">
               <div class="input-group-append">
                 <div onclick="javascript:buscaEmails('INBOX/Rascunhos',1,10)" class="btn btn-primary">
                   <i class="fas fa-search"></i>
                 </div>
               </div>
             </div>
           </div>
           <!-- /.card-tools -->
         </div>
         <!-- /.card-header -->
         <div class="card-body p-0">
           <div class="mailbox-controls">
             <!-- Check all button -->
             <button onclick="javascript:selecionarTudo()" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
             </button>
             <div class="btn-group">
               <button onclick="javascript:moverSelecionados('INBOX/Rascunhos','INBOX/Lixeira',exibirDraft(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
             </div>           
             <!-- /.btn-group -->
             <button onclick="javascript:exibirDraft(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
             <div class="float-right">
             ${primeira}-${ultima}
             <div class="btn-group">
               <button onclick="javascript:exibirDraft(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
               <button onclick="javascript:exibirDraft(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
             </div>
               <!-- /.btn-group -->
             </div>
             <!-- /.float-right -->
           </div>
           <div class="table-responsive mailbox-messages">
             <table id="tabelaDraft" class="table table-hover table-striped">
               <tbody>           
                             
               </tbody>
             </table>
             <!-- /.table -->
           </div>
           <!-- /.mail-box-messages -->
         </div>
         <!-- /.card-body -->
         <div class="card-footer p-0">
           <div class="mailbox-controls">
             <!-- Check all button -->
             <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
             </button>
             <div class="btn-group">
               <button onclick="javascript:moverSelecionados('INBOX/Rascunhos','INBOX/Lixeira',exibirDraft(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
             </div>
             
             <!-- /.btn-group -->
             <button onclick="javascript:exibirDraft(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
             <div class="float-right">
             ${primeira}-${ultima}
             <div class="btn-group">
               <button onclick="javascript:exibirDraft(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
               <button onclick="javascript:exibirDraft(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
             </div>
               <!-- /.btn-group -->
             </div>
             <!-- /.float-right -->
         </div>
     </div>`;
}

function criarElementoTrash(pagina,primeira,ultima){
    // return inboxComponente
     return `
     <div class="col-md-12 p-2" style="padding: 0;">
       <div class="card card-primary card-outline">
         <div class="card-header">
           <h3 class="card-title">Lixeira</h3>
 
           <div class="card-tools">
             <div class="input-group input-group-sm">
             <input id="ibusca" type="text" class="form-control" placeholder="Buscar emails">
               <div class="input-group-append">
                 <div onclick="javascript:buscaEmails('INBOX/Lixeira',1,10)" class="btn btn-primary">
                   <i class="fas fa-search"></i>
                 </div>
               </div>
             </div>
           </div>
           <!-- /.card-tools -->
         </div>
         <!-- /.card-header -->
         <div class="card-body p-0">
           <div class="mailbox-controls">
             <!-- Check all button -->
             <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
             </button>
             <div class="btn-group">
               <button onclick="javascript:moverSelecionados('INBOX/Lixeira','INBOX/Lixeira',exibirTrash(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
             </div>
             <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirTrash(${pagina}));">
                <option value="INBOX/Lixeira">Lixeira</option>
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Rascunhos">Rascunhos</option>
                <option value="INBOX/Spam">Spam</option>
              </select>
              
            </div>
             <!-- /.btn-group -->
             <button onclick="javascript:exibirTrash(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
             <div class="float-right">
             ${primeira}-${ultima}
             <div class="btn-group">
               <button onclick="javascript:exibirTrash(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
               <button onclick="javascript:exibirTrash(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
             </div>
               <!-- /.btn-group -->
             </div>
             <!-- /.float-right -->
           </div>
           <div class="table-responsive mailbox-messages">
             <table id="tabelaTrash" class="table table-hover table-striped">
               <tbody>           
                             
               </tbody>
             </table>
             <!-- /.table -->
           </div>
           <!-- /.mail-box-messages -->
         </div>
         <!-- /.card-body -->
         <div class="card-footer p-0">
           <div class="mailbox-controls">
             <!-- Check all button -->
             <button onclick="javascript:selecionarTudo()" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
             </button>
             <div class="btn-group">
               <button onclick="javascript:moverSelecionados('INBOX/Lixeira','INBOX/Lixeira',exibirTrash(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
             </div>
             <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirTrash(${pagina}));">
                <option value="INBOX/Lixeira">Lixeira</option>  
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Rascunhos">Rascunhos</option>
                <option value="INBOX/Spam">Spam</option>
              </select>
              
            </div>
             <!-- /.btn-group -->
             <button onclick="javascript:exibirTrash(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
             <div class="float-right">
             ${primeira}-${ultima}
             <div class="btn-group">
               <button onclick="javascript:exibirTrash(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
               <button onclick="javascript:exibirTrash(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
             </div>
               <!-- /.btn-group -->
             </div>
             <!-- /.float-right -->
         </div>
     </div>`;
}

function criarElementoOutbox(pagina,primeira,ultima){
   // return inboxComponente
    return `
    <div class="col-md-12 p-2" style="padding: 0;">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Caixa de saída</h3>

          <div class="card-tools">
            <div class="input-group input-group-sm">
            <input id="ibusca" type="text" class="form-control" placeholder="Buscar emails">
              <div class="input-group-append">
                <div onclick="javascript:buscaEmails('INBOX/Enviados',1,10)" class="btn btn-primary">
                  <i class="fas fa-search"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button onclick="javascript:moverSelecionados('INBOX/Enviados','INBOX/Lixeira',exibirOutbox(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
            </div>
           
            <!-- /.btn-group -->
            <button onclick="javascript:exibirOutbox(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
            <div class="float-right">
            ${primeira}-${ultima}
            <div class="btn-group">
              <button onclick="javascript:exibirOutbox(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
              <button onclick="javascript:exibirOutbox(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
            </div>
              <!-- /.btn-group -->
            </div>
            <!-- /.float-right -->
          </div>
          <div class="table-responsive mailbox-messages">
            <table id="tabelaOutbox" class="table table-hover table-striped">
              <tbody>           
                            
              </tbody>
            </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button onclick="javascript:moverSelecionados('INBOX/Enviados','INBOX/Lixeira',exibirOutbox(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
            </div>
            
            <!-- /.btn-group -->
            <button onclick="javascript:exibirOutbox(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
            <div class="float-right">
            ${primeira}-${ultima}
            <div class="btn-group">
              <button onclick="javascript:exibirOutbox(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
              <button onclick="javascript:exibirOutbox(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
            </div>
              <!-- /.btn-group -->
            </div>
            <!-- /.float-right -->
        </div>
    </div>`;
}

function criarElementoInbox(pagina,primeira,ultima){
   // return inboxComponente
    return `
    <div class="col-md-12 p-2" style="padding: 0;">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Caixa de entrada</h3>

          <div class="card-tools">
            <div class="input-group input-group-sm">
            <input id="ibusca" type="text" class="form-control" placeholder="Buscar emails">
              <div class="input-group-append">
                <div onclick="javascript:buscaEmails('INBOX',1,10)" class="btn btn-primary">
                  <i class="fas fa-search"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button onclick="javascript:moverSelecionados('INBOX','INBOX/Lixeira',exibirInbox(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
            </div>
            <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirInbox(${pagina}));">
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Lixeira">Lixeira</option>
                <option value="INBOX/Spam">Spam</option>
              </select>
              
            </div>
            <!-- /.btn-group -->
            <button onclick="javascript:exibirInbox(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
            <div class="float-right">
              ${primeira}-${ultima}
              <div class="btn-group">
                <button onclick="javascript:exibirInbox(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
                <button onclick="javascript:exibirInbox(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
              </div>
              <!-- /.btn-group -->
            </div>
            <!-- /.float-right -->
          </div>
          <div class="table-responsive mailbox-messages">
            <table id="tabelaInbox" class="table table-hover table-striped">
              <tbody>              
              </tbody>
            </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button onclick="javascript:moverSelecionados('INBOX','INBOX/Lixeira',exibirInbox(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
            </div>
            <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirInbox(${pagina}));">
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Lixeira">Lixeira</option>
                <option value="INBOX/Spam">Spam</option>
              </select>
              
            </div>
            <!-- /.btn-group -->
            <button onclick="javascript:exibirInbox(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
            <div class="float-right">
              ${primeira}-${ultima}
              <div class="btn-group">
                <button onclick="javascript:exibirInbox(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
                <button onclick="javascript:exibirInbox(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
              </div>
              <!-- /.btn-group -->
            </div>
            <!-- /.float-right -->
        </div>
    </div>`;
}

function criarElementoBusca(pagina,primeira,ultima,folderID,termo){
   // return inboxComponente
    return `
    <div class="col-md-12 p-2" style="padding: 0;">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Busca em ${folderID}</h3>

          <div class="card-tools">
            <div class="input-group input-group-sm">
            <input id="ibusca" type="text" class="form-control" placeholder="Buscar emails" value="${termo}">
              <div class="input-group-append">
                <div onclick="javascript:buscaEmails('${folderID}',1,10)" class="btn btn-primary">
                  <i class="fas fa-search"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button onclick="javascript:moverSelecionados('INBOX','INBOX/Lixeira',exibirBusca('${folderID}',${pagina},10,'${termo}'));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
            </div>
            <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirBusca('${folderID}',${pagina},10,'${termo}'));">
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Lixeira">Lixeira</option>
                <option value="INBOX/Spam">Spam</option>
              </select>
            </div>
            <!-- /.btn-group -->
            <button onclick="javascript:exibirBusca('${folderID}',${pagina},10,'${termo}')" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
            <!-- <div class="float-right">
              ${primeira}-${ultima}
              <div class="btn-group">
                <button onclick="javascript:exibirBusca('${folderID}',${pagina-1},10,'${termo}')" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
                <button onclick="javascript:exibirBusca('${folderID}',${pagina+1},10,'${termo}')" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
              </div>
            </div> -->
            <!-- /.float-right -->
          </div>
          <div class="table-responsive mailbox-messages">
            <table id="tabelaBusca" class="table table-hover table-striped">
              <tbody>              
              </tbody>
            </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button onclick="javascript:moverSelecionados('INBOX','INBOX/Lixeira',exibirBusca('${folderID}',${pagina},10,'${termo}'));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
            </div>
            <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirBusca('${folderID}',${pagina},10,'${termo}'));">
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Lixeira">Lixeira</option>
                <option value="INBOX/Spam">Spam</option>
              </select>
            </div>
            <!-- /.btn-group -->
            <button onclick="javascript:exibirBusca('${folderID}',${pagina},10,'${termo}')" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
            <div class="float-right">
              ${primeira}-${ultima}
              <div class="btn-group">
                <button onclick="javascript:exibirBusca('${folderID}',${pagina-1},10,'${termo}'))" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
                <button onclick="javascript:exibirBusca('${folderID}',${pagina+1},10,'${termo}'))" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
              </div>
              <!-- /.btn-group -->
            </div>
            <!-- /.float-right -->
        </div>
    </div>`;
}

function criarElementoSpam(pagina,primeira,ultima){
    // return inboxComponente
     return `
     <div class="col-md-12 p-2" style="padding: 0;">
       <div class="card card-primary card-outline">
         <div class="card-header">
           <h3 class="card-title">Spam</h3>
 
           <div class="card-tools">
             <div class="input-group input-group-sm">
             <input id="ibusca" type="text" class="form-control" placeholder="Buscar emails">
               <div class="input-group-append">
                 <div onclick="javascript:buscaEmails('INBOX/Spam',1,10)" class="btn btn-primary">
                   <i class="fas fa-search"></i>
                 </div>
               </div>
             </div>
           </div>
           <!-- /.card-tools -->
         </div>
         <!-- /.card-header -->
         <div class="card-body p-0">
           <div class="mailbox-controls">
             <!-- Check all button -->
             <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
             </button>
             <div class="btn-group">
               <button onclick="javascript:moverSelecionados('INBOX/Spam','INBOX/Lixeira',exibirSpam(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
             </div>
             <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirSpam(${pagina}));">
                <option value="INBOX/Spam">Spam</option>
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Lixeira">Lixeira</option>
              </select>
              
            </div>
             <!-- /.btn-group -->
             <button onclick="javascript:exibirSpam(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
             <div class="float-right">
             ${primeira}-${ultima}
             <div class="btn-group">
               <button onclick="javascript:exibirSpam(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
               <button onclick="javascript:exibirSpam(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
             </div>
               <!-- /.btn-group -->
             </div>
             <!-- /.float-right -->
           </div>
           <div class="table-responsive mailbox-messages">
             <table id="tabelaSpam" class="table table-hover table-striped">
               <tbody>              
               
               </tbody>
             </table>
             <!-- /.table -->
           </div>
           <!-- /.mail-box-messages -->
         </div>
         <!-- /.card-body -->
         <div class="card-footer p-0">
           <div class="mailbox-controls">
             <!-- Check all button -->
             <button onclick="javascript:selecionarTudo();" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
             </button>
             <div class="btn-group">
               <button onclick="javascript:moverSelecionados('INBOX/Spam','INBOX/Lixeira',exibirSpam(${pagina}));" type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
             </div>
             <div class="btn-group" style="align-items: baseline;">
              <p style="margin: 0;">Mover para &nbsp;</p>
              <select onchange="javascript:moverSelecionados('INBOX',this.value,exibirSpam(${pagina}));">
                <option value="INBOX/Spam">Spam</option>
                <option value="INBOX">Entrada</option>
                <option value="INBOX/Lixeira">Lixeira</option>
              </select>
              
            </div>
             <!-- /.btn-group -->
             <button onclick="javascript:exibirSpam(${pagina})" type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
             <div class="float-right">
             ${primeira}-${ultima}
             <div class="btn-group">
               <button onclick="javascript:exibirSpam(${pagina-1})" type="button" class="btn btn-default btn-sm" ${pagina < 2 ? 'disabled':''}><i class="fas fa-chevron-left"></i></button>
               <button onclick="javascript:exibirSpam(${pagina+1})" type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
             </div>
               <!-- /.btn-group -->
             </div>
             <!-- /.float-right -->
         </div>
     </div>`;
}

function marcarEmailImportante(msgID){

}


function adicionarEmail(email){
    if(!Array.isArray(email))
        email = [email]
    
    let emails = '';
  
    for (const el of email) {
      
        emails += `<tr>
        <td>
          <div class="icheck-primary">
            <input type="checkbox" value="" id="check${el.msgID}" data-id="${el.msgID}">
            <label for="check1"></label>
          </div>
        </td>
        <td class="mailbox-star"><a href="javascript:marcarEmailImportante(${el.msgID})"><i class="fas fa-star text-warning"></i></a></td>
        <td class="mailbox-subject" onclick="javascript:exibirEmail(${el.msgID},'${el.folderID}');" style="cursor:pointer;"><b>${el.msgSubject}</b> - ${el.msgBodyResume.substr(0,100)}...</td>
        <td class="mailbox-name"><a href="javascript:exibirEmail(${el.msgID},'${el.folderID}')">${el.msgFrom.fullName}&#13;&lt;${el.msgFrom.mailAddress}&gt;</a></td>
        <td class="mailbox-attachment">${el.msgHasAttachments && el.msgHasAttachments > 0 ? '<i class="fas fa-paperclip"></i>':''}</td>
        <td class="mailbox-date">${moment(el.msgDate,'DD/MM/yyyy HH:mm').fromNow()}</td>
      </tr>`;
    }
    
    return emails;
}

function exibirInbox(pagina = 1,qtdePagina = 10){

    $("#qtdeInbox").html(`
    <div class="spinner-border spinner-border-sm" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    `);
    
    const ultima = qtdePagina*pagina;
    const primeira = (ultima - qtdePagina)+1;

    inbox = criarElementoInbox(pagina,primeira,ultima);

    $.ajax({
      url : `/servicos/Expresso/listarEmails?pasta=Inbox&resultadosPorPagina=${qtdePagina}&pagina=${pagina}`,
      method : 'GET',
      success : function(data){
        console.log(data)
        $("#qtdeInbox").html(data.totalUnseen)
        $("#emailContainer").html(inbox);
        if(data.messages.length > 0)
          $("#tabelaInbox > tbody").append(adicionarEmail(data.messages));
        else
          toastr.error('Sem emails')
      },
      error : function(error){
        toastr.error('Houve um erro ao buscar emails no servidor!');
      }
    });
    
}

function exibirBusca(folderID,pagina,qtdePagina,termo){

    const ultima = qtdePagina*pagina;
    const primeira = (ultima - qtdePagina)+1;

    busca = criarElementoBusca(pagina,primeira,ultima,folderID,termo);

    $.ajax({
      url : `/servicos/Expresso/listarEmails?busca=${termo}&pasta=${folderID}&resultadosPorPagina=${qtdePagina}&pagina=${pagina}`,
      method : 'GET',
      success : function(data){

        data.messages.forEach(element => {
          element.msgBodyResume = ''
        });
        
        $("#emailContainer").html(busca);
        $("#tabelaBusca > tbody").append(adicionarEmail(data.messages));
      },
      error : function(error){
        toastr.error('Houve um erro ao buscar emails no servidor!');
      }
    });
    

  }

function exibirOutbox(pagina = 1,qtdePagina = 10){

  $("#qtdeOutbox").html(`
    <div class="spinner-border spinner-border-sm" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  `);

    const ultima = qtdePagina*pagina;
    const primeira = (ultima - qtdePagina)+1;

    outbox = criarElementoOutbox(pagina,primeira,ultima);

    $.ajax({
      url : `/servicos/Expresso/listarEmails?pasta=Inbox%2FEnviados&resultadosPorPagina=${qtdePagina}&pagina=${pagina}`,
      method : 'GET',
      success : function(data){
        $("#qtdeOutbox").html(data.totalUnseen)
        $("#emailContainer").html(outbox);
        $("#tabelaOutbox > tbody").append(adicionarEmail(data.messages));
      },
      error : function(error){
        toastr.error('Houve um erro ao buscar emails no servidor!');
      }
    });

}

function exibirDraft(pagina = 1,qtdePagina = 10){

  $("#qtdeDraft").html(`
    <div class="spinner-border spinner-border-sm" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  `);

    const ultima = qtdePagina*pagina;
    const primeira = (ultima - qtdePagina)+1;

    draft = criarElementoDraft(pagina,primeira,ultima);

    $.ajax({
      url : `/servicos/Expresso/listarEmails?pasta=Inbox%2FRascunhos&resultadosPorPagina=${qtdePagina}&pagina=${pagina}`,
      method : 'GET',
      success : function(data){
        $("#qtdeDraft").html(data.messages.length)
        $("#emailContainer").html(draft);
        $("#tabelaDraft > tbody").append(adicionarEmail(data.messages));
      },
      error : function(error){
        toastr.error('Houve um erro ao buscar emails no servidor!');
      }
    });

}

function exibirSpam(pagina = 1,qtdePagina = 10){

  $("#qtdeSpam").html(`
    <div class="spinner-border spinner-border-sm" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  `);

    const ultima = qtdePagina*pagina;
    const primeira = (ultima - qtdePagina)+1;
    
    spam = criarElementoSpam(pagina,primeira,ultima);

    $.ajax({
      url : `/servicos/Expresso/listarEmails?pasta=Inbox%2FSpam&resultadosPorPagina=${qtdePagina}&pagina=${pagina}`,
      method : 'GET',
      success : function(data){
        $("#qtdeSpam").html(data.messages.length)
        $("#emailContainer").html(spam);
        $("#tabelaSpam > tbody").append(adicionarEmail(data.messages));
      },
      error : function(error){
        toastr.error('Houve um erro ao buscar emails no servidor!');
      }
    });

}


function exibirTrash(pagina = 1,qtdePagina = 10){

  $("#qtdeTrash").html(`
    <div class="spinner-border spinner-border-sm" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  `);

    const ultima = qtdePagina*pagina;
    const primeira = (ultima - qtdePagina)+1;

    trash = criarElementoTrash(pagina,primeira,ultima);

    $.ajax({
      url : `/servicos/Expresso/listarEmails?pasta=Inbox%2FLixeira&resultadosPorPagina=${qtdePagina}&pagina=${pagina}`,
      method : 'GET',
      success : function(data){
        $("#qtdeTrash").html(data.messages.length)
        $("#emailContainer").html(trash);
        $("#tabelaTrash > tbody").append(adicionarEmail(data.messages));
      },
      error : function(error){
        toastr.error('Houve um erro ao buscar emails no servidor!');
      }
    });
    
}

function criarElementoWriteEmail(){
    return `
    
    <div class="col-md-12 p-2" style="padding: 0;">
            <div class="card card-primary card-outline">
                
                <div class="card-header">
                    <h3 class="card-title">Escrever Email</h3>
                </div>

                <div class="card-body">
                <form id="formEmail" enctype="multipart/form-data">    
                <div class="form-group">
                <input class="form-control" placeholder="Para:  adicione emails separados por virgula" name="msgTo" id="msgTo" value="">
                </div>
                <div style="margin-top: -18px;">
                Adicionar <a id="acc" href="javascript:addCc()">cc</a>,<a id="acco" href="javascript:addCco()">cco</a>
                </div>
                
                <div class="form-group">
                <input class="form-control" placeholder="Cópia para:   adicione emails separados por virgula" name="msgCcTo" id="msgCcTo" style="display:none;">
                </div>
                <div class="form-group">
                <input class="form-control" placeholder="Cópia oculta para:   adicione emails separados por virgula" name="msgBccTo" id="msgBccTo" style="display:none;">
                </div>
                <div class="form-group">
                <input type="hidden" class="form-control" placeholder="Responder Para:" name="msgReplyTo" id="msgReplyTo">
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" placeholder="msgID:" name="msgID" id="msgID">
                </div>
                <div class="form-group">
                        <input type="hidden" class="form-control" placeholder="Tipo:" name="msgType" id="msgType">
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" placeholder="Rascunho:" name="msgSaveDraft" id="msgSaveDraft">
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Assunto:" name="msgSubject" id="msgSubject">
                    </div>
                    <textarea id="msgBody" name="msgBody"></textarea>
                    <div class="form-group">
                    <div class="btn btn-default btn-file">
                        <i class="fas fa-paperclip"></i> Anexar
                        <input type="file" name="files[]" id="file" multiple>
                    </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="float-right">
                    <button id="btnRascunho" type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Rascunho</button>
                    <button id="btnEnviar" type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Enviar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    `
}

function exibirEscreverEmail(){

    write = criarElementoWriteEmail();

    $("#emailContainer").html(write);

    $('#msgBody').summernote()
}

function addCc(){
  $('#msgCcTo').show()  
}

function addCco(){
  $('#msgBccTo').show()  
}

function downloadAnexo(folderID,msgID,attachmentID,attachamentType,attachmentEncoding,attachmentName){
  window.open(`/servicos/Expresso/baixarAnexos?msgID=${msgID}&folderID=${folderID}&attachmentID=${attachmentID}&attachmentName=${attachmentName}&attachamentType=${attachamentType}&attachmentEncoding=${attachmentEncoding}`,'_new');
}

function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}

function moverSelecionados(folderID,toFolderID,exibirEmailAtual){
  //alert(folderID)
  $('[type=checkbox]').each(function(){

    if(this.checked){
      console.log(this.dataset.id)
      $.ajax({
        url : '/servicos/Expresso/moverEmail',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        method : 'POST',
        data : {
          folderID : folderID,
          toFolderID : toFolderID,
          msgID : this.dataset.id
        },
        success : exibirEmailAtual,
        error : function(error){
          toastr.error('Problemas ao excluir emai ID '+error)
        },
      });
      
    }
  });
  
}

function selecionarTudo(){
  $('[type=checkbox]').each(function(){
    if(this.checked)
      this.checked = false;
    else
      this.checked = true;
  });
}

function reply(){
  
  exibirEscreverEmail()

  let para = ''

  if(!Array.isArray(emailAtual.msgFrom))
    para = [emailAtual.msgFrom].map(el=> {return el.mailAddress}).join()
  else
    para = emailAtual.msgFrom.mailAddress
  
  $("#msgTo").val(para);
  $("#msgSubject").val('RES: '+emailAtual.msgSubject);
  $('#msgBody').summernote('code','<br>---------- Respondendo a mensagem : ----------<br>'+emailAtual.msgBody);

  $('#btnEnviar').click(function(e){
      
    $.ajax( {
      url: 'servicos/Expresso/enviarEmails',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data: new FormData( $('#formEmail')[0] ),
      processData: false,   
      contentType: false,
      success: function(data){
        toastr.success('Email enviado com sucesso!')
        exibirOutbox(1,10);
      },
      error: function(error){
        toastr.error('Erro ao enviar email!'+error);
      }

    });
    
    e.preventDefault();
    
  });
  
}

function foward(MsgID = "",FolderID = "Inbox"){

  exibirEscreverEmail()

  let para = ''

  $("#formEmail").append(`
  <input type="hidden" name="encaminhar" value="true">
  <input type="hidden" name="Msg" value="${MsgID}">
  <input type="hidden" name="Folder" value="${FolderID}">
  `);

  $("#msgSubject").val('ENCAMINHADA: '+emailAtual.msgSubject);
  $('#msgBody').summernote('code','<br>---------- Mensagem Encaminhada ----------<br>'+emailAtual.msgBody);

  $('#btnEnviar').click(function(e){
      
    $.ajax( {
      url: 'servicos/Expresso/enviarEmails',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data: new FormData( $('#formEmail')[0] ),
      processData: false,   
      contentType: false,
      success: function(data){
        toastr.success('Email enviado com sucesso!')
        exibirOutbox(1,10);
      },
      error: function(error){
        toastr.error('Erro ao enviar email!'+error);
      }

    });
    
    e.preventDefault();
    
  });
}

function del(MsgID,FolderID){
  if(confirm('Deseja realmete excluir a mensagem? Está ação não pode ser desfeita!')){
    $.ajax( {
      url: 'servicos/Expresso/deletarEmail',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data: {MsgID:MsgID,FolderID:FolderID},
      success: function(data){
        toastr.success('Email apagado com sucesso!')
        exibirEmail(parseInt(MsgID)-1,FolderID,true,false)
      },
      error: function(error){
        toastr.error('Erro ao apagar email!'+error);
      }

    });
  }
}

function buscaEmails(folderID,pagina,qtdePagina){
  
  if($('#ibusca').val() != ''){
    exibirBusca(folderID,pagina,qtdePagina,$('#ibusca').val())
  }else{
    toastr.error('Termo de pesquisa nulo ou inválido! insira um termo para busca')
  }
}

$(document).ready(function(){

  moment.locale('pt-br');
   
  exibirInbox(1,10)

  $('#btnEscrever').click(function(){
    exibirEscreverEmail();
    
    

    $('#btnEnviar').click(function(e){
      
      $.ajax( {
        url: 'servicos/Expresso/enviarEmails',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        data: new FormData( $('#formEmail')[0] ),
        processData: false,   
        contentType: false,
        success: function(data){
          toastr.success('Email enviado com sucesso!')
          exibirOutbox(1,10);
        },
        error: function(error){
          toastr.error('Erro ao enviar email!'+error);
        }

      });
      
      e.preventDefault();
      
    });

  });

  
});