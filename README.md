
  

# template

  

>Template para softwares DDTQ/SSI

   
  

[![Build Status][travis-image]][travis-url]

O template contém as seguintes views:

>Home
>--
>- Comunicados Administrativos.

>Administração
>--
>- Links Administrativos.

>Boletins
> - 

>Sistemas
>--
>- Sistemas cadastrados no Sia-UI [https://git.pmpr.parana/ddtq-ssi/sia-ui];
>- Sistemas Legado.

>Perfil
>--
>- Alterar Senha;
>- Atualizar Email Alternativo.

>Email
>--
>- Caixa de Email.

>Notificações
>--
>- ''


  ## Pré-Requisitos
```sh

- PHP 7.4+
- Laravel 8+
- Apache2
- PostGreSQL 12+
 
```

  ## Instalação 

  

OS X & Linux:

  
  
  

```sh

git clone https://git.pmpr.parana/ddtq-ssi/template.git

cd template path

cp .env.exemple .env

##Edite as Variáveis de Ambiente para Produção

php artisan migrate:fresh
php artisan key:generate
php artisan cache:clear
php artisan config:cache

```

  

  

Git:

  

  

```sh

git clone https://git.pmpr.parana/ddtq-ssi/template-software.git

```


## Histórico de Versões

  

* CHANGE: Versão Inicial.

* 0.0.1 -- Versão Inicial.

* Em progresso.

  

  
  

## Manutenção  

1. Fork it (<https://git.pmpr.parana/ddtq-ssi/sia-ui/fork>)

  

2. Create your feature branch (`git checkout -b feature/fooBar`)

  

3. Commit your changes (`git commit -am 'Add some fooBar'`)

  

4. Push to the branch (`git push origin feature/fooBar`)

  

5. Create a new Pull Request

<!-- Markdown link & img dfn's -->

  

[travis-image]: https://img.shields.io/travis/dbader/node-datadog-metrics/master.svg?style=flat-square

  

[travis-url]: https://travis-ci.org/dbader/node-datadog-metrics