<?php
class ConexaoBD{
    private $serverName = "localhost";
    private $userName = "root";
    private $password = "";
    private $bdName = "projeto_final";

    public function conectar(){
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conexao = new mysqli($this->serverName, $this->userName, $this->password, $this->bdName);
        $conexao->set_charset("utf8mb4");
        return $conexao;
    }
}
?>