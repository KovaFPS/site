<?php
include("logica-usuario.php");
verificaUsuario();
include("conexao.php");

$mensagem = ""; // Variável para armazenar mensagens de erro ou sucesso
$produto = null; // Inicializando a variável produto como null

// Verificação de estoque antes do envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = isset($_POST["produto_id"]) ? $_POST["produto_id"] : '';
    $quantidade = isset($_POST["quantidade"]) ? $_POST["quantidade"] : '';
    $motivo = isset($_POST["motivo"]) ? $_POST["motivo"] : '';

    if (empty($produto_id) || empty($quantidade) || empty($motivo)) {
        $mensagem = "Todos os campos são obrigatórios!";
    } else {
        // Lógica para verificar o estoque
        $sql_estoque = "SELECT quantidade FROM produto WHERE id='$produto_id'";
        $result_estoque = mysqli_query($conexao, $sql_estoque);

        if ($result_estoque) {
            // Verificar se a consulta retornou algum resultado
            if (mysqli_num_rows($result_estoque) > 0) {
                $produto = mysqli_fetch_assoc($result_estoque);

                if (is_array($produto) && isset($produto["quantidade"])) {
                    if ($produto["quantidade"] >= $quantidade) {
                        // Se o estoque for suficiente, continuar com o processamento do formulário
                        $usuario_id = $_SESSION["usuario_logado"]["id"];

                        // Atualizar o estoque
                        $nova_quantidade = $produto["quantidade"] - $quantidade;
                        $sql_update_estoque = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
                        if (mysqli_query($conexao, $sql_update_estoque)) {
                            // Inserir registro de saída por uso
                            $sql = "INSERT INTO saida_uso (produto_id, quantidade, motivo, usuario_id) VALUES ('$produto_id', '$quantidade', '$motivo', '$usuario_id')";
                            if (mysqli_query($conexao, $sql)) {
                                $mensagem = "Registro de saída por uso realizado com sucesso!";
                            } else {
                                $mensagem = "Erro ao registrar saída por uso: " . mysqli_error($conexao);
                            }
                        } else {
                            $mensagem = "Erro ao atualizar o estoque: " . mysqli_error($conexao);
                        }
                    } else {
                        // Exibir a mensagem de erro com a quantidade atual do estoque
                        $mensagem = "Quantidade solicitada ($quantidade) indisponível em estoque (Atual: {$produto['quantidade']})!";
                    }
                } else {
                    $mensagem = "Produto não encontrado ou dados de produto inválidos!";
                }
            } else {
                $mensagem = "Produto não encontrado!";
            }
        } else {
            $mensagem = "Erro na consulta ao banco de dados: " . mysqli_error($conexao);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Saída por Uso</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>

<body>
    <?php include("header.php") ?>
    <div class="container-fluid">
        <div class="row mt-5">
            <!-- COLUNA 1 -->
            <div class="col-md-5" style="background: linear-gradient(to bottom, #EE82EE, #EE82EE, #DB7093); color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h3> Olá, seja bem vindo!</h3>
                    <h1> Registro de Saída por Uso</h1> <br>
                    <i class="fas fa-tools" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4 mr-2" value="Voltar" onclick="javascript: location.href='index.php';" style="border-radius: 25px;"> Voltar</button>
                </div>
            </div>
            <!-- COLUNA 2 -->
            <div class="col-md-7 p-5">
                <h2 class="text-center" style="color: #EE82EE;"> <i class="far fa-copy"></i> Registrar Uso</h2>
                <hr class="mb-5">
                <!-- Ele envia os dados para tela de cadastro -->
                <form id="form_registro_uso" method="post">
                    <!-- Linha do Produto -->
                    <div class="form-row">
                        <div class="col-md-7 mb-3">
                            <label for="produto_id"> Produto</label>
                            <select class="form-control" name="produto_id" id="produto_id" required>
                                <?php
                                $sql = "SELECT id, nome_produto FROM produto";
                                $result = mysqli_query($conexao, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['nome_produto']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Linha da Quantidade -->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="quantidade"> Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" id="quantidade" aria-describedby="quantidade" placeholder="Quantidade" required>
                            <span id="mensagem_estoque" class="text-danger"><?php echo $mensagem; ?></span>
                            <?php if ($mensagem && $produto) { ?>
                                <span class="text-muted">Estoque atual: <?php echo $produto['quantidade']; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Linha do Motivo -->
                    <div class="form-row">
                        <div class="col-md-7 mb-3">
                            <label for="motivo"> Motivo</label>
                            <input type="text" class="form-control" name="motivo" id="motivo" aria-describedby="motivo" placeholder="Motivo" required>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="btnca" class="btn m-4" style="background-color: #EE82EE; color:white; border-radius: 25px;" value="cadastrar">Registrar</button>
                    </div>

                    <!-- Exibição de mensagens de erro -->
                    <?php
                    if ($mensagem) {
                        echo "<div class='alert alert-danger text-center' role='alert'>$mensagem</div>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
