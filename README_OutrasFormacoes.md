# Módulo Outras Formações

## Rotas

As ações são encaminhadas por `Controller/navegacao.php`:

| Ação | Método HTTP | Parâmetros |
|---|---|---|
| Exibir a página principal autenticada | `GET` | `pagina=principal` |
| Inserir formação | `POST` | `btnAddOF`, `txtInicioOF`, `txtFimOF`, `txtDescOF` |
| Remover formação | `POST` | `btnExcluirOF`, `id` |

As datas devem estar no formato `AAAA-MM-DD`; início, fim e descrição são
obrigatórios, o fim não pode preceder o início e a descrição aceita até 150
caracteres. A página principal lista automaticamente as formações pertencentes
ao usuário autenticado. O identificador do usuário vem da sessão, não do
formulário.

Exemplo de inclusão (formulário URL-encoded):

```text
POST /Controller/navegacao.php
btnAddOF=1&txtInicioOF=2024-01-15&txtFimOF=2024-06-30&txtDescOF=Curso+de+Ingl%C3%AAs
```

Após inclusão ou exclusão, a navegação redireciona para:

```text
/Controller/navegacao.php?pagina=principal#oFormacao
```

A resposta é uma página HTML, não JSON. A seção mostra uma mensagem de sucesso
ou erro e, quando a operação tem êxito, a tabela atualizada.

Exemplo de exclusão:

```text
POST /Controller/navegacao.php
btnExcluirOF=1&id=12
```

## Controller

`Controller/OutrasFormacoesController.php` disponibiliza:

- `inserir($inicio, $fim, $descricao, $idusuario)`: retorna `true` em caso de
  sucesso ou `false` se a validação ou persistência falhar.
- `remover($id, $idusuario)`: retorna `true` se removeu um registro do usuário
  informado; caso contrário, retorna `false`.
- `gerarLista($idusuario)`: retorna um `mysqli_result` com `idoutrasformacoes`,
  `inicio`, `fim` e `descricao`, ou `false` se houver falha.
- `getMensagens()`: retorna mensagens de validação ou de erro da última
  operação.

Exemplo de uso pelo Controller:

```php
$controller = new OutrasFormacoesController();
$sucesso = $controller->inserir('2024-01-15', '2024-06-30', 'Curso de Inglês', 3);
$mensagens = $controller->getMensagens();
```

## Mensagens comuns

- “Informe a data de início.” / “Informe a data de conclusão.”
- “A data de início é inválida. Use o formato AAAA-MM-DD.”
- “A data de conclusão não pode ser anterior à data de início.”
- “Informe a descrição da formação.”
- “A descrição deve ter no máximo 150 caracteres.”
- “Não foi possível salvar a formação. Tente novamente.”
- “Não foi possível excluir a formação. Tente novamente.”
- “Não foi possível carregar as formações. Atualize a página para tentar novamente.”

Em uma listagem válida sem registros, a tabela mostra “Nenhuma outra formação
cadastrada.”

## Dependências

- PHP com as extensões `mysqli` e `mysqlnd` (para `mysqli_stmt::get_result()`).
- MySQL com o esquema `projeto_final` e a tabela `outrasformacoes`, incluindo
  as colunas `idoutrasformacoes`, `idusuario`, `inicio`, `fim` e `descricao`.
- Sessão autenticada com o objeto serializado do usuário em
  `$_SESSION['Usuario']`.
- `Controller/Navegacao.php`, `Model/OutrasFormacoes.php` e
  `Model/ConexaoBD.php`.

Configure servidor, usuário, senha e nome do banco em `Model/ConexaoBD.php`
conforme o ambiente local.
