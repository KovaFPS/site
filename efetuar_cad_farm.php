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
    // Montar a mensagem para o Discord
    $mensagem = "```md\n";
    $mensagem .= "# Registro de Farm\n\n";
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
    curl_close($ch);

    // Verificar se a requisição foi bem-sucedida
    if (!$response) {
        echo "<script>alert('Erro ao enviar mensagem para o Discord');</script>";
    } else {
        echo "<script>alert('Registro de farm realizado com sucesso!'); window.location.href='cad_farm.php';</script>";
    }
}

mysqli_close($conexao);
?>