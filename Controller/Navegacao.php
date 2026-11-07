<?php

require_once __DIR__ . '/ProjectPaths.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['pagina']) && $_GET['pagina'] === 'principal' &&
    $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../Model/Usuario.php';
    $usuarioSessao = isset($_SESSION['Usuario'])
        ? unserialize($_SESSION['Usuario'])
        : null;
    $idUsuarioSessao = $usuarioSessao instanceof Usuario
        ? filter_var($usuarioSessao->getId(), FILTER_VALIDATE_INT)
        : false;

    if ($idUsuarioSessao === false || $idUsuarioSessao <= 0) {
        unset($_SESSION['Usuario']);
        header('Location: ' . projectUrl('index.php'));
        exit;
    }

    include_once __DIR__ . '/../View/principal.php';
    exit;
}

    if(isset($_POST["btnPrimeiroAcesso"]))
    {
        include_once __DIR__ . '/../View/primeiroAcesso.php';
    }
    else{
        if(isset($_POST["btnLogin"]))
        {
            require_once __DIR__ . '/UsuarioController.php';
        
            $uController = new UsuarioController();
            
            if($uController->login($_POST['txtLogin'], $_POST['txtSenha']))
            {           
                include_once __DIR__ . '/../View/principal.php';
            }
            else
            {
                include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
            }
        }
        else{
            if(isset($_POST["btnCadastrar"]))
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
            else{

                if(isset($_POST["btnCadRealizado"]))
                {
                    include_once __DIR__ . '/../View/principal.php';
                }
                else{
                    if(isset($_POST["btnCadNRealizado"]))
                    {
                        include_once __DIR__ . '/../View/primeiroAcesso.php';
                    }
                    else
                    {       
                        if(isset($_POST["btnAtualizar"]))
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
                                include_once __DIR__ . '/../View/operacaoNaoRealizada.php';
                            }
                        
                        }
                        else
                        {
                            if(isset($_POST["btnAddFormacao"]))
                            {
                                require_once __DIR__ . '/FormacaoAcadController.php';
                                include_once __DIR__ . '/../Model/Usuario.php';
                            
                                $fController = new FormacaoAcadController();
                                
                                if($fController->inserir(date('Y-m-d', strtotime($_POST['txtInicioFA'])), date('Y-m-d', strtotime($_POST["txtFimFA"])), $_POST["txtDescFA"], unserialize($_SESSION['Usuario'])->getID()) != false)
                                {           
                                    include_once __DIR__ . '/../View/cadastroRealizado.php';
                                }
                                else
                                {
                                    include_once __DIR__ . '/../View/cadastroNaoRealizado.php';
                                }
                            
                            }
                            else{
                                    if(isset($_POST["btnExcluirFA"]))
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
                                    else{
                                        if(isset($_POST["btnAddEP"]))
                                        {
                                            require_once __DIR__ . '/ExperienciaProfissionalController.php';
                                            include_once __DIR__ . '/../Model/Usuario.php';
                                        
                                            $epController = new ExperienciaProfissionalController();
                                            
                                            if($epController->inserir(date('Y-m-d', strtotime($_POST['txtInicioEP'])), date('Y-m-d', strtotime($_POST["txtFimEP"])), $_POST["txtEmpEP"], $_POST["txtDescEP"], unserialize($_SESSION['Usuario'])->getID()) != false)
                                            {           
                                                include_once __DIR__ . '/../View/informacaoInserida.php';
                                            }
                                            else
                                            {
                                                include_once __DIR__ . '/../View/operacaoNRealizada.php';
                                            }
                                        
                                        }
                                        else
                                        {
                                            if(isset($_POST["btnExcluirEP"]))
                                            {
                                                require_once __DIR__ . '/ExperienciaProfissionalController.php';
                                                include_once __DIR__ . '/../Model/Usuario.php';
                                            
                                                $epController = new ExperienciaProfissionalController();
                                                
                                                if($epController->remover($_POST['idEP']) == true)
                                                {           
                                                    include_once __DIR__ . '/../View/informacaoExcluida.php';
                                                }
                                                else
                                                {
                                                    include_once __DIR__ . '/../View/operacaoNRealizada.php';
                                                }
                                            
                                            }
                                            else{
                                                if(isset($_POST["btnAddOF"]))
                                                {
                                                    require_once __DIR__ . '/OutrasFormacoesController.php';
                                                    include_once __DIR__ . '/../Model/Usuario.php';
                                                
                                                    $fController = new OutrasFormacoesController();
                                                    $usuario = unserialize($_SESSION['Usuario']);
                                                    $dados = array(
                                                        'inicio' => isset($_POST['txtInicioOF']) && is_string($_POST['txtInicioOF']) ? $_POST['txtInicioOF'] : '',
                                                        'fim' => isset($_POST['txtFimOF']) && is_string($_POST['txtFimOF']) ? $_POST['txtFimOF'] : '',
                                                        'descricao' => isset($_POST['txtDescOF']) && is_string($_POST['txtDescOF']) ? $_POST['txtDescOF'] : ''
                                                    );

                                                    if($fController->inserir($dados['inicio'], $dados['fim'], $dados['descricao'], $usuario->getID()))
                                                    {
                                                        $_SESSION['mensagemOF'] = array(
                                                            'tipo' => 'sucesso',
                                                            'mensagens' => array('Formação cadastrada com sucesso.')
                                                        );
                                                    }
                                                    else
                                                    {
                                                        $_SESSION['mensagemOF'] = array(
                                                            'tipo' => 'erro',
                                                            'mensagens' => $fController->getMensagens(),
                                                            'dados' => $dados
                                                        );
                                                    }

                                                    header('Location: ' . projectUrl('Controller/navegacao.php?pagina=principal#oFormacao'));
                                                    exit;
                                                
                                                }
                                                else{
                                                    if(isset($_POST["btnExcluirOF"]))
                                                    {
                                                        require_once __DIR__ . '/OutrasFormacoesController.php';
                                                        require_once __DIR__ . '/../Model/Usuario.php';
                                                    
                                                        $fController = new OutrasFormacoesController();
                                                        $usuario = isset($_SESSION['Usuario']) ? unserialize($_SESSION['Usuario']) : null;
                                                        $id = isset($_POST['id']) && is_string($_POST['id']) ? $_POST['id'] : '';
                                                        $idUsuario = $usuario instanceof Usuario ? $usuario->getID() : 0;

                                                        if ($fController->remover($id, $idUsuario)) {
                                                            $_SESSION['mensagemOF'] = array(
                                                                'tipo' => 'sucesso',
                                                                'mensagens' => array('Formação excluída com sucesso.')
                                                            );
                                                        } else {
                                                            $_SESSION['mensagemOF'] = array(
                                                                'tipo' => 'erro',
                                                                'mensagens' => $fController->getMensagens()
                                                                    ? $fController->getMensagens()
                                                                    : array('Não foi possível excluir a formação selecionada.')
                                                            );
                                                        }

                                                        header('Location: ' . projectUrl('Controller/navegacao.php?pagina=principal#oFormacao'));
                                                        exit;
                                                    }
                                                    else{
                                                        if(isset($_POST["btnPrincipal"]) || isset($_POST["btnAtualizacaoCadastro"]) || isset($_POST["btnCadRealizado"])
                                                        || isset($_POST["btnInfInserir"]) || isset($_POST["btnInfExcluir"]))
                                                        {
                                                            include_once __DIR__ . '/../View/principal.php';
                                                        }
                                                        else
                                                        {
                                                            include_once __DIR__ . '/../View/login.php';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
?>