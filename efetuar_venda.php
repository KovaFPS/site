<?php
include("conexao.php");
include("logica-usuario.php");

verificaUsuario();

$id_cliente = $_POST['id_cliente'];
$obs = $_POST['obs'];
$itens_venda = $_POST['itens_venda'];

// Realizar o cadastro da venda
$sql_venda = "INSERT INTO vendas (id_cliente, obs) VALUES ('$id_cliente', '$obs')";
$resultado_venda = mysqli_query($conexao, $sql_venda);

// Verificar se a venda foi bem-sucedida
if ($resultado_venda) {
    // Obter o ID da venda
    $id_venda = mysqli_insert_id($conexao);

    // Iterar sobre os itens da venda
    foreach ($itens_venda as $item) {
        $id_produto = $item['id_produto'];
        $qnt = $item['qnt'];
        $preco = $item['preco'];

        // Cadastrar o item na tabela itens_venda
        $sql_item = "INSERT INTO itens_venda (id_venda, id_produto, qnt, preco) VALUES ('$id_venda', '$id_produto', '$qnt', '$preco')";
        $resultado_item = mysqli_query($conexao, $sql_item);

        // Atualizar a quantidade de itens no estoque
        $sql_update_estoque = "UPDATE produto SET quantidade = quantidade - $qnt WHERE id = '$id_produto'";
        $resultado_update_estoque = mysqli_query($conexao, $sql_update_estoque);

        if (!$resultado_item || !$resultado_update_estoque) {
            die('Erro ao registrar venda: ' . mysqli_error($conexao));
        }
    }

    // Mensagem de sucesso
    echo "<div class='container'>";
    echo "<div class='alert alert-success' role='alert'>";
    echo "<h4 class='alert-heading'>Venda Registrada</h4>";
    echo "<p>A venda foi registrada com sucesso!</p>";
    echo "</div>";
    echo "</div>";

    // Redirecionar após 3 segundos
    echo "<meta http-equiv='refresh' content='3;url=index.php'>";
} else {
    // Mensagem de erro
    echo "<div class='container'>";
    echo "<div class='alert alert-danger' role='alert'>";
    echo "<h4 class='alert-heading'>Erro ao Registrar Venda</h4>";
    echo "<p>Erro ao registrar a venda. Por favor, tente novamente.</p>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conexao);
?>
