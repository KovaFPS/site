<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comprador = $_POST['comprador'];
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $valor_unitario = $_POST['valor_unitario'];

    // Verificação de estoque
    $sql_estoque = "SELECT quantidade FROM produto WHERE id='$produto_id'";
    $result_estoque = mysqli_query($conexao, $sql_estoque);
    $produto = mysqli_fetch_assoc($result_estoque);

    if ($produto) {
        if ($produto['quantidade'] >= $quantidade) {
            $nova_quantidade = $produto['quantidade'] + $quantidade;
            $sql_update_estoque = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
            mysqli_query($conexao, $sql_update_estoque);

            $sql = "INSERT INTO entrada_compra (comprador, produto_id, quantidade, valor_unitario) VALUES ('$comprador', '$produto_id', '$quantidade', '$valor_unitario')";
            if (mysqli_query($conexao, $sql)) {
                echo "Registro de entrada por compra realizado com sucesso!";
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
