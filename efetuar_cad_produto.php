<?php
include("logica-usuario.php");

// VERIFICA SE FOI LOGADO
verificaUsuario();

include_once('conexao.php'); // Certifique-se de que a conexão está sendo incluída

// Captura os dados do formulário e verifica se as variáveis estão definidas
$nome_produto = isset($_POST['txtnome_produto']) ? $_POST['txtnome_produto'] : '';
$valor_mercado = isset($_POST['txtvalor_mercado']) ? $_POST['txtvalor_mercado'] : '';
$valor_custo_medio = isset($_POST['txtvalor_custo_medio']) ? $_POST['txtvalor_custo_medio'] : '';
$quantidade = isset($_POST['txtquantidade']) ? $_POST['txtquantidade'] : 0; // Adicionado para evitar erro
$onde_pega = isset($_POST['txtonde_pega']) ? $_POST['txtonde_pega'] : '';

// Verificar se um arquivo foi enviado
if (isset($_FILES['txtimagem']) && $_FILES['txtimagem']['error'] === UPLOAD_ERR_OK) {
  // Diretório para onde o arquivo será movido
  $uploadDir = 'assets/img/produto/';
  // Nome original do arquivo
  $fileName = $_FILES['txtimagem']['name'];
  // Caminho completo do arquivo
  $uploadFile = $uploadDir . basename($fileName);

  // Movendo o arquivo para o diretório desejado
  if (move_uploaded_file($_FILES['txtimagem']['tmp_name'], $uploadFile)) {
      echo "Arquivo válido e enviado com sucesso.\n";
  } else {
      echo "Possível ataque de upload de arquivo!\n";
  }
} else {
  echo "Nenhum arquivo enviado ou ocorreu um erro no upload.\n";
}

// Validar os dados recebidos antes de inserir no banco de dados
if (!empty($nome_produto) && !empty($valor_mercado) && !empty($valor_custo_medio)) {
    // Prepara a query de inserção
    $sqlinsert = "INSERT INTO produto (nome_produto, valor_mercado, valor_custo_medio, quantidade, onde_pega, imagem) 
                  VALUES ('$nome_produto', '$valor_mercado', '$valor_custo_medio', '$quantidade', '$onde_pega', '$fileName')";

    // Executa a query
    $resultado = mysqli_query($conexao, $sqlinsert);

    // Verifica se a inserção foi bem sucedida
    if (!$resultado) {
        echo '<input type="button" onclick="window.location=\'index.php\';" value="voltar"><br><br>';
        die('<b> Query Inválida: </b>' . mysqli_error($conexao));
    } else {
        echo "Registro inserido com sucesso!";
        // Feche a conexão apenas após o sucesso da inserção
        mysqli_close($conexao);
    }
} else {
    echo "Por favor, preencha todos os campos obrigatórios.";
}

?>

<!--Fecha o arquivo php, pois está sendo utilizado outras instruções-->

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
                if (isset($resultado)) {
                    include("header.php");
                    echo '<div class="alert alert-success" role="alert">
                        <div class="p-5">
                            <i class="fas fa-grin-hearts h1"> </i> 
                            <p> Parabéns! Registro Cadastrado com Sucesso.</p>
                        </div>
                    </div>

                    <a href="cad_produto.php" class="btn mx-4 mt-5" style="background-color: #EE82EE; color:white; border-radius: 25px;">Criar novo Cadastro</a>
                    <a href="lista_produto.php" class="btn mt-5" style="background-color: #EE82EE; color:white; border-radius: 25px;">Ver Cadastrados</a>';
                }

                ?>
            </div>
        </div>
    </div>

</body>

</html>
