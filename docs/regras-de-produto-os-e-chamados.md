# Regras de produto: ordens de serviço e chamados

Este documento registra as regras de negócio acordadas para a relação entre ordens de serviço (OS), chamados, administradores/gerentes e técnicos. Ele serve de referência para atualizar o sistema; não descreve necessariamente o comportamento implementado hoje.

## Conceitos

- **Chamado:** solicitação de atendimento registrada para uma ocorrência.
- **Ordem de serviço (OS):** instrumento de planejamento e distribuição do trabalho associado a um ou mais chamados.
- **Responsável:** técnico designado para executar uma OS inteira ou um chamado específico dentro de uma OS.

## Regras acordadas

1. A OS pode ser criada antes de existir qualquer chamado associado.
2. Chamados podem ser associados a uma OS depois que ela foi criada.
3. Uma OS pode conter nenhum, um ou vários chamados.
4. Um chamado pode estar associado a várias OS ao longo do tempo, mantendo o histórico das associações.
5. Um chamado pode pertencer a no máximo uma OS ativa por vez. Ele só pode ser associado a outra OS quando a associação anterior deixar de estar ativa.
6. Um administrador ou gerente pode direcionar uma OS inteira a um técnico.
7. Um administrador ou gerente também pode distribuir os chamados de uma OS entre os técnicos responsáveis. Essa distribuição permite que chamados da mesma OS tenham técnicos diferentes.
8. O técnico responsável pode fechar o chamado após executar o atendimento.
9. Deve ser possível cancelar individualmente um chamado ou cancelar uma OS.
10. Cancelamentos devem preservar o registro para consulta histórica, em vez de apagar fisicamente a OS ou o chamado.

## Fluxo de trabalho esperado

1. Um administrador ou gerente cria uma OS.
2. Chamados são associados à OS conforme a necessidade operacional.
3. O administrador ou gerente atribui a OS a um técnico ou distribui chamados individuais entre técnicos.
4. Cada técnico executa e fecha os chamados sob sua responsabilidade.
5. Chamados e OS podem ser cancelados por usuários autorizados, conforme as regras de autorização a definir.

## Histórico e integridade

- Cada associação entre chamado e OS deve ser rastreável ao longo do tempo; remover uma associação ativa não deve apagar associações anteriores.
- O sistema deve impedir que o mesmo chamado tenha duas associações ativas simultaneamente.
- O sistema deve registrar quem criou, associou, atribuiu, fechou ou cancelou cada registro e quando a ação ocorreu.
- O cancelamento deve registrar o autor, a data e um motivo. A obrigatoriedade e o tamanho mínimo do motivo ainda precisam ser definidos.
- O fechamento do chamado deve preservar os dados do atendimento, incluindo solução e data de conclusão. Outros campos obrigatórios, como assinatura, precisam ser confirmados.

## Decisões de produto ainda necessárias

Os itens abaixo não foram definidos e devem ser resolvidos antes de implementar as transições finais de estado:

1. **Escopo de cliente da OS:** uma OS pertence a um único cliente? Se sim, os chamados associados devem pertencer ao mesmo cliente? Recomendação: sim, para manter isolamento e evitar misturar contratos.
2. **Cancelamento da OS:** o que acontece com chamados ainda ativos quando a OS é cancelada — voltam para uma fila sem OS, são cancelados junto ou exigem uma decisão individual?
3. **Encerramento da OS:** a OS terá ação e estado próprios de encerramento? Se sim, ela só pode ser encerrada quando todos os chamados estiverem fechados ou cancelados?
4. **Chamado sem OS:** ele pode ser atribuído diretamente a um técnico ou todo atendimento precisa passar por uma OS?
5. **Atribuição da OS inteira:** a atribuição global define o técnico inicial de todos os chamados? Uma atribuição individual posterior pode substituir esse responsável?
6. **Reabertura e nova OS:** se um chamado fechado for reaberto, ele volta à OS anterior ou exige uma nova associação? A recomendação é preservar o histórico e permitir uma nova OS ativa.
7. **Fechamento do chamado:** quais dados são obrigatórios além da solução e da data de conclusão (por exemplo, assinatura, evidência ou avaliação do cliente)?
8. **Permissões:** quais perfis podem criar OS, associar chamados, atribuir técnicos, fechar chamados e cancelar registros?

## Diferença em relação ao sistema atual

O comportamento atual não atende a este fluxo: uma OS nova é criada somente a partir de um chamado concluído, a relação é um para um e os dados do chamado são copiados para a OS. Para suportar as regras acordadas, será necessário substituir o vínculo atual por um histórico de associações chamado–OS e revisar atribuições, estados, permissões e telas de operação.
