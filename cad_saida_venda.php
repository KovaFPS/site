<?php
include("logica-usuario.php");
verificaUsuario();
include("conexao.php");

$mensagem = ""; // Variável para armazenar mensagens de erro ou sucesso

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST["produto_id"];
    $quantidade = $_POST["quantidade"];
    $cliente_id = $_POST["cliente_id"];
    $valor_unitario = $_POST["valor_unitario"];

    // Verificação de estoque
    $sql_estoque = "SELECT nome_produto, quantidade FROM produto WHERE id='$produto_id'";
    $result_estoque = mysqli_query($conexao, $sql_estoque);
    $produto = mysqli_fetch_assoc($result_estoque);

    if ($produto) {
        if ($produto["quantidade"] >= $quantidade) {
            $nova_quantidade = $produto["quantidade"] - $quantidade;
            $valor_total = $quantidade * $valor_unitario;
            $usuario_id = obterIdUsuarioLogado();
            $nome_produto = $produto['nome_produto'];

            // Obter o nome do cliente
            $sql_cliente = "SELECT nome FROM cliente WHERE id='$cliente_id'";
            $result_cliente = mysqli_query($conexao, $sql_cliente);
            $cliente = mysqli_fetch_assoc($result_cliente);
            $nome_cliente = $cliente['nome'];

            // Descrição da transação financeira
            $descricao_financa = "Venda de $quantidade x $nome_produto para $nome_cliente";

            // Atualizar o estoque
            $sql_update_estoque = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
            mysqli_query($conexao, $sql_update_estoque);

            // Inserir registro de saída por venda
            $sql_saida_venda = "INSERT INTO saida_venda (cliente_id, produto_id, quantidade, valor_unitario, usuario_id) VALUES ('$cliente_id', '$produto_id', '$quantidade', '$valor_unitario', '$usuario_id')";
            if (mysqli_query($conexao, $sql_saida_venda)) {
                // Inserir registro na entrada_financa
                $sql_entrada_financa = "INSERT INTO entrada_financa (tipo, valor, usuario_id, descricao) VALUES ('Venda de Produto/Item', '$valor_total', '$usuario_id', '$descricao_financa')";
                mysqli_query($conexao, $sql_entrada_financa);

                // Obter o nome do usuário
                $query_usuario = "SELECT nome FROM usuario WHERE id = '$usuario_id'";
                $resultado_usuario = mysqli_query($conexao, $query_usuario);
                $usuario = mysqli_fetch_assoc($resultado_usuario);
                $nome_usuario = $usuario ? $usuario['nome'] : 'Nome não encontrado';

                // Montar a mensagem para o Discord - Registro de Venda
                $mensagem_discord_venda = "```md\n";
                $mensagem_discord_venda .= "######  Registro de Venda  ######\n\n";
                $mensagem_discord_venda .= "## Produto:\n";
                $mensagem_discord_venda .= "- " . $nome_produto . "\n";
                $mensagem_discord_venda .= "## Nome do Cliente:\n";
                $mensagem_discord_venda .= "- " . $nome_cliente . "\n";
                $mensagem_discord_venda .= "## Quantidade Vendida:\n";
                $mensagem_discord_venda .= "- " . $quantidade . "\n";
                $mensagem_discord_venda .= "## Quantidade Anterior no Estoque:\n";
                $mensagem_discord_venda .= "- " . $produto['quantidade'] . "\n";
                $mensagem_discord_venda .= "## Quantidade Depois da Venda:\n";
                $mensagem_discord_venda .= "- " . $nova_quantidade . "\n";
                $mensagem_discord_venda .= "## Valor /Un do Produto:\n";
                $mensagem_discord_venda .= "- $ " . number_format($valor_unitario, 0, '.', '.') . " USD\n";
                $mensagem_discord_venda .= "## Valor Total da Venda:\n";
                $mensagem_discord_venda .= "- R$ " . number_format($valor_total, 0, '.', '.') . " USD\n";
                $mensagem_discord_venda .= "## Quem vendeu:\n";
                $mensagem_discord_venda .= "- " . $nome_usuario . "\n";
                $mensagem_discord_venda .= "## Data e Hora:\n";
                $mensagem_discord_venda .= "- " . date('d/m/Y H:i:s') . "\n";
                $mensagem_discord_venda .= "```";

                // Webhook do Discord para registro de venda
                $webhook_url_venda = 'https://discord.com/api/webhooks/1244999970046607532/33GawFPoeuJ-6yrkQX5_krlgLADMYZXcBwbC-czlYlh6BHfZVGyLJIm7k4p16pid9qGO';
                $data_discord_venda = json_encode(['content' => $mensagem_discord_venda]);

                $ch_venda = curl_init($webhook_url_venda);
                curl_setopt($ch_venda, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch_venda, CURLOPT_POST, 1);
                curl_setopt($ch_venda, CURLOPT_POSTFIELDS, $data_discord_venda);
                curl_setopt($ch_venda, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch_venda);
                curl_close($ch_venda);

                // Montar a mensagem para o Discord - Registro de Entrada de Dinheiro
                $mensagem_discord_entrada = "```md\n";
                $mensagem_discord_entrada .= "######  Registro de Entrada de Dinheiro  ######\n\n";
                $mensagem_discord_entrada .= "## Tipo:\n";
                $mensagem_discord_entrada .= "- Venda de Produto/Item\n";
                $mensagem_discord_entrada .= "## Descrição:\n";
                $mensagem_discord_entrada .= "- " . $descricao_financa . "\n";
                $mensagem_discord_entrada .= "## Valor da Venda:\n";
                $mensagem_discord_entrada .= "- $ " . number_format($valor_total, 0, '.', '.') . " USD\n";
                $mensagem_discord_entrada .= "## Quem vendeu:\n";
                $mensagem_discord_entrada .= "- " . $nome_usuario . "\n";
                $mensagem_discord_entrada .= "## Data e Hora:\n";
                $mensagem_discord_entrada .= "- " . date('d/m/Y H:i:s') . "\n";
                $mensagem_discord_entrada .= "```";

                // Webhook do Discord para registro de entrada de dinheiro
                $webhook_url_entrada = 'https://discord.com/api/webhooks/1244829573711925301/U4Y-SOK107N8ANfS96VF1qIOkYwWqNCwN_eTZKfcwrBmuHpED4mkOdubfUHWFHxCOHM_';
                $data_discord_entrada = json_encode(['content' => $mensagem_discord_entrada]);

                $ch_entrada = curl_init($webhook_url_entrada);
                curl_setopt($ch_entrada, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch_entrada, CURLOPT_POST, 1);
                curl_setopt($ch_entrada, CURLOPT_POSTFIELDS, $data_discord_entrada);
                curl_setopt($ch_entrada, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch_entrada);
                curl_close($ch_entrada);

                $mensagem = "Registro de saída por venda realizado com sucesso! Estoque atual: $nova_quantidade";
            } else {
                $mensagem = "Erro ao registrar saída por venda: " . mysqli_error($conexao);
            }
        } else {
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
            <div class="col-md-7 p-5">
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
                                <span class="text-muted">Estoque anterior: <?php echo $produto['quantidade']; ?></span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaI6xoAR1p8FN1RtM0rR8tjt+fJ59b116Y9+N" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+d3L9aijxdh58bDr7amv8AwtIAXUHsE3wYRsM1P6Vx9W8fwtBQ" crossorigin="anonymous"></script>
    <script>
        // Atualizar o valor unitário automaticamente ao selecionar o produto
        $('#produto_id').change(function() {
            var valor = $(this).find(':selected').data('valor');
            $('#valor_unitario').val(valor);
            atualizarValorTotal();
        });

        // Atualizar o valor total automaticamente ao alterar a quantidade ou o valor unitário
        $('#quantidade, #valor_unitario').on('input', function() {
            atualizarValorTotal();
        });

        function atualizarValorTotal() {
            var quantidade = parseFloat($('#quantidade').val());
            var valor_unitario = parseFloat($('#valor_unitario').val());
            var valor_total = quantidade * valor_unitario;
            if (!isNaN(valor_total)) {
                $('#valor_total').text(valor_total.toFixed(2));
            } else {
                $('#valor_total').text('');
            }
        }
    </script>
</body>
</html>
