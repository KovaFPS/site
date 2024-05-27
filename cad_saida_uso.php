<?php
include("conexao.php");
include("logica-usuario.php");

// Inicializa a variável $mensagem
$mensagem = "";

// Verifica se o usuário está logado
if (!usuarioEstaLogado()) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit; // Certifique-se de sair do script após redirecionar
}

// Continua apenas se o método de requisição for POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = (int)$_POST['produto_id'];
    $quantidade = (int)$_POST['quantidade'];
    $motivo = mysqli_real_escape_string($conexao, $_POST['motivo']);
    
    // Usar a função para obter o ID do usuário logado
    $usuario_id = obterIdUsuarioLogado();

    // Verificação de estoque
    $sql_estoque = "SELECT quantidade FROM produto WHERE id='$produto_id'";
    $result_estoque = mysqli_query($conexao, $sql_estoque);
    
    // Inicializa $produto como um array vazio
    $produto = [];

    if ($result_estoque && mysqli_num_rows($result_estoque) > 0) {
        $produto = mysqli_fetch_assoc($result_estoque);

        if ($produto['quantidade'] >= $quantidade) {
            // Atualizar estoque
            $nova_quantidade = $produto['quantidade'] - $quantidade;
            $sql_update = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
            mysqli_query($conexao, $sql_update);

            // Registrar saída por uso
            $sql = "INSERT INTO saida_uso (produto_id, quantidade, motivo, usuario_id) VALUES ('$produto_id', '$quantidade', '$motivo', '$usuario_id')";
            if (mysqli_query($conexao, $sql)) {
                // Definir a mensagem de sucesso
                $mensagem = "Registro de saída por uso realizado com sucesso!";
            } else {
                // Definir a mensagem de erro
                $mensagem = "Erro ao registrar saída por uso: " . mysqli_error($conexao);
            }
        } else {
            // Definir a mensagem de erro
            $mensagem = "Quantidade solicitada indisponível em estoque.";
        }
    } else {
        // Definir a mensagem de erro
        $mensagem = "Produto não encontrado no estoque.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Saída por Uso</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>
<body>
    <?php include("header.php") ?>
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-5" style="background: linear-gradient(to bottom, #EE82EE, #EE82EE, #DB7093); color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h3> Olá, seja bem vindo!</h3>
                    <h1> Registro de Saída por Uso</h1> <br>
                    <i class="fas fa-tools" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4 mr-2" onclick="javascript: location.href='index.php';" style="border-radius: 25px;"> Voltar</button>
                </div>
            </div>
            <div class="col-md-7 p-5">
                <h2 class="text-center" style="color: #EE82EE;"> <i class="far fa-copy"></i> Registrar Uso</h2>
                <hr class="mb-5">
                <form id="form_registro_uso" method="post">
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
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="quantidade"> Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" id="quantidade" placeholder="Quantidade" required>
                            <span id="mensagem_estoque" class="text-danger"><?php echo $mensagem; ?></span>
                            <?php if ($mensagem && $produto) { ?>
                                <span class="text-muted">Estoque atual: <?php echo $produto['quantidade']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-7 mb-3">
                            <label for="motivo"> Motivo</label>
                            <input type="text" class="form-control" name="motivo" id="motivo" placeholder="Motivo" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn m-4" style="background-color: #EE82EE; color:white; border-radius: 25px;">Registrar</button>
                    </div>
                    <!-- Exibição da mensagem de erro ou sucesso -->
                    <?php if ($mensagem): ?>
                        <div class="alert <?php echo ($mensagem == "Registro de saída por uso realizado com sucesso!") ? 'alert-success' : 'alert-danger'; ?> text-center" role="alert">
                            <?php echo $mensagem; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>