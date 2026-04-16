# Plano de implementação — Monitoramento

Este plano organiza entregas sugeridas em fases, da estabilização à evolução funcional. Ajuste prazos e prioridades conforme a célula DDTQ/SSI e dependências de infraestrutura (SIA, rede, banco).

---

## Fase 0 — Baseline e ambiente (curto prazo)

| # | Ação | Objetivo |
|---|------|----------|
| 0.1 | Documentar no README o propósito real (monitoramento + template SIA), pré-requisitos e variáveis obrigatórias | Evitar divergência entre documentação e código |
| 0.2 | Garantir `.env` por ambiente (dev/homolog/prod), sem secrets versionados | Segurança e reprodutibilidade |
| 0.3 | Pipeline de deploy: `composer install`, `npm ci`/`npm install`, `npm run production`, `php artisan migrate --force`, caches | Implantação previsível |
| 0.4 | Validar PostgreSQL e, se usado, conexão `auditoria` | Evitar falhas silenciosas em log/auditoria |

**Critério de saída:** aplicação sobe com migrations aplicadas e login testável (ambiente de homologação).

---

## Fase 1 — Correções técnicas e segurança (alta prioridade)

| # | Ação | Objetivo |
|---|------|----------|
| 1.1 | Corrigir `routes/api.php`: import de `Illuminate\Http\Response` ou remover parâmetro não usado; revisar autenticação da rota `update` (token fixo → segredo em env + validação de origem/IP ou assinatura) | Estabilidade e redução de superfície de ataque |
| 1.2 | Revisar `composer.json`: remover `disable-tls` / `secure-http` em ambientes que não precisem; documentar exceção se for estritamente necessário em rede interna | Supply chain e integridade de pacotes |
| 1.3 | Eliminar ou justificar `sleep(1)` em `Auth2`; preferir fluxo de sessão único | Latência e previsibilidade |
| 1.4 | Consolidar rotas duplicadas (`reload-captcha`, possivelmente `home`) entre `web.php` e `rotasTemplate.php` | Comportamento único e testável |

**Critério de saída:** rotas API sem erro de tipo; rota de update protegida; duplicatas mapeadas com decisão documentada.

---

## Fase 2 — Consistência do domínio “Monitoramento”

| # | Ação | Objetivo |
|---|------|----------|
| 2.1 | Definir **página inicial canônica** (`/`): apenas `web.php` ou apenas template; alinhar breadcrumbs e menu AdminLTE | UX clara |
| 2.2 | Política para **credenciais de câmera** (`usuario`/`senha` em `cameras`): criptografia em repouso ou cofre; mascarar na UI | Proteção de dados sensíveis |
| 2.3 | API REST interna (opcional) para câmeras/LPR com políticas de autorização explícitas | Integração com outros sistemas sem depender só de views |
| 2.4 | Tratar erros em `CameraController@store` (substituir `ddd()` por log + resposta HTTP adequada) | Comportamento em produção |

**Critério de saída:** fluxo de câmeras e LPR documentado; dados sensíveis com política definida.

---

## Fase 3 — Integrações externas

| # | Ação | Objetivo |
|---|------|----------|
| 3.1 | Externalizar hosts fixos (`10.47.1.20`, proxies URBS) para `.env` com valores padrão por ambiente | Portabilidade |
| 3.2 | Para scraping de comunicados: avaliar **API oficial** ou fila de sincronização (job agendado) em vez de request síncrono pesado | Performance e manutenção |
| 3.3 | Testes de contrato ou smoke tests contra SIA-Auth em homologação | Regressões em login/token |

**Critério de saída:** integrações configuráveis; jobs documentados onde fizer sentido.

---

## Fase 4 — Qualidade e observabilidade

| # | Ação | Objetivo |
|---|------|----------|
| 4.1 | Testes PHPUnit: login (mock HTTP SIA), CRUD mínimo de `Camera` e `ProspeccaoLPR`, health `GET /monitoramento` | Regressão automatizada |
| 4.2 | Padronizar uso de `App\Log\Log` vs `Illuminate\Support\Facades\Log` | Rastreabilidade |
| 4.3 | Revisar `throttle` e CORS em rotas públicas (`/monitoramento`) | Abuso e política de origem |

**Critério de saída:** CI executando testes; métricas/logs revisados.

---

## Fase 5 — Evolução de produto (opcional)

| # | Ação | Objetivo |
|---|------|----------|
| 5.1 | Mapa unificado (câmeras + prospecções LPR) com camadas | Visão operacional única |
| 5.2 | Relatórios/exportação (Excel/PDF) para prospecções | Entregas gerenciais |
| 5.3 | Avaliar upgrade Laravel/PHP em médio prazo (compatibilidade com ecossistema SIA) | Suporte de longo prazo |

---

## Ordem sugerida de execução

1. Fase 0 → Fase 1 (sem ambiente estável e segurança mínima, o resto acumula débito).  
2. Fase 2 em paralelo com decisões de produto (home e dados sensíveis).  
3. Fases 3 e 4 conforme carga e exposição das integrações.  
4. Fase 5 após baseline estável.

---

## Riscos e dependências

- **SIA-Auth / rede interna**: indisponibilidade bloqueia login federado e várias APIs.  
- **Scraping de intranet**: quebra silenciosa se o HTML mudar.  
- **URBS / proxy**: dependência de conectividade e política de proxy institucional.

---

## Entregáveis deste plano

Checklist simples: marcar cada item ao concluir; anexar issues/tickets internos aos IDs 1.x, 2.x, etc., conforme o processo da equipe.
