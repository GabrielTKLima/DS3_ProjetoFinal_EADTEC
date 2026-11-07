<?php

if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class OutrasFormacoesController{

    private $mensagens = array();

    /**
     * Retorna as mensagens de validação ou de falha da última operação.
     *
     * @return array
     */
    public function getMensagens()
    {
        return $this->mensagens;
    }
    
    /**
     * Cria uma formação complementar associada ao usuário informado.
     *
     * @return bool Resultado da operação de inserção no Model.
     */
    public function inserir($inicio, $fim, $descricao,$idusuario) {
        $this->mensagens = array();
        $inicio = is_string($inicio) ? trim($inicio) : '';
        $fim = is_string($fim) ? trim($fim) : '';
        $descricao = is_string($descricao) ? trim($descricao) : '';

        if ($inicio === '') {
            $this->mensagens[] = 'Informe a data de início.';
        } elseif (!$this->dataValida($inicio)) {
            $this->mensagens[] = 'A data de início é inválida. Use o formato AAAA-MM-DD.';
        }

        if ($fim === '') {
            $this->mensagens[] = 'Informe a data de conclusão.';
        } elseif (!$this->dataValida($fim)) {
            $this->mensagens[] = 'A data de conclusão é inválida. Use o formato AAAA-MM-DD.';
        }

        if ($inicio !== '' && $fim !== '' && $this->dataValida($inicio) &&
            $this->dataValida($fim) && $fim < $inicio) {
            $this->mensagens[] = 'A data de conclusão não pode ser anterior à data de início.';
        }

        if ($descricao === '') {
            $this->mensagens[] = 'Informe a descrição da formação.';
        } else {
            $quantidadeCaracteres = preg_match_all('/./us', $descricao, $caracteres);
            if ($quantidadeCaracteres === false) {
                $this->mensagens[] = 'A descrição contém caracteres inválidos.';
            } elseif ($quantidadeCaracteres > 150) {
                $this->mensagens[] = 'A descrição deve ter no máximo 150 caracteres.';
            }
        }

        $idusuarioValidado = filter_var($idusuario, FILTER_VALIDATE_INT);
        if ($idusuarioValidado === false || $idusuarioValidado <= 0) {
            $this->mensagens[] = 'Não foi possível identificar o usuário. Entre novamente no sistema.';
        }

        if (!empty($this->mensagens)) {
            return false;
        }

        require_once __DIR__ . '/../Model/OutrasFormacoes.php';
        $formacao = new OutrasFormacoes();
        $formacao->setInicio($inicio);
        $formacao->setFim($fim);
        $formacao->setDescricao($descricao); 
        $formacao->setIdUsuario($idusuarioValidado);
        try {
            return $formacao->inserirBD();
        } catch (mysqli_sql_exception $exception) {
            error_log('Falha ao inserir outra formação: ' . $exception->getMessage());
            $this->mensagens[] = 'Não foi possível salvar a formação. Tente novamente.';
            return false;
        }
    }

    /**
     * Solicita ao Model a exclusão de uma formação pelo identificador.
     *
     * @return bool Resultado da operação de exclusão no Model.
     */
    public function remover($id, $idusuario) {
        $this->mensagens = array();
        $idValidado = filter_var($id, FILTER_VALIDATE_INT);
        $idUsuarioValidado = filter_var($idusuario, FILTER_VALIDATE_INT);
        if ($idValidado === false || $idValidado <= 0 ||
            $idUsuarioValidado === false || $idUsuarioValidado <= 0) {
            $this->mensagens[] = 'A formação selecionada é inválida.';
            return false;
        }

        require_once __DIR__ . '/../Model/OutrasFormacoes.php';
        $formacao = new OutrasFormacoes();
        $formacao->setIdUsuario($idUsuarioValidado);
        try {
            return $formacao->excluirBD($idValidado);
        } catch (mysqli_sql_exception $exception) {
            error_log('Falha ao remover outra formação: ' . $exception->getMessage());
            $this->mensagens[] = 'Não foi possível excluir a formação. Tente novamente.';
            return false;
        }
    }

    /**
     * Obtém do Model as formações vinculadas ao usuário informado para a View.
     *
     * @return mysqli_result|false Registros retornados pelo Model ou false em caso de falha.
     */
    public function gerarLista($idusuario)
    {
        $this->mensagens = array();
        $idusuarioValidado = filter_var($idusuario, FILTER_VALIDATE_INT);
        if ($idusuarioValidado === false || $idusuarioValidado <= 0) {
            $this->mensagens[] = 'Não foi possível identificar o usuário das formações.';
            return false;
        }

        require_once __DIR__ . '/../Model/OutrasFormacoes.php';
        $formacao = new OutrasFormacoes();
        try {
            return $formacao->listaFormacoes($idusuarioValidado);
        } catch (mysqli_sql_exception $exception) {
            error_log('Falha ao listar outras formações: ' . $exception->getMessage());
            $this->mensagens[] = 'Não foi possível carregar as formações. Atualize a página para tentar novamente.';
            return false;
        }
    }

    private function dataValida($data)
    {
        $dataFormatada = DateTime::createFromFormat('!Y-m-d', $data);
        $erros = DateTime::getLastErrors();

        return $dataFormatada !== false &&
            ($erros === false || ($erros['warning_count'] === 0 && $erros['error_count'] === 0)) &&
            $dataFormatada->format('Y-m-d') === $data;
    }

}

?>