# Log de cocriação

Este log registra, em cada etapa do desenvolvimento, a pergunta feita à IA,
o resumo da resposta, a decisão tomada e sua justificativa.

## Etapa 0 — Contextualização e análise da arquitetura

**Pergunta feita à IA**

> Contextualização do projeto: Estou trabalhando em um sistema MVC em PHP. Já possuo a View OutrasFormações e um Model correspondente. Quero integrar a camada Controller, contemplando inserir, remover e listar. Você pode revisar rapidamente a arquitetura esperada e listar os arquivos de Controller que precisarei criar/alterar (com nomes e responsabilidades)?

**Resumo da resposta**

- `Controller/OutrasFormacoesController.php` já existe e implementa os métodos `inserir`, `remover` e `gerarLista`, delegando as operações ao Model.
- `Model/OutrasFormacoes.php` já contém as operações de persistência correspondentes.
- `Controller/Navegacao.php` já encaminha as ações de inclusão e remoção para o Controller.
- A tabela de Outras Formações em `View/principal.php` apresenta os cabeçalhos, mas ainda não renderiza os registros. A consulta do Controller também não está conectada à View.
- Portanto, não é necessário criar outro arquivo de Controller. A integração da listagem deve conectar a navegação ao método `gerarLista` e apresentar os resultados na View.

**Decisão tomada**

Manter o Controller existente e integrar a listagem usando `Controller/Navegacao.php` como ponto de encaminhamento e `View/principal.php` para exibir as formações retornadas.

**Justificativa**

A camada Controller já reúne as responsabilidades solicitadas e delega o acesso aos dados ao Model. Reaproveitá-la evita duplicação; a lacuna observada está na ligação entre a consulta e a apresentação dos dados.

## Etapa 1 — Esqueleto do Controller

**Pergunta feita à IA**

> Gere um esqueleto de OutrasFormacoesController.php em PHP, padrão MVC, com métodos inserir(), remover() e gerarLista(). Inclua docblocks resumindo responsabilidades. Suponha um Model OutrasFormacoesModel com métodos criar($dados), excluir($id), listar(). Não implemente detalhes do banco, apenas chamadas ao Model e retornos para a View.

**Resumo da resposta**

O Controller do projeto já implementa os três métodos solicitados e delega as operações ao Model. Foram documentadas suas responsabilidades com docblocks. Como o projeto usa `OutrasFormacoes` com os métodos `inserirBD`, `excluirBD` e `listaFormacoes`, o esqueleto foi adaptado à API existente, sem introduzir o Model hipotético `OutrasFormacoesModel`.

**Decisão tomada**

Manter os métodos, parâmetros e chamadas já integrados ao projeto e acrescentar docblocks em `Controller/OutrasFormacoesController.php`.

**Justificativa**

Trocar o contrato atual pelos nomes hipotéticos (`criar`, `excluir`, `listar`) poderia quebrar a integração com o Model existente e com a navegação. A documentação esclarece a responsabilidade de cada método sem alterar esse comportamento.

## Etapa 2 — Validação de Outras Formações

**Pergunta feita à IA**

> Crie regras de validação para o Controller OutrasFormacoesController: campos obrigatórios (curso, instituição, carga horária, conclusão), limites de tamanho, e formato de datas. Retorne mensagens amigáveis para a View. Inclua tratamento de exceções do Model e redirecionamentos apropriados.

**Resumo da resposta**

Como o projeto atual não possui campos separados para curso, instituição e carga horária, foi escolhida a validação dos campos existentes: início, fim/conclusão e descrição (até 150 caracteres). O Controller valida os campos e as datas no servidor, retorna mensagens amigáveis, e a navegação usa redirecionamento após o POST. Também foi corrigida a conexão para usar MySQLi, como os Models esperam, permitindo tratar exceções lançadas nas operações.

**Decisão tomada**

Manter o formulário e o banco de dados atuais; exigir início, conclusão e descrição, validar o formato e a ordem das datas, limitar a descrição a 150 caracteres, apresentar erros junto ao formulário e usar POST/Redirect/GET para evitar reenvios acidentais.

**Justificativa**

Adicionar curso, instituição e carga horária como campos próprios exigiria mudanças de escopo na View, no Model e no esquema SQL. A adaptação usa o contrato que já existe, preserva a compatibilidade da funcionalidade e oferece feedback sem expor detalhes técnicos do banco. A conexão foi alinhada ao MySQLi porque os Models usam essa API e a classe anterior estava sintaticamente inválida e retornava PDO.

## Etapa 3 — Integração da View e listagem

**Pergunta feita à IA**

> Analise este snippet de View OutrasFormações (cole abaixo o formulário e a tabela). Aponte se as action e method estão alinhadas às rotas do Controller, e proponha ajustes mínimos. Mostre como exibir mensagens de sucesso/erro e preencher a tabela com o retorno de gerarLista().

**Resumo da resposta**

O formulário de inclusão usa `POST` para `/Controller/navegacao.php`, que reconhece `btnAddOF`; portanto, a action e o method estão alinhados. A tabela, porém, não era preenchida e não havia um formulário de exclusão por linha. A View passou a chamar `gerarLista()` para o usuário da sessão, renderizar os registros e exibir uma opção de exclusão por item. As mensagens de sucesso e erro são apresentadas na própria seção e o POST de exclusão redireciona de volta a ela. A exclusão também foi limitada ao usuário autenticado.

**Decisão tomada**

Manter o endpoint e o método HTTP atuais, conectar a consulta do Controller à tabela e enviar exclusões como POST para a navegação, filtrando os registros e as exclusões pelo usuário da sessão.

**Justificativa**

O endpoint já encaminhava inclusão e exclusão, então não era necessário criar novas rotas. A View precisava consumir o resultado de `gerarLista()` e fornecer o identificador do registro para a ação de exclusão. Vincular a exclusão ao usuário impede que a alteração de um identificador enviado pelo navegador remova registros de outra conta.

## Etapa 4 — Plano de testes manuais

**Pergunta feita à IA**

> Monte um plano de testes manuais para o Controller OutrasFormacoesController cobrindo casos de sucesso e falhas: inserir válido/inválido, remover existente/inexistente, listar vazio, e comportamento pós-redirecionamento. Forneça passos, dados de exemplo e resultado esperado.

**Resumo da resposta**

Foi elaborado um roteiro manual para testar as operações de inclusão, exclusão e listagem de Outras Formações pela interface, incluindo validação de falhas e o redirecionamento após o envio. Os testes usam os campos reais do projeto: início, fim e descrição.

**Decisão tomada**

Executar os cenários abaixo em ambiente local com o MySQL configurado, usando um usuário de teste e verificando tanto a resposta da interface quanto os registros da tabela `outrasformacoes`.

**Justificativa**

Os testes cobrem os fluxos de uso e os casos de borda solicitados, confirmando que a validação, o escopo por usuário, as mensagens e o redirecionamento funcionam em conjunto.

### Preparação

1. Inicie o Apache e o MySQL e carregue o esquema do projeto.
2. Crie ou use uma conta de teste e entre no sistema.
3. Para testar lista vazia, use uma conta sem registros de outras formações. Para testar exclusão, cadastre uma formação de teste.
4. Acesse a seção **Outras Formações** em `View/principal.php`.

### Teste 4.1 — Inserir formação válida

1. Preencha início `2024-01-15`, fim `2024-06-30` e descrição `Curso de Inglês - Inglês City`.
2. Envie o formulário.
3. **Resultado esperado:** o navegador retorna à seção `#oFormacao`, aparece a mensagem de sucesso e a tabela exibe a nova formação com as datas e a descrição informadas.

### Teste 4.2 — Tentar inserir dados inválidos

1. Tente enviar os campos obrigatórios vazios. O navegador pode bloquear o envio por causa dos atributos `required`.
2. Para confirmar a validação no servidor, envie também uma requisição POST de teste sem início, com início impossível `2024-02-30`, com fim anterior ao início (`2024-01-01` e `2023-12-31`) ou com descrição de 151 caracteres.
3. **Resultado esperado:** não há novo registro; após o redirecionamento, são exibidas mensagens amigáveis para os campos inválidos e os valores digitados permanecem preenchidos quando foram enviados.

### Teste 4.3 — Remover formação existente

1. Na linha da formação de teste, clique em **Excluir**.
2. **Resultado esperado:** o navegador retorna à seção `#oFormacao`, mostra a mensagem de sucesso e a formação removida deixa de aparecer na tabela. A exclusão deve afetar somente o usuário autenticado.

### Teste 4.4 — Tentar remover formação inexistente

1. Envie o formulário de exclusão com um identificador numérico que não corresponda a uma formação do usuário atual (por exemplo, um número muito alto).
2. **Resultado esperado:** nenhum registro é removido; após o redirecionamento, é exibida uma mensagem de falha e a página continua utilizável.

### Teste 4.5 — Listar sem formações

1. Entre com uma conta que não tenha registros na tabela `outrasformacoes`.
2. Abra ou atualize a seção **Outras Formações**.
3. **Resultado esperado:** a tabela apresenta “Nenhuma outra formação cadastrada.”, sem linhas de dados nem mensagens de erro.

### Teste 4.6 — Comportamento após redirecionamento

1. Conclua uma inclusão válida ou tente uma inclusão inválida.
2. Confira que o endereço termina em `/Controller/navegacao.php?pagina=principal#oFormacao` e que a mensagem correspondente aparece uma única vez.
3. Atualize a página.
4. **Resultado esperado:** a mensagem temporária não reaparece e o navegador não repete o POST nem cria uma segunda formação.

## Etapa 5 — Documentação do módulo

**Pergunta feita à IA**

> Gere um README_OutrasFormacoes.md conciso descrevendo rotas do módulo, parâmetros aceitos, exemplos de requisição/resposta, mensagens de erro mais comuns e dependências do Controller.

**Resumo da resposta**

Foi criado `README_OutrasFormacoes.md` descrevendo os endpoints GET/POST reais em `Controller/Navegacao.php`, seus campos, exemplos de inclusão e exclusão, o redirecionamento pós-POST, as mensagens mais comuns e os retornos do Controller. A documentação também identifica as dependências de PHP/MySQL e os arquivos do módulo.

**Decisão tomada**

Documentar o comportamento implementado, incluindo que a resposta é HTML com redirecionamento e mensagens, e não uma API JSON.

**Justificativa**

Assim, os exemplos refletem o contrato atual entre formulário, navegação, Controller e Model, sem sugerir rotas ou formatos de resposta que o projeto não oferece.

## Correção complementar — Identificação do usuário na sessão

**Pergunta feita à IA**

> Como corrigir o erro “Não foi possível identificar o usuário das formações”?

**Resumo da resposta**

A causa era o login considerar sucesso mesmo quando o CPF não correspondia a um usuário. Nesse caso, um objeto sem ID era salvo na sessão e a listagem de formações rejeitava esse ID. O Controller agora só cria sessão autenticada após localizar um usuário com ID e validar a senha; o cadastro também só salva a sessão após uma inserção bem-sucedida. A busca por CPF usa uma consulta preparada e a rota principal limpa sessões sem um ID válido e retorna ao início.

**Decisão tomada**

Validar a existência e o ID do usuário antes de autenticar e proteger a sessão da página principal contra objetos inválidos.

**Justificativa**

Isso corrige a origem do erro e evita que CPF desconhecido ou cadastro falho gere uma sessão sem identificador, em vez de apenas ocultar a mensagem na View.
