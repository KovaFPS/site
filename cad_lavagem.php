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
