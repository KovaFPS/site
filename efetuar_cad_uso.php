<?php
include("conexao.php");
include("logica-usuario.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $motivo = $_POST['motivo'];
    $usuario_id = $_SESSION['usuario_logado']['id'];

    // Verificação de estoque
    $sql_estoque = "SELECT quantidade FROM produto WHERE id='$produto_id'";
    $result_estoque = mysqli_query($conexao, $sql_estoque);
    $produto = mysqli_fetch_assoc($result_estoque);

    if ($produto && $produto['quantidade'] >= $quantidade) {
        $nova_quantidade = $produto['quantidade'] - $quantidade;
        $sql_update_estoque = "UPDATE produto SET quantidade='$nova_quantidade' WHERE id='$produto_id'";
        mysqli_query($conexao, $sql_update_estoque);

        $sql = "INSERT INTO saida_uso (produto_id, quantidade, motivo, usuario_id) VALUES ('$produto_id', '$quantidade', '$motivo', '$usuario_id')";
        if (mysqli_query($conexao, $sql)) {
            echo "Registro de saída por uso realizado com sucesso!";
        } else {
            echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
        }
    } else {
        echo "Quantidade insuficiente em estoque!";
    }
}
?>
