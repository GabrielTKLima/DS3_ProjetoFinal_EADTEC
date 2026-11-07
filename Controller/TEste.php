<?php

require_once __DIR__ . '/ProjectPaths.php';
    
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
		
        if(isset($_POST["btnLogin"]))
        {
            require_once __DIR__ . '/UsuarioController.php';
          
            $uController = new UsuarioController();
            
            if($uController->login($_POST['txtLogin'], $_POST['txtSenha']) &&
            !empty($_POST['txtLogin']) && !empty($_POST['txtSenha']) )
            {           
                include_once __DIR__ . '/../View/principal.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        }
        elseif(isset($_POST["btnPrimeiroAcesso"]) || isset($_POST["btnCadNRealizado"]))
        {
            include_once __DIR__ . '/../View/primeiroAcesso.php';
        }
        elseif(isset($_POST["btnCadastrar"]))
        {
            
            require_once __DIR__ . '/UsuarioController.php';
            $uController = new UsuarioController();
            
            if($uController->inserir($_POST["txtNome"], $_POST["txtCPF"], $_POST["txtEmail"], $_POST['txtSenha']))
            {           
                include_once __DIR__ . '/../View/cadastroRealizado.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        }
        elseif(isset($_POST["btnAtualizar"]))
        {
            require_once __DIR__ . '/UsuarioController.php';
          
            $uController = new UsuarioController();
            
            if($uController->atualizar($_POST["txtID"], $_POST["txtNome"], $_POST["txtCPF"], $_POST["txtEmail"],
             date('Y-m-d', strtotime($_POST['txtData']))))
            {           
                include_once __DIR__ . '/../View/atualizacaoRealizada.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        
        }elseif(isset($_POST["btnAddFormacao"]))
        {
            require_once __DIR__ . '/FormacaoAcadController.php';
            include_once __DIR__ . '/../Model/Usuario.php';
          
            $fController = new FormacaoAcadController();
            
            if($fController->inserir(date('Y-m-d', strtotime($_POST['txtInicioFA'])), date('Y-m-d', strtotime($_POST["txtFimFA"])), $_POST["txtDescFA"], unserialize($_SESSION['Usuario'])->getID()) != false)
            {           
                include_once __DIR__ . '/../View/informacaoInserida.php';
            }
            else
            {
                include_once __DIR__ . '/../View/operacaoNaoRealizada.php';
            }
        
        }
        elseif(isset($_POST["btnExcluirFA"]))
        {
            require_once __DIR__ . '/FormacaoAcadController.php';
            include_once __DIR__ . '/../Model/Usuario.php';
          
            $fController = new FormacaoAcadController();
            
            if($fController->remover($_POST['id']) == true)
            {           
                include_once __DIR__ . '/../View/informacaoExcluida.php';
            }
            else
            {
                include_once __DIR__ . '/../View/operacaoNaoRealizda.php';
            }
        
        }
        elseif(isset($_POST["btnAddEP"]))
        {
            require_once __DIR__ . '/ExperienciaProfissionalController.php';
            include_once __DIR__ . '/../Model/Usuario.php';
          
            $epController = new ExperienciaProfissionalController();
            
            if($epController->inserir(date('Y-m-d', strtotime($_POST['txtInicioEP'])), date('Y-m-d', strtotime($_POST["txtFimEP"])), $_POST["txtEmpEP"], $_POST["txtDescEP"], unserialize($_SESSION['Usuario'])->getID()) != false)
            {           
                include_once __DIR__ . '/../View/cadastroRealizado.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        
        }
        elseif(isset($_POST["btnExcluirEP"]))
        {
            require_once __DIR__ . '/ExperienciaProfissionalController.php';
            include_once __DIR__ . '/../Model/Usuario.php';
          
            $epController = new ExperienciaProfissionalController();
            
            if($epController->remover($_POST['idEP']) == true)
            {           
                include_once __DIR__ . '/../View/cadastroRealizado.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        
        }
        elseif(isset($_POST["btnAddOF"]))
        {
            require_once __DIR__ . '/OutrasFormacoesController.php';
            include_once __DIR__ . '/../Model/Usuario.php';
          
            $fController = new OutrasFormacoesController();
            
            if($fController->inserir(date('Y-m-d', strtotime($_POST['txtInicioOF'])), date('Y-m-d', strtotime($_POST["txtFimOF"])), $_POST["txtDescOF"], unserialize($_SESSION['Usuario'])->getID()) != false)
            {           
                include_once __DIR__ . '/../View/cadastroRealizado.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        
        }
        elseif(isset($_POST["btnExcluirOF"]))
        {
            require_once __DIR__ . '/OutrasFormacoesController.php';
            include_once __DIR__ . '/../Model/Usuario.php';
          
            $fController = new OutrasFormacoesController();
            $usuario = isset($_SESSION['Usuario']) ? unserialize($_SESSION['Usuario']) : null;
            $idUsuario = $usuario instanceof Usuario ? $usuario->getID() : 0;
            $id = isset($_POST['id']) && is_string($_POST['id']) ? $_POST['id'] : '';

            if($fController->remover($id, $idUsuario) == true)
            {           
                include_once __DIR__ . '/../View/cadastroRealizado.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        
        }
        elseif(isset($_POST["btnPrincipal"]) || isset($_POST["btnAtualizacaoCadastro"]) || isset($_POST["btnCadRealizado"])
        || isset($_POST["btnInfInserir"]) || isset($_POST["btnInfExcluir"]))
        {
            include_once __DIR__ . '/../View/principal.php';
        }
        else
        {
            include_once __DIR__ . '/../View/login.php';
        }
?>