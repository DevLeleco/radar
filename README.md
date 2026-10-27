# Radar

Aplicação web para cadastro de clientes, unidades, equipamentos, usuários e técnicos, além de abertura e acompanhamento de chamados, ordens de serviço e consulta por QR code.

## Requisitos

- PHP 8.3 ou superior, conforme `composer.json`.
- Composer 2.x.
- PostgreSQL e a extensão PHP `pdo_pgsql` habilitada.
- Extensões PHP exigidas pelo Laravel/Composer; `composer check-platform-reqs` verifica o ambiente após instalar as dependências.
- Node.js 24 e npm (a versão principal está indicada em `.nvmrc`) para compilar os assets do frontend.

O projeto usa Laravel 13, AdminLTE 3, Vite 8 e PostgreSQL por padrão. Não há Dockerfile nem `docker-compose` neste repositório; prepare os serviços localmente ou use um ambiente equivalente.

## Executar localmente com PostgreSQL

Os comandos abaixo assumem um banco novo chamado `radar` e uma role PostgreSQL `radar`. Se já tiver uma role e um banco próprios, use esses valores nas configurações do `.env`.

1. Instale PHP, Composer, PostgreSQL e Node.js 24. Confirme que o PHP CLI tem `pdo_pgsql` habilitada e instale as dependências do projeto:

   ```sh
   composer install
   composer check-platform-reqs
   npm ci
   ```

2. Crie a role e o banco vazios no PostgreSQL. No `psql`, substitua o valor de senha por uma credencial local forte:

   ```sql
   CREATE ROLE radar WITH LOGIN PASSWORD 'SENHA_LOCAL_FORTE';
   CREATE DATABASE radar OWNER radar;
   ```

3. Crie o `.env` e configure `APP_URL`, `DB_*` e os dados do primeiro administrador. `RADAR_ADMIN_PASSWORD` deve ter ao menos 12 caracteres. Configure também as variáveis `MAIL_*` se for validar recuperação de senha:

   ```sh
   cp .env.example .env
   ```

   O `.env.example` já aponta para PostgreSQL em `127.0.0.1:5432`, banco `radar` e role `radar`. Ajuste esses valores se o serviço local usar outro host, porta, banco ou usuário. Não coloque credenciais reais no Git.

4. Gere a chave da aplicação:

   ```sh
   php artisan key:generate
   ```

5. Aplique as migrations e crie o administrador inicial configurado no `.env`:

   ```sh
   php artisan migrate --seed
   ```

   O `DatabaseSeeder` cria o primeiro administrador somente quando `RADAR_ADMIN_NAME`, `RADAR_ADMIN_EMAIL` e `RADAR_ADMIN_PASSWORD` estão definidos. Se o e-mail já existir e não for administrador, a execução para sem alterar essa conta. Guarde a credencial em local seguro e remova a senha do `.env` depois do primeiro uso.

6. Compile os assets do frontend:

   ```sh
   npm run build
   ```

7. Inicie o servidor local:

   ```sh
   php artisan serve --host=127.0.0.1 --port=8000
   ```

Abra `http://127.0.0.1:8000` no navegador. Para recompilar assets automaticamente enquanto desenvolve, use `npm run dev` em outro terminal no lugar de `npm run build`; mantenha esse processo ativo junto com `php artisan serve`.

### Dados locais de demonstração

Opcionalmente, defina `RADAR_DEMO_PASSWORD` no `.env` com ao menos 12 caracteres e execute:

```sh
php artisan db:seed --class=DemoDataSeeder
```

Em ambiente `local` ou `testing`, o seeder cria os dados de demonstração. Em outros ambientes exige `RADAR_ALLOW_DEMO_SEED=true`; em produção é sempre bloqueado. Ele pode criar um administrador do sistema caso ainda não exista e usa `RADAR_ADMIN_PASSWORD` quando configurada ou, caso contrário, a senha de demonstração. As contas de cliente e técnico usam a senha de demonstração. Não reutilize essas credenciais fora de uma base local descartável.

### Configurações opcionais

- Configure `MAIL_*` no `.env` para testar envio de redefinição de senha. Os valores de exemplo não são um serviço SMTP funcional.
- Para limpar configurações em cache após alterar o `.env`, execute `php artisan config:clear`.
- O script `php artisan serve` é para desenvolvimento. Em produção, configure Apache ou Nginx para servir `public/`, habilite HTTPS, defina `SESSION_SECURE_COOKIE=true`, configure proxy reverso e domínio de sessão, permita escrita PHP em `storage/` e `bootstrap/cache/` e mantenha `APP_DEBUG=false`.

## Banco existente

As migrations de domínio deste repositório foram criadas para uma instalação nova. **Não execute `php artisan migrate` em uma base existente sem antes fazer backup e comparar o esquema e os dados.** As tabelas de negócio podem já existir sem registro na tabela `migrations`; nesse caso, será necessário preparar uma estratégia de reconciliação, importação ou baseline. Verifique duplicidades e relações órfãs antes de adicionar índices únicos e chaves estrangeiras. As migrations de técnicos e de consistência de chamados interrompem com mensagem de reconciliação se os dados não cumprirem as novas relações.

As migrations definem as tabelas `cliente`, `unidade`, `equipamento`, `tecnico`, `chamado` e `os`, além das colunas `id_cliente` e `perfil` em `users`. Chamados têm restrições para garantir que unidade e equipamento pertencem à mesma relação de cliente. Novas ordens de serviço referenciam um chamado concluído; ordens antigas podem permanecer sem vínculo.

## Acesso e contas

- `perfil = 1`: administrador do sistema, com acesso global aos clientes, contas, técnicos e operação.
- `perfil = 2`: administrador/usuário de cliente, limitado aos chamados, histórico e QR codes do próprio cliente.
- `perfil = 3`: técnico, com uma conta própria associada a um cliente e a um registro de técnico; só acessa chamados do próprio cliente atribuídos a ele ou ainda sem atribuição.
- Não existe cadastro público. O primeiro administrador é criado pelo `DatabaseSeeder` quando as três variáveis `RADAR_ADMIN_*` são configuradas; usuários seguintes são administrados pela aplicação.
- A redefinição de senha envia um link por e-mail. Configure SMTP antes de depender desse fluxo.

Técnicos são usuários distintos, não administradores associados a um cadastro técnico. O cadastro de técnico cria a conta e o registro técnico na mesma transação. A gestão de usuários e de técnicos é restrita ao administrador do sistema.

## Implantação

O `Procfile` contém uma configuração de exemplo para Heroku com Apache e `public/` como raiz web. Configure as variáveis de ambiente e o serviço de banco/SMTP na plataforma antes de publicar.

## Testes

Os testes existentes são exemplos do Laravel e ainda não cobrem as regras de negócio do Radar. Execute a suíte somente depois de preparar um ambiente de teste isolado e implementar migrations compatíveis com esse ambiente.
