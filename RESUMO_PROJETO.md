# Resumo do projeto — Monitoramento (monitoramento-master)

## Visão geral

Aplicação **Laravel 8** derivada do **template DDTQ/SSI** da PMPR, com nome de produto **Monitoramento** (`APP_NAME` no `.env.example`). Combina:

1. **Funcionalidade própria de monitoramento**: cadastro e visualização de câmeras, mosaicos por usuário, prospecção de pontos para LPR (Leitura de Placas), e integração opcional com dados de ônibus (URBS Curitiba).
2. **Shell institucional**: home, sistemas, administração, boletins, perfil, e-mail, notificações, chat (Converse.js), auditoria de logs — carregados principalmente por `routes/rotasTemplate.php`.
3. **Integração centralizada com o ecossistema SIA** (SIA-Auth / APIs internas): login federado, tokens JWT, catálogo de sistemas em `config/sistemas.php`, e cliente HTTP genérico em `app/apis/Api.php`.

O **README.md** ainda descreve o template genérico; o código e o `.env.example` refletem o uso real como sistema de monitoramento.

---

## Stack tecnológica

| Camada | Tecnologia |
|--------|------------|
| Backend | PHP (^7.3\|^8.0), Laravel 8 |
| Banco | PostgreSQL (principal + conexão `auditoria` opcional no `.env.example`) |
| Frontend assets | Laravel Mix 6, Bootstrap 4, jQuery, AdminLTE 3, Sass |
| Pacotes relevantes | `jeroennoten/laravel-adminlte`, `mews/captcha`, `yajra/laravel-datatables`, `spatie/laravel-query-builder`, `rap2hpoutre/laravel-log-viewer`, `fruitcake/laravel-cors` |

---

## Estrutura de rotas

- **`routes/web.php`**: rotas da aplicação “monitoramento” protegidas por `auth` + `auth2`:
  - `GET /` → `home.index` (home do domínio monitoramento)
  - Resource `cameras`, `cameras/view`, `cidades`, `mosaicos`, `atualizaMosaicos`
  - Resource `prospeccoesLPR` (DataTables na listagem)
  - `GET /onibus` — cache de posição de veículos via API externa (proxy configurado)
- **`routes/rotasTemplate.php`**: template institucional (login, home alternativa, sistemas, perfil, etc.). Inclui:
  - `GET /monitoramento` — health check do banco (CORS aberto `*`)
  - `GET /monitoramentos` — view `sistemas.monitoramento`
  - Fallback genérico para `home` com mensagem de erro
  - Rota longa `comunicados` com scraping de intranet e persistência via modelo/API de notas
- **`routes/api.php`**: rota `GET /api/update` que sincroniza arquivos de configuração/views a partir do SIA; rota `/api/user` com `auth:api`.
- **`routes/servicos.php`** (prefixo `/servicos`): lista rotas de serviço e inclui dinamicamente arquivos em `app/apis/*.php` (P1, Qo, Notas, Expresso, etc.).

**Observação:** Existem **duas definições** de `reload-captcha` (`web.php` e `rotasTemplate.php`) e possível sobreposição de rotas nomeadas; vale consolidar em manutenção futura.

---

## Autenticação e autorização

1. **`auth` (Laravel)** — usuário Eloquent (`App\Models\User`).
2. **`auth2` (`App\Http\Middleware\Auth2`)** — exige `Session::get('user')` preenchido (payload vindo do SIA após login). Se vazio, faz logout e redireciona ao login.
3. **Login**
   - Formulário local com **captcha** (`LoginController@login`).
   - **`GET /auth?token=...`** — JWT assinado com `SIA_CHAVE_ASSINATURA`; decodifica, cria/atualiza `User`, preenche sessão com objeto decodificado e `autorizacoes`.
4. **`autorizacao` middleware** — compara perfis necessários (string `;`) com `Session::get('autorizacoes')` (ex.: rota `auditoria` exige `Administrador` ou `Auditoria`).

---

## Domínio de dados (migrations principais)

- **`users`**: usuário Laravel; migração adiciona campo **`mosaico`** (JSON de layout de mosaico de câmeras).
- **`cameras`**: servidor, cidade, IP, porta, identificação da câmera, geolocalização, credenciais, protocolo, VMS, link, `ativo`; unicidade `(servidor, cidade, camera)`.
- **`prospeccao_l_p_r_s`**: nome, cidade, bairro, endereço, sentido, coordenadas, auditoria de cadastro (`cadastrada_por`, CPF, `user_id`); soft deletes.
- Outras tabelas padrão: `sessions`, `password_resets`, `failed_jobs`, `auditoria_migrations`.

---

## Módulos funcionais principais

### Câmeras (`CameraController`, `App\Models\Camera`)

- CRUD e busca por termo; view agregada `monitoramento.view`; criação com mapa (`lat`/`lng` via query).
- **Mosaicos**: leitura/gravação do JSON em `users.mosaico` para o usuário autenticado.

### Prospecção LPR (`ProspeccaoLPRController`)

- Listagem server-side com **DataTables**; cadastro via validação de campos obrigatórios e vínculo ao usuário da sessão SIA.

### Integração SIA / APIs (`app/apis/Api.php`, `SiaAPI.php`)

- Obtenção de token por sistema, chamadas GET/POST com parâmetros de sessão (OPM, RG, etc.) ou modo `chamadaSistema`.
- `config/sistemas.php` — mapa extenso de sistemas (URLs, portas, metadados para o hub “Sistemas”).

### Auditoria (`App\Log\Log`)

- `Log::auditoria()` grava em modelo de log local; em falha, tenta API `Auditoria`.

### Outros

- **Ônibus**: agregação com cache e classificação de status por atraso de “refresh” da API URBS.
- **Comunicados** (em `rotasTemplate.php`): scraping HTML da intranet; acoplamento forte a IPs/hosts internos.

---

## Configuração de ambiente (trechos relevantes)

- `DOMINIO_SIA`, `PORTA_SIA`, `SIA_SSL_VERIFY`, `SIA_CHAVE_ASSINATURA`, `SIA_ID_SOFTWARE` — integração SIA.
- `DB_*` e `DB_AUDITORIA_*` — PostgreSQL.
- `URL_INTRANET` — base para conteúdo institucional.

---

## Pontos de atenção (técnica e segurança)

- **`routes/api.php`**: uso de tipo `Response` sem import pode gerar erro em tempo de execução; token fixo `'hgjfhgjkefhjkgherjkgh'` na query — risco de segurança se a rota estiver exposta.
- **`composer.json`**: `disable-tls` e `secure-http: false` facilitam ambiente fechado, mas são **inadequados** para builds públicos ou CI sem isolamento.
- **Credenciais**: `.env.example` contém valores de exemplo (chaves, DB); em repositório real deve-se usar placeholders e secrets fora do Git.
- **`Auth2`**: `sleep(1)` no middleware — impacto em performance e UX.
- **Rotas duplicadas / fallback** — manutenção e testes automatizados ficam mais difíceis até normalizar.

---

## Testes e qualidade

- Estrutura PHPUnit padrão Laravel em `tests/`; não há evidência no resumo de cobertura ampla — recomenda-se expandir testes de feature para rotas críticas (login, câmeras, LPR).

---

## Conclusão

O repositório é um **monólito Laravel** com **dois eixos**: (a) **monitoramento operacional** (câmeras, LPR, utilitários) e (b) **portal template SIA** (navegação, integrações, comunicados). A evolução do produto deve priorizar **clareza de rotas**, **segurança das APIs de atualização** e **documentação alinhada ao código** (README).
