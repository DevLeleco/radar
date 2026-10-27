# Pendências do Radar

Lista atualizada após as etapas de isolamento por cliente e vínculo entre chamados e ordens de serviço. As migrations `2026_10_27_000009` e `2026_10_27_000010` foram aplicadas ao PostgreSQL local usado nesta etapa. Não foram executados testes automatizados.

## P0 — Segurança e isolamento

- [ ] Criar testes automatizados para login, autorização por perfil e isolamento entre clientes nas consultas e ações de chamados, unidades, equipamentos e QR codes.
- [ ] Ajustar a validação dos endpoints de lookup para validar os IDs dentro do escopo do cliente autenticado e evitar distinção entre IDs existentes de outros clientes e IDs inexistentes.
- [ ] Rotacionar a credencial de banco que foi incluída no histórico Git e avaliar com a equipe responsável a limpeza segura do histórico.
- [ ] Validar em ambiente de implantação HTTPS, cookies seguros, domínio da sessão e configuração de proxy confiável.

## P1 — Integridade e regras do produto

- [ ] Rever a numeração dos chamados para que seja inequívoca, resistente a exclusões e concorrência; definir se os números existentes serão migrados.
- [ ] Definir e implementar a regra de cancelamento para chamados atribuídos e concluídos; alinhar mensagens e permissões ao ciclo de vida decidido.
- [ ] Decidir como reconciliar ordens de serviço antigas que permaneceram sem `id_chamado`; atualmente novas ordens só podem ser criadas para chamados concluídos e cada chamado aceita uma única ordem.
- [ ] Validar as migrations 009 e 010 em uma base de teste limpa e em uma cópia segura de uma base existente antes de promover para outros ambientes.
- [ ] Preparar estratégia de baseline/reconciliação para bases existentes sem histórico completo na tabela `migrations`.
- [ ] Revisar nulabilidade, índices, exclusões e cardinalidade das relações com o responsável pelo domínio antes de produção.

## P1 — Execução local e operação

- [ ] Confirmar que `.env` aponta para o PostgreSQL local esperado e que as credenciais configuradas correspondem à role e ao banco criados.
- [ ] Configurar e validar SMTP com uma conta de teste para recuperação de senha; revisar remetente, transporte e validade dos tokens.
- [ ] Documentar e validar backup e recuperação do PostgreSQL para os ambientes utilizados.
- [ ] Executar auditoria atualizada das dependências PHP e JavaScript e manter o resultado associado aos lockfiles atuais.

## P2 — Qualidade e manutenção

- [ ] Substituir os testes de exemplo do Laravel por testes de domínio para clientes, usuários, técnicos, chamados, QR codes e ordens de serviço.
- [ ] Adicionar pipeline de integração contínua para testes, análise estática, formatação e auditoria de dependências.
- [ ] Padronizar respostas para falhas inesperadas e adicionar logging contextual sem registrar credenciais ou dados pessoais desnecessários.
- [ ] Completar trilha de auditoria das ações administrativas.
- [ ] Refatorar regras e consultas dos controladores em serviços e policies, reduzindo duplicação.

## Concluído recentemente

- [x] Criado seeder idempotente para dados de demonstração e executado no PostgreSQL local: cinco clientes, cinco administradores de cliente, 25 técnicos, 50 equipamentos e 50 chamados.
- [x] Bloqueado o seeder de demonstração em produção e exigida autorização explícita em ambientes compartilhados.
- [x] Reforçadas as junções das consultas de chamados para relacionar cliente, unidade e equipamento.
- [x] Aplicada a migration de integridade composta entre chamado, cliente, unidade e equipamento.
- [x] Implementado vínculo único entre novas ordens de serviço e chamados concluídos; ordens antigas continuam como registros legados sem vínculo.
- [x] Documentado no README o fluxo de preparação e execução local com PostgreSQL, Composer, Node/npm, migrations e assets.
