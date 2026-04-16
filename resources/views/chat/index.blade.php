<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat | PMPR</title>
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('converse/dist/converse.min.css')}}">
    <script src="{{asset('converse/dist/converse.min.js')}}" charset="utf-8"></script>
</head>
<body>
   
</body>
<script src="{{ asset('js/app.js') }}"></script>
<script>

const params = new URLSearchParams(window.location.search)

    $(document).ready(function(){
        
        alert(params.values());
        alert(window.location.search)
        console.log(params) 
    })
        
    /* converse.initialize({
    bosh_service_url: 'https://im.pm.pr.gov.br:7443/http-bind/',
    authentication: 'prebind',
    prebind_url: '{{route('xmppPrebind')}}',
    auto_login: true,
    allow_logout: false,    
    allow_message_corrections: 'last',
    allow_message_retraction: 'moderator',
    allow_non_roster_messaging : true,
    allow_registration: false,
    auto_list_rooms: true,
    //fullname: 'TESTE',
    assets_path: '/converse/dist/',
    auto_reconnect: false,
    //auto_register_muc_nickname: true,
    //auto_join_on_invite : true,
    muc_domain: 'conference.im.pm.pr.gov.br',
    registration_domain: 'im.pm.pr.gov.br',
    default_domain: 'im.pm.pr.gov.br',
    locked_domain: 'im.pm.pr.gov.br',
    //auto_register_muc_nickname: true,
    muc_nickname_from_jid : false,
    locked_muc_nickname: true,
    hide_offline_users: true,
    locales: ['pt_BR'],
    i18n: 'pt_BR',
    locked_muc_domain: true,
    play_sounds: true,
    show_controlbox_by_default: true,
    show_client_info: false,
    allow_adhoc_commands: false
}); */
</script>
</html>



