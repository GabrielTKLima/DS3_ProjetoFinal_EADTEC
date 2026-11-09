
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Usuário</title>
</head>
<body>
<?php
    include_once '../Model/Usuario.php';
    include_once '../Controller/UsuarioController.php';
    include_once '../Controller/ExperienciaProfissionalController.php';
    include_once '../Controller/OutrasFormacoesController.php';
    include_once '../Controller/FormacaoAcadController.php';
    include_once '../Controller/ExperienciaProfissionalController.php';
    include_once '../Controller/OutrasFormacoesController.php';
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    $u = new Usuario();
    $u->carregarUsuarioID($_SESSION['IDUsuario']);

?> 
    <!--Incial -->
    <header class="w3-container w3-padding-32 w3-center ">
        <h1 class="w3-text-white w3-panel w3-cyan w3-round-large">
           <?php echo $u->getNome();?> Curriculo 
        </h1>
    </header>
    <div class="w3-padding-128 w3-content w3-text-grey">
        <div class="w3-container">
            <h2 class="w3-text-white w3-panel w3-cyan w3-round-large">
                NOME: <?php echo $u->getNome();?>  
            </h2>
            <h2 class="w3-text-white w3-panel w3-cyan w3-round-large">
            CPF: <?php echo $u->getCPF();?>  
            </h2>
            <h2 class="w3-text-white w3-panel w3-cyan w3-round-large">
            EMAIL: <?php echo $u->getEmail();?>  
            </h2>
            <h2 class="w3-text-white w3-panel w3-cyan w3-round-large">
            DATA DE NASCIMENTO: <?php echo $u->getDataNascimento();?>  
            </h2>
        </div>
    </div>
    <br>
    <br>
    <h1 class="  w3-text-cyan w3-round-large w3-center">
           Formação Acadêmica
    </h1>
    <div class="w3-padding-128 w3-content w3-text-grey">
        <div class="w3-container">
            <table class="w3-table-all w3-centered">
            <thead>   
                <tr class="w3-center w3-blue">
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Descrição</th>
                </tr>
            <thead>
                <?php
                    $fCon = new FormacaoAcadController();
                    $results = $fCon->gerarLista($_SESSION['IDUsuario']);
                    if($results != null)
                    while($row = $results->fetch_object()) {
                        echo '<tr>';
                       
                        echo '<td style="width: 10%;">'.$row->inicio.'</td>';
                        echo '<td style="width: 10%;">'.$row->fim.'</td>';
                        echo '<td style="width: 65%;">'.$row->descricao.'</td>';
                        echo '</tr>';
                    }  
                ?>
            </table>
        </div>
    </div>
    <br>
    <br>
    <h1 class="  w3-text-cyan w3-round-large w3-center">
           Experiência Profissional
    </h1>
    <div class="w3-padding-128 w3-content w3-text-grey">                
        <div class="w3-container">
            <table class="w3-table-all w3-centered">
            <thead>   
                <tr class="w3-center w3-blue">
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Empresa</th>
                    <th>Descrição</th>
                </tr>
            <thead>
                <?php
                    $ePro = new ExperienciaProfissionalController();
                    $results = $ePro->gerarLista($_SESSION['IDUsuario']);
                    if($results != null)
                    
                    while($row = $results->fetch_object()) {
                        echo '<tr>';
                        echo '<td style="width: 10%;">'.$row->inicio.'</td>';
                        echo '<td style="width: 10%;">'.$row->fim.'</td>';
                        echo '<td style="width: 10%;">'.$row->empresa.'</td>';
                        echo '<td style="width: 65%;">'.$row->descricao.'</td>';
                        echo '</tr>';
                    }  
                ?>
            </table>
        </div>
    </div>
    <br>
    <br>
    <div class="w3-padding-128 w3-content w3-text-grey">  
         <div class="w3-container">
            <table class="w3-table-all w3-centered">
            <thead>   
                <tr class="w3-center w3-blue">
                    
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Descrição</th>
                </tr>
            <thead>
                
                <?php
                    $fCon = new OutrasFormacoesController();
                    $results = $fCon->gerarLista($_SESSION['IDUsuario']);
                    if($results != null)
                    
                    while($row = $results->fetch_object()) {
                        echo '<tr>';
                       
                        echo '<td style="width: 10%;">'.$row->inicio.'</td>';
                        echo '<td style="width: 10%;">'.$row->fim.'</td>';
                        echo '<td style="width: 65%;">'.$row->descricao.'</td>';
                        echo '</tr>';
                    }  
                ?>
            </table>
        </div> 
    </div>         
    <br>
    <br>
<div class="w3-padding-128 w3-content w3-text-grey">
	<form action="/Controller/navegacao.php" method="post" class="w3-container w3-light-grey w3-text-blue w3-margin w3-center" style="width: 30%;">
		<div class="w3-row w3-section">
			<div>
				<button name="btnVoltarLista" class="w3-button w3-block w3-margin w3-blue w3-cell w3-round-large" style="width: 90%;">
					Voltar
				</button>
			</div>
		</div>
	</form>
</div>

	
</body>
</html>