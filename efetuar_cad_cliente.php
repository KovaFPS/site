<?php

include("conexao.php"); // Inclui o arquivo de conexão com o banco de dados

// Recebendo os dados do formulário
$nome = $_POST['txtnome'];
$state_id = $_POST['txtstate_id'];
$telefone = $_POST['txttelefone'];
$turf_organizacao_empresa = $_POST['txtturforganizacaoempresa'];
$limite_credito = $_POST['txtlimitecredito'];
$endereco = $_POST['txtendereco'];

// Remove qualquer caractere não numérico do campo de limite de crédito
$limite_credito = preg_replace('/[^0-9.]/', '', $limite_credito);

// Verifica se o StateID possui exatamente 4 dígitos
if (strlen($state_id) !== 4 || !ctype_digit($state_id)) {
    die('StateID inválido. Deve conter exatamente 4 dígitos numéricos.');
}

// Prepara a instrução SQL (utilizando prepared statements para segurança)
$sqlinsert = "INSERT INTO cliente (id, nome, state_id, telefone, turf, limite_credito, endereco) 
              VALUES (0, ?, ?, ?, ?, ?, ?)";

// Prepara e vincula os parâmetros
$stmt = $conexao->prepare($sqlinsert);
$stmt->bind_param("ssssds", $nome, $state_id, $telefone, $turf_organizacao_empresa, $limite_credito, $endereco);

// Executa a query
$resultado = $stmt->execute();

// Verifica se a query foi bem-sucedida
if (!$resultado) {
    die('Query Inválido: ' . $conexao->error);
}

// Fecha o statement
$stmt->close();

?>

<!-- HTML para exibir mensagem de sucesso ou erro -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>

<body>
    <?php include("header.php") ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center" style="margin-top: 100px">

                <?php

                // Verifica se a query foi bem-sucedida e exibe mensagem correspondente
                if (!$resultado) {
                    die('Query Inválido: ' . $conexao->error);
                } else {
                    include("header.php");
                    echo '<div class="alert alert-success" role="alert">
                            <div class="p-5">
                                <i class="fas fa-grin-hearts h1"> </i> 
                                <p> Parabéns! Registro Cadastrado com Sucesso.</p>
                            </div>
                        </div>

                        <a href="cad_cliente.php" class="btn mx-4 mt-5" style="background-color: #58AF9C; color:white; border-radius: 25px;">Criar novo Cadastro</a>
                        <a href="lista_cliente.php" class="btn mt-5" style="background-color: #58AF9C; color:white; border-radius: 25px;">Ver Cadastros</a>';
                }
                mysqli_close($conexao); // Fecha a conexão com o banco
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>

</html>
