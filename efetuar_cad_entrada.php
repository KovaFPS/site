<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_fornecedor = $_POST['id_fornecedor'];
    $data_entrada = $_POST['data_entrada'];
    $observacao = $_POST['observacao'];
    $produtos = $_POST['produtos'];

    $query = "INSERT INTO entrada (id_fornecedor, data_entrada, observacao) VALUES ('$id_fornecedor', '$data_entrada', '$observacao')";
    mysqli_query($conexao, $query);
    $id_entrada = mysqli_insert_id($conexao);

    foreach ($produtos as $produto) {
        $produto_id = $produto['id'];
        $quantidade = $produto['quantidade'];
        $preco = $produto['preco'];
        $query = "INSERT INTO itens_entrada (id_entrada, id_produto, quantidade, preco) VALUES ('$id_entrada', '$produto_id', '$quantidade', '$preco')";
        mysqli_query($conexao, $query);
    }
    header("Location: index.php");
}
?>
