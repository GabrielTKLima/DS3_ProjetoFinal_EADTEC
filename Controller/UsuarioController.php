<?php

if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class UsuarioController{
    
    
    public function inserir($nome, $cpf, $email,$senha) {
        require_once __DIR__ . '/../Model/Usuario.php';
        $usuario = new Usuario();
        $usuario->setNome($nome);
        $usuario->setCPF($cpf);
        $usuario->setEmail($email); 
        $usuario->setSenha($senha);    
        $r = $usuario->inserirBD();
        if ($r) {
            session_regenerate_id(true);
            $_SESSION['Usuario'] = serialize($usuario);
        }
        return $r;     
    }

    public function atualizar($id, $nome, $cpf, $email, $dataNascimento) {
        require_once __DIR__ . '/../Model/Usuario.php';
        $usuario = new Usuario();
        $usuario->setId($id);
        $usuario->setNome($nome);
        $usuario->setCPF($cpf);
        $usuario->setEmail($email);
        $usuario->setDataNascimento($dataNascimento);
        $r = $usuario->atualizarBD();
        $_SESSION['Usuario'] = serialize($usuario);
        return $r;
    }
    public function login($cpf, $senha)
    {
        if (!is_string($cpf) || trim($cpf) === '' ||
            !is_string($senha) || $senha === '') {
            return false;
        }

        require_once __DIR__ . '/../Model/Usuario.php';
        $usuario = new Usuario();
        if ($usuario->carregarUsuario(trim($cpf)) &&
            $usuario->getId() !== null &&
            hash_equals((string)$usuario->getSenha(), $senha)) {
            session_regenerate_id(true);
            $_SESSION['Usuario'] = serialize($usuario);
            return true;
        }

        return false;
    }
}

?>