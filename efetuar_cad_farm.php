<?php
include("conexao.php");
include("logica-usuario.php");

verificaUsuario();

$usuario_id = $_POST['usuario_id'];
$produto_id = $_POST['produto_id'];
$quantidade = $_POST['quantidade'];

// Realizar o cadastro no banco de dados
$sql = "INSERT INTO farm (usuario_id, produto_id, quantidade) VALUES ('$usuario_id', '$produto_id', '$quantidade')";
$resultado = mysqli_query($conexao, $sql);

// Verificar se o cadastro foi bem-sucedido
if (!$resultado) {
    die('Query Inválido: ' . mysqli_error($conexao));
} else {
    // Atualizar o estoque na tabela de produtos
    $sql_atualizar_estoque = "UPDATE produto SET quantidade = quantidade + '$quantidade' WHERE id = '$produto_id'";
    $resultado_atualizar_estoque = mysqli_query($conexao, $sql_atualizar_estoque);

    if (!$resultado_atualizar_estoque) {
        die('Erro ao atualizar o estoque: ' . mysqli_error($conexao));
    }

    // Montar a mensagem para o Discord
    $mensagem = "```md\n";
    $mensagem .= "######  Registro de Farm  ######\n\n";
    $mensagem .= "## Item/Produto:\n";
    // Obter o nome do produto
    $query_produto = "SELECT nome_produto FROM produto WHERE id = '$produto_id'";
    $resultado_produto = mysqli_query($conexao, $query_produto);
    $produto = mysqli_fetch_assoc($resultado_produto);
    if ($produto) {
        $mensagem .= "- " . $produto['nome_produto'] . "\n";
    }
    $mensagem .= "## Quantidade:\n";
    $mensagem .= "- " . $quantidade . "\n";
    $mensagem .= "## Quem farmou:\n";
    // Obter o nome do usuário
    $query_usuario = "SELECT nome FROM usuario WHERE id = '$usuario_id'";
    $resultado_usuario = mysqli_query($conexao, $query_usuario);
    $usuario = mysqli_fetch_assoc($resultado_usuario);
    if ($usuario) {
        $mensagem .= "- " . $usuario['nome'] . "\n";
    }
    $mensagem .= "## Data e Hora:\n";
    $mensagem .= "- " . date('d/m/Y H:i:s') . "\n";
    $mensagem .= "```";

    // Webhook do Discord
    $webhook_url = 'https://discord.com/api/webhooks/1243980870591709245/RdOEV5D42gPwpBvnCVN2OsHF0pEbeR8Y1PnPPq-cgPfzSNTHi_PsKaV4kNV0pB6rTG0E';

    // Dados do webhook
    $data = json_encode(['content' => $mensagem]);

    // Configurações da requisição CURL
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Executar a requisição CURL
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obter o status HTTP da requisição
    curl_close($ch);

    // Mensagem de registro
    echo "<div class='container'>";
    echo "<div class='alert alert-success' role='alert'>";
    echo "<h4 class='alert-heading'>Registro de Farm</h4>";
    echo "<p>Item/Produto: ";
    if ($produto) {
        echo $produto['nome_produto'];
    }
    echo "</p>";
    echo "<p>Quantidade: $quantidade</p>";
    echo "<p>Quem farmou: ";
    if ($usuario) {
        echo $usuario['nome'];
    }
    echo "</p>";
    echo "<p>Data e Hora: " . date('d/m/Y H:i:s') . "</p>";
    echo "</div>";
    echo "</div>";

    // Redirecionar para cad_farm.php após 3 segundos
    echo "<meta http-equiv='refresh' content='1;url=cad_farm.php'>";
}
mysqli_close($conexao);
?>
