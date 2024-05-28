<?php
include("logica-usuario.php");
verificaUsuario();
include("conexao.php");

$mensagem = "";
$valorLavado = 0;
$valorRetido = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST["descricao"];
    $valor = str_replace(['$', ','], '', $_POST["valor"]);
    $porcentagem = str_replace('%', '', $_POST["porcentagem"]);
    $usuario_id = obterIdUsuarioLogado();

    $valor = floatval($valor);
    $porcentagem = floatval($porcentagem);

    if (!is_numeric($valor) || !is_numeric($porcentagem)) {
        $mensagem = "Por favor, insira valores numéricos para o valor e a porcentagem.";
    } else {
        if ($porcentagem < 0 || $porcentagem > 100) {
            $mensagem = "A porcentagem deve estar entre 0 e 100.";
        } else {
            $valorRetido = $valor * ($porcentagem / 100);
            $valorLavado = $valor - $valorRetido;
            $tipo = "Lavagem de dinheiro";

            if ($usuario_id !== null) {
                $sql1 = "INSERT INTO lavagem_dinheiro (usuario_id, descricao, valor, porcentagem) VALUES ('$usuario_id', '$descricao', '$valor', '$porcentagem')";
                $resultado1 = mysqli_query($conexao, $sql1);

                if ($resultado1) {
                    $sql2 = "INSERT INTO entrada_financa (tipo, valor, descricao, usuario_id) VALUES ('$tipo', '$valorRetido', '$descricao', '$usuario_id')";
                    $resultado2 = mysqli_query($conexao, $sql2);

                    if ($resultado2) {
                        $mensagem = "Lavagem de dinheiro registrada com sucesso!";
                        
                        // Obter o nome do usuário
                        $query_usuario = "SELECT nome FROM usuario WHERE id = '$usuario_id'";
                        $resultado_usuario = mysqli_query($conexao, $query_usuario);
                        $usuario = mysqli_fetch_assoc($resultado_usuario);
                        $nome_usuario = $usuario ? $usuario['nome'] : 'Nome não encontrado';
                        
// Montar a mensagem para o Discord
$mensagem_discord_lavagem = "```md\n";
$mensagem_discord_lavagem .= "######  Registro de Lavagem de Dinheiro  ######\n\n";
$mensagem_discord_lavagem .= "## Descrição:\n";
$mensagem_discord_lavagem .= "- " . $descricao . "\n";
$mensagem_discord_lavagem .= "## Valor Lavado:\n";
$mensagem_discord_lavagem .= "- " . number_format($valor, 0, ',', '.') . " USD\n";
$mensagem_discord_lavagem .= "## Valor Entregue ao Cliente:\n";
$mensagem_discord_lavagem .= "- " . number_format($valorLavado, 0, '.', '.') . " USD\n";
$mensagem_discord_lavagem .= "## Valor Retido:\n";
$mensagem_discord_lavagem .= "- " . number_format($valorRetido, 0, '.', '.') . " USD\n";
$mensagem_discord_lavagem .= "## Porcentagem:\n";
$mensagem_discord_lavagem .= "- " . $porcentagem . "%\n";
$mensagem_discord_lavagem .= "## Quem Lavou:\n";
$mensagem_discord_lavagem .= "- " . $nome_usuario . "\n";
$mensagem_discord_lavagem .= "## Data e Hora:\n";
$mensagem_discord_lavagem .= "- " . date('d/m/Y H:i:s') . "\n";
$mensagem_discord_lavagem .= "```";

                        // Webhook do Discord para registro de lavagem de dinheiro
                        $webhook_url_lavagem = 'https://discord.com/api/webhooks/1244829363816370238/elNW_sUa314durKCI1QXGlA9ldkl-Af_GZ9Gmd44OGSTRNiV2wbGrN1NN7pKZ6ZdhJnB';

                        // Dados do webhook
                        $data_discord_lavagem = json_encode(['content' => $mensagem_discord_lavagem]);

                        // Configurações da requisição CURL
                        $ch_lavagem = curl_init($webhook_url_lavagem);
                        curl_setopt($ch_lavagem, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                        curl_setopt($ch_lavagem, CURLOPT_POST, 1);
                        curl_setopt($ch_lavagem, CURLOPT_POSTFIELDS, $data_discord_lavagem);
                        curl_setopt($ch_lavagem, CURLOPT_RETURNTRANSFER, true);

                        // Executar a requisição CURL
                        $response_lavagem = curl_exec($ch_lavagem);
                        $http_status_lavagem = curl_getinfo($ch_lavagem, CURLINFO_HTTP_CODE); // Obter o status HTTP da requisição
                        curl_close($ch_lavagem);

                        // Montar a mensagem para o Discord de entrada de dinheiro
                        $mensagem_discord_entrada = "```md\n";
                        $mensagem_discord_entrada .= "######  Registro de Entrada de Dinheiro  ######\n\n";
                        $mensagem_discord_entrada .= "## Tipo:\n";
                        $mensagem_discord_entrada .= "- " . $tipo . "\n";
                        $mensagem_discord_entrada .= "## Descrição:\n";
                        $mensagem_discord_entrada .= "- " . $descricao . "\n";
                        $mensagem_discord_entrada .= "## Valor de Lucro:\n";
                        $mensagem_discord_entrada .= "- " . number_format($valorRetido, 0, '.', '.') . " USD\n";
                        $mensagem_discord_entrada .= "## Quem deu a entrada:\n";
                        $mensagem_discord_entrada .= "- " . $nome_usuario . "\n";
                        $mensagem_discord_entrada .= "## Data e Hora:\n";
                        $mensagem_discord_entrada .= "- " . date('d/m/Y H:i:s') . "\n";
                        $mensagem_discord_entrada .= "```";

                        // Webhook do Discord para registro de entrada de dinheiro
                        $webhook_url_entrada = 'https://discord.com/api/webhooks/1244829573711925301/U4Y-SOK107N8ANfS96VF1qIOkYwWqNCwN_eTZKfcwrBmuHpED4mkOdubfUHWFHxCOHM_';

                        // Dados do webhook
                        $data_discord_entrada = json_encode(['content' => $mensagem_discord_entrada]);

                        // Configurações da requisição CURL
                        $ch_entrada = curl_init($webhook_url_entrada);
                        curl_setopt($ch_entrada, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                        curl_setopt($ch_entrada, CURLOPT_POST, 1);
                        curl_setopt($ch_entrada, CURLOPT_POSTFIELDS, $data_discord_entrada);
                        curl_setopt($ch_entrada, CURLOPT_RETURNTRANSFER, true);

                        // Executar a requisição CURL
                        $response_entrada = curl_exec($ch_entrada);
                        $http_status_entrada = curl_getinfo($ch_entrada, CURLINFO_HTTP_CODE); // Obter o status HTTP da requisição
                        curl_close($ch_entrada);
                    } else {
                        $mensagem = "Erro ao registrar entrada financeira.";
                    }
                } else {
                    $mensagem = "Erro ao registrar lavagem de dinheiro.";
                }
            } else {
                $mensagem = "ID de usuário não definido ou inválido.";
            }
        }
    }
    echo json_encode(array(
        'mensagem' => $mensagem,
        'valorLavado' => number_format($valorLavado, 2, '', ''),
        'valorRetido' => number_format($valorRetido, 2, '', '')
    ));

    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Lavagem de Dinheiro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous">
</head>
<body>
    <?php include("header.php") ?>
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-5 text-center" style="background: linear-gradient(to right, #58AF9C, #58AF9C, #2E8B57); color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h3> Olá, seja bem vindo!</h3>
                    <h1> Cadastro de Lavagem de Dinheiro</h1> <br>
                    <i class="fas fa-hand-holding-usd" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4" value="Voltar" onclick="javascript: location.href='index.php';" style="border-radius: 25px;">Voltar</button>
                </div>
            </div>
            <div class="col-md-7 p-5">
                <h2 class="text-center" style="color: #58AF9C;"> <i class="far fa-copy"></i> Cadastro de Lavagem de Dinheiro </h2>
                <hr class="mb-5">
                <form id="lavagemForm" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="valor">Valor a ser Lavado (em dólar)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="valor" name="valor" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">$</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="porcentagem">Porcentagem de Retenção</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="porcentagem" name="porcentagem" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn m-4" style="background-color: #58AF9C; color:white; border-radius: 25px;" value="Registrar">Registrar</button>
                    </div>
                </form>
                <div id="mensagem" class="alert mt-3" role="alert" style="display: none;"></div>
                <div id="valorLavadoInfo" class="mt-3" style="display: none;">
                    <h5 class="mb-3"><strong>Resultado:</strong></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave mr-2" style="font-size: 24px; color: #28a745;"></i>
                                <p class="mb-0">Valor a ser Entregue ao Cliente Após Lavagem:</p>
                            </div>
                            <h4 id="valorLavado" class="text-success font-weight-bold mt-2"></h4>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hand-holding-usd mr-2" style="font-size: 24px; color: #dc3545;"></i>
                                <p class="mb-0">Valor Retirado para Entrada de Dinheiro:</p>
                            </div>
                            <h4 id="valorRetido" class="text-danger font-weight-bold mt-2"></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function formatarNumero(numero) {
                if (!isNaN(numero) && isFinite(numero)) {
                    return numero.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                } else {
                    return '0,00';
                }
            }

            $('#porcentagem').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                $(this).val(value + '');
            });

            $('#lavagemForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'cad_lavagem.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#mensagem').text(response.mensagem).removeClass('alert-danger').addClass('alert-success').show();
                        $('#valorLavado').text('$' + formatarNumero(response.valorLavado));
                        $('#valorRetido').text('$' + formatarNumero(response.valorRetido));
                        $('#valorLavadoInfo').show();
                    },
                    error: function(xhr, status, error) {
                        $('#mensagem').text('Ocorreu um erro ao registrar a lavagem de dinheiro.').removeClass('alert-success').addClass('alert-danger').show();
                    }
                });
            });

            $('#valor, #porcentagem').on('input', function() {
                var valor = parseFloat($('#valor').val().replace(/[^0-9.]/g, ''));
                var porcentagem = parseFloat($('#porcentagem').val().replace(/\D/g, ''));

                var valorRetido = valor * (porcentagem / 100);
                var valorLavado = valor - valorRetido;

                $('#valorLavado').text('$' + formatarNumero(valorLavado));
                $('#valorRetido').text('$' + formatarNumero(valorRetido));
                $('#valorLavadoInfo').show();
            });
        });
    </script>
</body>
</html>
