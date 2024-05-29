<?php
include("logica-usuario.php");

// VERIFICA SE FOI LOGADO
verificaUsuario();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>
<body>
    <?php
    include_once('conexao.php');
    // RECUPERAÇÃO
    $codigo = $_GET['codigo'];

    // CRIANDO A LINHA DO SELECT
    $sqlconsulta = "SELECT * FROM produto WHERE id = '$codigo'";

    // EXECUTANDO INSTRUÇÃO
    $resultado = @mysqli_query($conexao, $sqlconsulta);
    if (!$resultado) {
        echo '<input type="button" onclick="window.location=\'index.php\';" value="voltar"><br><br>';
        die('<b> Query Invalida: </b>' . @mysqli_error($conexao));
    } else {
        $num = @mysqli_num_rows($resultado);
        if ($num == 0) {
            echo "<b>Codigo: </b> Não Localizado!!! <br> <br>";
            echo '<input type="button" onclick="window.location=\'index.php\';" value="voltar"><br><br>';
            exit;
        } else {
            $dado = mysqli_fetch_array($resultado);
        }
    }
    mysqli_close($conexao);
    ?>
    <?php include("header.php") ?>

    <div class="container-fluid">
        <div class="row mt-5">
            <!-- COLUNA 1 -->
            <div class="col-md-5" style="background-color: #EE82EE; color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h1>Exclusão de Cadastro</h1>
                    <h1>Produto</h1> <br>
                    <i class="fas fa-dolly" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4 mr-2" value="Voltar" onclick="javascript: location.href='lista_produto.php';" style="border-radius: 25px;">Voltar</button>
                </div>
            </div>
            <!-- COLUNA 2 -->
            <div class="col-md-7 p-5">
                <h2 class="text-center" style="color: #EE82EE;"> <i class="far fa-copy"></i> Excluir Cadastro </h2>
                <hr class="mb-5">
                <form method="post" action="excluir_produto.php">
                    <!-- 1° linha -->
                    <div class="form-row mb-3">
                        <div class="form-group col-md-2">
                            <label for="exampleInputCodigo">Código</label>
                            <input type="text" class="form-control" name="txtid" value='<?php echo $dado['id']; ?>' readonly>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="exampleInputNomeProduto">Nome do Produto</label>
                            <input type="text" class="form-control" name="txtnome_produto" value='<?php echo $dado['nome_produto']; ?>' readonly>
                        </div>
                    </div>
                    <!-- 2° linha -->
                    <div class="form-row mb-3">
                        <div class="form-group col-md-4">
                            <label for="exampleInputValorMercado">Valor de Mercado</label>
                            <input type="text" class="form-control" name="txtvalor_mercado" value='<?php echo $dado['valor_mercado']; ?>' readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputValorCustoMedio">Valor de Custo Médio</label>
                            <input type="text" class="form-control" name="txtvalor_custo_medio" value='<?php echo $dado['valor_custo_medio']; ?>' readonly>
                        </div>
                    </div>
                    <!-- 3° linha -->
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <label for="exampleInputOndePega">Onde Pega</label>
                            <select class="form-control" name="txtonde_pega" readonly>
                                <option value="Farm Sanitation" <?php if ($dado['onde_pega'] == 'Farm Sanitation') echo 'selected'; ?>>Farm Sanitation</option>
                                <option value="Farm Madeira" <?php if ($dado['onde_pega'] == 'Farm Madeira') echo 'selected'; ?>>Farm Madeira</option>
                                <option value="Farm Prisão" <?php if ($dado['onde_pega'] == 'Farm Prisão') echo 'selected'; ?>>Farm Prisão</option>
                                <option value="Farm Plantação Weed" <?php if ($dado['onde_pega'] == 'Farm Plantação Weed') echo 'selected'; ?>>Farm Plantação Weed</option>
                                <option value="Compra" <?php if ($dado['onde_pega'] == 'Compra') echo 'selected'; ?>>Compra</option>
                                <option value="Blueprint" <?php if ($dado['onde_pega'] == 'Blueprint') echo 'selected'; ?>>Blueprint</option>
                                <option value="App Drone" <?php if ($dado['onde_pega'] == 'App Drone') echo 'selected'; ?>>App Drone</option>
                                <option value="Heist's" <?php if ($dado['onde_pega'] == 'Heist\'s') echo 'selected'; ?>>Heist's</option>
                            </select>
                        </div>
                    </div>
                    <!-- Botões -->
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-danger m-2" style="border-radius: 25px;"><i class="far fa-trash-alt"></i> Excluir</button>
                        <button type="button" class="btn btn-outline-secondary m-2" onclick="javascript: location.href='lista_produto.php';" style="border-radius: 25px;">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
