<?php
include("conexao.php");
include("logica-usuario.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = (int)$_POST['produto_id'];
    $quantidade = (int)$_POST['quantidade'];
    $motivo = mysqli_real_escape_string($conexao, $_POST['motivo']);
    $usuario_id = $_SESSION['usuario_logado']['id'];

    // Verificação de estoque
    $sql_estoque = "SELECT quantidade FROM produto WHERE id='$produto_id'";
    $result_estoque = mysqli_query($conexao, $sql_estoque);
    $produto = mysqli_fetch_assoc($result_estoque);

    if ($produto['quantidade'] >= $quantidade) {
        // Atualizar estoque
        $nova_quantidade = $produto['quantidade'] - $quantidade;
        $sql_update = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
        mysqli_query($conexao, $sql_update);

        // Registrar saída por uso
        $sql = "INSERT INTO saida_uso (produto_id, quantidade, motivo, usuario_id) VALUES ('$produto_id', '$quantidade', '$motivo', '$usuario_id')";
        if (mysqli_query($conexao, $sql)) {
            header("Location: success_page.php");
        } else {
            echo "Erro: " . mysqli_error($conexao);
        }
    } else {
        echo "Quantidade solicitada indisponível em estoque.";
    }
}
?>
