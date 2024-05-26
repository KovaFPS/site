<?php
include("logica-usuario.php");
verificaUsuario();
include("conexao.php");

$mensagem = ""; // Variável para armazenar mensagens de erro ou sucesso

// Verificação de estoque antes do envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST["produto_id"];
    $quantidade = $_POST["quantidade"];

    // Lógica para verificar o estoque (substitua com sua implementação real)
    $sql_estoque = "SELECT quantidade FROM produto WHERE id='$produto_id'";
    $result_estoque = mysqli_query($conexao, $sql_estoque);
    $produto = mysqli_fetch_assoc($result_estoque);

    if ($produto) {
        if ($produto["quantidade"] >= $quantidade) {
            // Se o estoque for suficiente, continuar com o processamento do formulário
            $cliente_id = $_POST["cliente_id"];
            $valor_unitario = $_POST["valor_unitario"];

            // Atualizar o estoque
            $nova_quantidade = $produto["quantidade"] - $quantidade;
            $sql_update_estoque = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
            mysqli_query($conexao, $sql_update_estoque);

            // Inserir registro de saída por venda
            $sql = "INSERT INTO saida_venda (cliente_id, produto_id, quantidade, valor_unitario) VALUES ('$cliente_id', '$produto_id', '$quantidade', '$valor_unitario')";
            if (mysqli_query($conexao, $sql)) {
                $mensagem = "Registro de saída por venda realizado com sucesso!";
            } else {
                $mensagem = "Erro ao registrar saída por venda: " . mysqli_error($conexao);
            }
        } else {
            // Exibir a mensagem de erro com a quantidade atual do estoque
            $mensagem = "Quantidade solicitada ($quantidade) indisponível em estoque (Atual: {$produto['quantidade']})!";
        }
    } else {
        $mensagem = "Produto não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Saída por Venda</title>
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
                    <h1> Registro de Saída por Venda</h1> <br>
                    <i class="fas fa-shopping-cart" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4 mr-2" value="Voltar" onclick="javascript: location.href='index.php';" style="border-radius: 25px;"> Voltar</button>
                    <!-- redireciona para a tela indicada -->
                </div>
            </div>
                        <!-- COLUNA 2 -->
                        <div class="col-md-7 p-5 ">
                <h2 class="text-center" style="color: #EE82EE;"> <i class="far fa-copy"></i> Registrar Venda</h2>
                <hr class="mb-5">
                <!-- Ele envia os dados para tela de cadastro-->
                <form id="form_registro_venda" method="post">
                    <!-- 1° linha -->
                    <div class="form-row">
                        <div class="col-md-7 mb-3">
                            <label for="cliente_id"> Cliente</label>
                            <select class="form-control" name="cliente_id" id="cliente_id" required>
                                <?php
                                $sql = "SELECT id, nome FROM cliente";
                                $result = mysqli_query($conexao, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- 2° linha -->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="produto_id"> Produto</label>
                            <select class="form-control" name="produto_id" id="produto_id" required>
                                <?php
                                $sql = "SELECT id, nome_produto, valor_mercado FROM produto";
                                $result = mysqli_query($conexao, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}' data-valor='{$row['valor_mercado']}'>{$row['nome_produto']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="quantidade"> Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" id="quantidade" aria-describedby="quantidade" placeholder="Quantidade" required>
                            <span id="mensagem_estoque" class="text-danger"><?php echo $mensagem; ?></span>
                            <?php if ($mensagem) { // Exibir a quantidade atual do estoque apenas se houver mensagem de erro ?>
                                <span class="text-muted">Estoque atual: <?php echo $produto['quantidade']; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- 3° linha -->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="valor_unitario"> Valor Unitário</label>
                            <input type="number" class="form-control" name="valor_unitario" id="valor_unitario" aria-describedby="valor_unitario" placeholder="Valor Unitário" required>
                        </div>
                    </div>

                    <!-- Mostrar valor total do pedido -->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="valor_total">Valor Total</label>
                            <span id="valor_total" class="form-control" readonly></span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="btnca" class="btn m-4" style="background-color: #EE82EE; color:white; border-radius: 25px;" value="cadastrar">Registrar</button>
                        <!-- Submeter via post para a pag efetuar cadastro.php -->
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

    <script>
        function verificarEstoque() {
            var produto_id = document.getElementById("produto_id").value;
            var quantidade = document.getElementById("quantidade").value;

            // Requisição AJAX para verificar o estoque em tempo real
            $.get("verificar_estoque.php", {
                produto_id: produto_id,
                quantidade: quantidade
            }, function(data) {
                var resposta = JSON.parse(data);
                if (!resposta.disponivel) {
                    document.getElementById("mensagem_estoque").innerHTML = "Quantidade indisponível no estoque!";
                } else {
                    document.getElementById("mensagem_estoque").innerHTML = "";
                    document.getElementById("form_registro_venda").submit(); // Enviar o formulário se a quantidade estiver disponível
                }
            });

            return false; // Evitar o envio do formulário antes da resposta da requisição AJAX
        }

        function calcularValorTotal() {
            var quantidade = parseInt(document.getElementById("quantidade").value);
            var valorUnitario = parseFloat(document.getElementById("valor_unitario").value);

            var valorTotal = quantidade * valorUnitario;

            // Formatando o valor total em dólares
            var valorTotalFormatado = valorTotal.toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD'
            });

            document.getElementById("valor_total").innerHTML = valorTotalFormatado;
        }

        // Chamando a função calcularValorTotal() quando houver alterações nos campos de quantidade ou valor unitário
        document.getElementById("quantidade").addEventListener("input", calcularValorTotal);
        document.getElementById("valor_unitario").addEventListener("input", calcularValorTotal);
    </script>
</body>

</html>
