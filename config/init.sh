#!/bin/bash

IN=`cat .env | grep "PROXY_PROTOCOLO="`
arrIN=(${IN//=/ })
PROXY_PROTOCOLO=${arrIN[1]}
IN=`cat .env | grep "PROXY_URL="`
arrIN=(${IN//=/ })
PROXY_URL=${arrIN[1]}
IN=`cat .env | grep "PROXY_PORTA="`
arrIN=(${IN//=/ })
PROXY_PORTA=${arrIN[1]}
IN=`cat .env | grep "PROXY_USUARIO="`
arrIN=(${IN//=/ })
PROXY_USUARIO=${arrIN[1]}
IN=`cat .env | grep "PROXY_SENHA="`
arrIN=(${IN//=/ })
PROXY_SENHA=${arrIN[1]}

export http_proxy="$PROXY_PROTOCOLO://$PROXY_USUARIO$PROXY_SENHA$PROXY_URL:$PROXY_PORTA"
export https_proxy="$PROXY_PROTOCOLO://$PROXY_USUARIO$PROXY_SENHA$PROXY_URL:$PROXY_PORTA"

IN=`cat .env | grep "APP_HTTP_PORTA="`
arrIN=(${IN//=/ })
PORTA=${arrIN[1]}
echo $PORTA > init.run
export no_proxy=10.147.29.225,10.47.1.8,git.pmpr.parana,api.expresso.pr.gov.br
IN=`cat .env | grep "DB_DATABASE="`
arrIN=(${IN//=/ })
DATABASE=${arrIN[1]}
sudo -u postgres createdb $DATABASE
yes | composer require spatie/laravel-query-builder --no-interaction
yes | composer install --no-interaction
php artisan migrate
php artisan db:seed

nohup php artisan serve --host=0.0.0.0 --port=$PORTA > /dev/null 2>&1 &

IN=`cat .env | grep "APP_NAME="`
arrIN=(${IN//=/ })
APP_NAME=${arrIN[1]}

if [ "$APP_NAME" == "Sia-Auth" ]; then
    echo "Iniciando fila de atualização de templates"
    nohup php artisan queue:work --queue=AtualizaTemplate > /dev/null 2>&1 &
fi


