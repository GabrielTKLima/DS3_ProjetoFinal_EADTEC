<?php
class ConexaoBD{
    private $serverName = "localhost";
    private $userName = "root";
    private $password = "";
    private $bdName = "projeto_final";
}

public function conectar(){
    try{
        $conexao = new PDO("mysql:host=$this->serverName;dbname=$this->bdName", $this->userName, $this->password);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    }catch(PDOException $e){
        echo "Erro na conexão: " . $e->getMessage();
        return null;
    }
}
?>