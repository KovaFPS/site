<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $valor_unitario = $_POST['valor_unitario'];

    // Verificação de estoque
    $sql_estoque = "SELECT quantidade FROM produto WHERE id='$produto_id'";
    $result_estoque = mysqli_query($conexao, $sql_estoque);
    $produto = mysqli_fetch_assoc($result_estoque);

    if ($produto) {
        if ($produto['quantidade'] >= $quantidade) {
            $nova_quantidade = $produto['quantidade'] - $quantidade;
            $sql_update_estoque = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
            mysqli_query($conexao, $sql_update_estoque);

            $sql = "INSERT INTO saida_venda (cliente_id, produto_id, quantidade, valor_unitario) VALUES ('$cliente_id', '$produto_id', '$quantidade', '$valor_unitario')";
            if (mysqli_query($conexao, $sql)) {
                echo "Registro de saída por venda realizado com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
            }
        } else {
            echo "Quantidade solicitada indisponível em estoque!";
        }
    } else {
        echo "Produto não encontrado!";
    }
}
?>
