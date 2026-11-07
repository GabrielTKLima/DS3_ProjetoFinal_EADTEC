<?php

class OutrasFormacoes
{
    private $id;
    private $idusuario;
    private $inicio;
    private $fim;
    private $descricao;
    
  
    //ID
    public function setID($id)
    {
        $this->id = $id;
    }
    public function getID()
    {
        return $this->id;
    }
    //idusuario
    public function setIdUsuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function getIdUsuario()
    {
        return $this->idusuario;
    }

    //inicio
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }
       public function getInicio()
    {
        return $this->inicio;
    }

    //fim
    public function setFim($fim)
    {
        $this->fim = $fim;
    }
    public function getFim()
    {
        return $this->fim;
    }

    //Data de nascimento
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
    public function getDescricao()
    {
        return $this->descricao;
    }

    //Métodos Banco de Dados
    public function inserirBD()
    {
        require_once __DIR__ . '/ConexaoBD.php';
        
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $stmt = $conn->prepare(
            "INSERT INTO outrasformacoes (idusuario, inicio, fim, descricao) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("isss", $this->idusuario, $this->inicio, $this->fim, $this->descricao);
        $stmt->execute();
        $this->id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return true;
    }

    public function excluirBD($id)
    {
        require_once __DIR__ . '/ConexaoBD.php';
        
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $stmt = $conn->prepare(
            "DELETE FROM outrasformacoes WHERE idoutrasformacoes = ? AND idusuario = ?"
        );
        $stmt->bind_param("ii", $id, $this->idusuario);
        $stmt->execute();
        $excluido = $stmt->affected_rows === 1;
        $stmt->close();
        $conn->close();
        return $excluido;
    }

    public function listaFormacoes($idusuario)
    {
        require_once __DIR__ . '/ConexaoBD.php';
        
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $stmt = $conn->prepare(
            "SELECT idoutrasformacoes, inicio, fim, descricao FROM outrasformacoes WHERE idusuario = ? ORDER BY inicio DESC"
        );
        $stmt->bind_param("i", $idusuario);
        $stmt->execute();
        $re = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $re;
    }
}

?>