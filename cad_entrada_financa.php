<?php
include("logica-usuario.php");
verificaUsuario();
include("conexao.php");

$mensagem = "";
$valorLavado = 0;
$valorRetirado = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST["tipo"];
    $valor = $_POST["valor"];
    $descricao = $_POST["descricao"];
    $usuario_id = $_SESSION['usuario_id'];

    if ($tipo === 'Lavagem de dinheiro') {
        $porcentagem = $_POST["porcentagem"];
        $valorLavado = $valor * (1 - ($porcentagem / 100));
        $valorRetirado = $valor - $valorLavado;
        $valor = $valorLavado;
    }

    $sql = "INSERT INTO entrada_financa (tipo, valor, descricao, usuario_id) VALUES ('$tipo', '$valor', '$descricao', '$usuario_id')";
    if (mysqli_query($conexao, $sql)) {
        $mensagem = "Entrada financeira registrada com sucesso!";
    } else {
        $mensagem = "Erro ao registrar entrada financeira: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Lavagem de Dinheiro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
    <?php include("header.php") ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Cadastro de Lavagem de Dinheiro</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="lavagemForm" method="post">
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor a ser Lavado (em dólar)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" id="valor" name="valor" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="porcentagem">Porcentagem de Retenção</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="porcentagem" name="porcentagem" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar Lavagem</button>
                </form>
                <div id="mensagem" class="alert mt-3" role="alert" style="display: none;"></div>
                <div id="valorLavadoInfo" class="mt-3" style="display: none;">
                    <p><strong>Resultado:</strong></p>
                    <p>Valor a ser Entregue ao Cliente Após Lavagem: <span id="valorLavado"></span></p>
                    <p>Valor Retirado para Entrada de Dinheiro: <span id="valorRetido"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Função para calcular e exibir os valores em tempo real
            function calcularValores() {
                var valor = parseFloat($('#valor').val().replace(',', '').replace('$', ''));
                var porcentagem = parseFloat($('#porcentagem').val().replace('%', ''));

                var valorRetido = valor * (porcentagem / 100);
                var valorLavado = valor - valorRetido;

                $('#valorLavado').text('$' + formatarNumero(valorLavado));
                $('#valorRetido').text('$' + formatarNumero(valorRetido));
                $('#valorLavadoInfo').show();
            }

            // Quando o valor for digitado, calcular e exibir os valores em tempo real
            $('#valor, #porcentagem').on('input', function() {
                calcularValores();
            });

            // Função para formatar o número
            function formatarNumero(numero) {
                return numero.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Quando o formulário for enviado
            $('#lavagemForm').submit(function(event) {
                event.preventDefault(); // Impedir o envio do formulário padrão
                var formData = $(this).serialize(); // Obter os dados do formulário

                // Enviar os dados do formulário para o script PHP usando AJAX
                $.ajax({
                    type: 'POST',
                    url: 'cad_lavagem.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $('#mensagem').text(response.mensagem).addClass('alert-info').show(); // Exibir a mensagem de sucesso
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Exibir mensagens de erro no console
                    }
                });
            });
        });
    </script>
</body>

</html>

