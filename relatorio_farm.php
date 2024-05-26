<?php
include("conexao.php");
include("logica-usuario.php");

verificaUsuario();

// Inicializa o filtro como uma string vazia
$filtro = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores dos campos de filtro
    $filtro_membro = $_POST["filtro_membro"];
    $filtro_produto = $_POST["filtro_produto"];
    $filtro_data_inicio = $_POST["filtro_data_inicio"];
    $filtro_data_fim = $_POST["filtro_data_fim"];

    // Verifica se os campos de filtro não estão vazios e constrói a cláusula WHERE
    if (!empty($filtro_membro)) {
        $filtro .= " AND usuario.nome LIKE '%$filtro_membro%'";
    }
    if (!empty($filtro_produto)) {
        $filtro .= " AND produto.nome_produto LIKE '%$filtro_produto%'";
    }
    if (!empty($filtro_data_inicio) && !empty($filtro_data_fim)) {
        $filtro .= " AND farm.data BETWEEN '$filtro_data_inicio' AND '$filtro_data_fim'";
    }
}

// Consulta SQL com a cláusula WHERE aplicada
$consulta = "SELECT farm.*, produto.nome_produto AS nome_produto, usuario.nome AS quem_farmou 
             FROM farm 
             INNER JOIN produto ON farm.produto_id = produto.id
             INNER JOIN usuario ON farm.usuario_id = usuario.id
             WHERE 1 $filtro
             ORDER BY farm.data DESC";

// Executa a consulta
$con = @mysqli_query($conexao, $consulta) or die($mysql->error);

// Obtém o total de registros
$total_registros = mysqli_num_rows($con);

// Inicializa variáveis para o cálculo
$total_produtos_distintos = 0;
$total_produtos_registrados = 0;
$quantidade_por_produto = array();

// Loop para calcular o total de produtos distintos, a quantidade por produto e o total de produtos registrados
while ($dado = $con->fetch_array()) {
    $total_produtos_registrados += $dado['quantidade'];
    if (!isset($quantidade_por_produto[$dado['nome_produto']])) {
        $total_produtos_distintos++;
    }
    $quantidade_por_produto[$dado['nome_produto']] = isset($quantidade_por_produto[$dado['nome_produto']]) ? $quantidade_por_produto[$dado['nome_produto']] + $dado['quantidade'] : $dado['quantidade'];
}

// Voltamos ao início dos resultados para exibir os detalhes na tabela
mysqli_data_seek($con, 0);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Farm</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
    <?php include("header.php") ?>
    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <div class="col-md-6">
                <h1>Relatório de Farm</h1>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="filtro_membro">Filtrar por Membro:</label>
                        <input type="text" class="form-control" id="filtro_membro" name="filtro_membro">
                    </div>
                    <div class="form-group">
                        <label for="filtro_produto">Filtrar por Produto:</label>
                        <input type="text" class="form-control" id="filtro_produto" name="filtro_produto">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="filtro_data_inicio">Data Inicial:</label>
                            <input type="date" class="form-control" id="filtro_data_inicio"
                                name="filtro_data_inicio">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="filtro_data_fim">Data Final:</label>
                            <input type="date" class="form-control" id="filtro_data_fim" name="filtro_data_fim">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>

                <br>

                <?php if ($total_registros > 0) { ?>
                <p>Filtrado por:
                    <?php
                        if (!empty($filtro_membro)) echo " Membro: $filtro_membro;";
                        if (!empty($filtro_produto)) echo " Produto: $filtro_produto;";
                        if (!empty($filtro_data_inicio) && !empty($filtro_data_fim)) echo " Intervalo de Data: $filtro_data_inicio até $filtro_data_fim;";
                        ?>
                </p>

                <h3>Detalhes dos Registros:</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Membro</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dado = $con->fetch_array()) { ?>
                        <tr>
                            <td><?php echo date("d/m/Y H:i:s", strtotime($dado['data'])); ?></td>
                            <td><?php echo $dado['quem_farmou']; ?></td>
                            <td><?php echo $dado['nome_produto']; ?></td>
                            <td><?php echo $dado['quantidade']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <p>Nenhum registro encontrado.</p>
                <?php } ?>
            </div>
            <div class="col-md-6">
                <h3>Resumo:</h3>
                <p>Total de Registros: <?php echo $total_registros; ?></p>
                <p>Total de Produtos Distintos: <?php echo $total_produtos_distintos; ?></p>
                <p>Total de Produtos Registrados: <?php echo $total_produtos_registrados; ?></p>
                <h3>Quantidade por Produto:</h3>
                <ul>
                    <?php foreach ($quantidade_por_produto as $produto => $quantidade) { ?>
                    <li><?php echo "$produto: $quantidade"; ?></li>
                    <?php } ?>
                </ul>
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
