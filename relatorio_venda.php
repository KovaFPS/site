<?php
include("conexao.php");
include("logica-usuario.php");

verificaUsuario();

// Inicializa o filtro como uma string vazia
$filtro = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores dos campos de filtro
    $filtro_cliente = $_POST["cliente_id"];
    $filtro_produto = $_POST["produto_id"];
    $filtro_data_inicio = $_POST["data_inicio"];
    $filtro_data_fim = $_POST["data_fim"];

    // Verifica se os campos de filtro não estão vazios e constrói a cláusula WHERE
    if (!empty($filtro_cliente)) {
        $filtro .= " AND c.id = '$filtro_cliente'";
    }
    if (!empty($filtro_produto)) {
        $filtro .= " AND p.id = '$filtro_produto'";
    }
    if (!empty($filtro_data_inicio) && !empty($filtro_data_fim)) {
        $filtro .= " AND DATE(f.data) BETWEEN '$filtro_data_inicio' AND '$filtro_data_fim'";
    }
}

// Consulta SQL com a cláusula WHERE aplicada
$consulta = "SELECT f.*, p.nome_produto AS nome_produto, c.nome AS nome_cliente, u.nome AS nome_vendedor
             FROM saida_venda f 
             JOIN produto p ON f.produto_id = p.id
             JOIN cliente c ON f.cliente_id = c.id
             JOIN usuario u ON f.usuario_id = u.id
             WHERE 1 $filtro
             ORDER BY f.data_venda DESC";

// Executa a consulta
$con = @mysqli_query($conexao, $consulta) or die($mysql->error);

// Obtém o total de registros
$total_registros = mysqli_num_rows($con);

// Inicializa variáveis para os cálculos
$total_vendas = 0;
$quantidade_por_produto = array();
$total_por_produto = array();

// Loop para calcular o total de vendas e a quantidade por produto
while ($row = mysqli_fetch_assoc($con)) {
    $total_vendas += $row['quantidade'] * $row['valor_unitario'];
    if (!isset($quantidade_por_produto[$row['nome_produto']])) {
        $quantidade_por_produto[$row['nome_produto']] = 0;
        $total_por_produto[$row['nome_produto']] = 0;
    }
    $quantidade_por_produto[$row['nome_produto']] += $row['quantidade'];
    $total_por_produto[$row['nome_produto']] += $row['quantidade'] * $row['valor_unitario'];
}

// Voltamos ao início dos resultados para exibir os detalhes na tabela
mysqli_data_seek($con, 0);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
    <?php include("header.php") ?>
    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <div class="col-md-6">
                <h1>Relatório de Vendas</h1>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="cliente_id">Filtrar por Cliente:</label>
                        <select class="form-control" name="cliente_id" id="cliente_id">
                            <option value="">Todos</option>
                            <?php
                            $sql_clientes = "SELECT id, nome FROM cliente";
                            $result_clientes = mysqli_query($conexao, $sql_clientes);
                            while ($row = mysqli_fetch_assoc($result_clientes)) {
                                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="produto_id">Filtrar por Produto:</label>
                        <select class="form-control" name="produto_id" id="produto_id">
                            <option value="">Todos</option>
                            <?php
                            $sql_produtos = "SELECT id, nome_produto FROM produto";
                            $result_produtos = mysqli_query($conexao, $sql_produtos);
                            while ($row = mysqli_fetch_assoc($result_produtos)) {
                                echo "<option value='{$row['id']}'>{$row['nome_produto']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="data_inicio">Data Inicial:</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="data_fim">Data Final:</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>

                <br>

                <?php if ($total_registros > 0) { ?>
                <h3>Detalhes dos Registros:</h3>
                <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Data Venda</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($con)) { ?>
                    <tr>
                        <td><?php echo date("d/m/Y", strtotime($row['data_venda'])); ?></td>
                        <td><?php echo $row['nome_vendedor']; ?></td>
                        <td><?php echo $row['nome_cliente']; ?></td>
                        <td><?php echo $row['nome_produto']; ?></td>
                        <td><?php echo $row['quantidade']; ?></td>
                        <td><?php echo "$" . number_format($row['valor_unitario'], 2, ',', '.'); ?></td>
                        <td><?php echo "$" . number_format($row['quantidade'] * $row['valor_unitario'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
                <?php } else { ?>
                <p>Nenhum registro encontrado.</p>
                <?php } ?>
            </div>
            <div class="col-md-6">
                <?php if ($total_registros > 0) { ?>
                <h3>Resumo:</h3>
                <p>Total de Registros: <?php echo $total_registros; ?></p>
                <p>Total de Vendas: <?php echo "$" . number_format($total_vendas, 2, ',', '.'); ?></p>
                <h3>Quantidade por Produto:</h3>
                <ul>
                    <?php foreach ($quantidade_por_produto as $produto => $quantidade) { ?>
                    <li><?php echo "$produto: $quantidade - Total: $" . number_format($total_por_produto[$produto], 2, ',', '.'); ?></li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
</body>

</html>
