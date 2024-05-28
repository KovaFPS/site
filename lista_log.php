<?php
include("conexao.php");
include("logica-usuario.php");

verificaUsuario();

// Inicializa o filtro como uma string vazia
$filtro = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores dos campos de filtro
    $filtro_usuario = $_POST["usuario_id"];
    $filtro_acao = $_POST["acao"];
    $filtro_data_inicio = $_POST["data_inicio"];
    $filtro_data_fim = $_POST["data_fim"];

    // Verifica se os campos de filtro não estão vazios e constrói a cláusula WHERE
    if (!empty($filtro_usuario)) {
        $filtro .= " AND l.usuario_id = '$filtro_usuario'";
    }
    if (!empty($filtro_acao)) {
        $filtro .= " AND l.acao = '$filtro_acao'";
    }
    if (!empty($filtro_data_inicio) && !empty($filtro_data_fim)) {
        $filtro .= " AND DATE(l.data) BETWEEN '$filtro_data_inicio' AND '$filtro_data_fim'";
    }
}

// Consulta SQL com a cláusula WHERE aplicada
$consulta = "SELECT l.*, u.nome AS nome_usuario
             FROM log l 
             JOIN usuario u ON l.usuario_id = u.id
             WHERE 1 $filtro
             ORDER BY l.data_hora DESC";




// Executa a consulta
$con = @mysqli_query($conexao, $consulta) or die($mysqli->error);

// Obtém o total de registros
$total_registros = mysqli_num_rows($con);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Logs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
    <?php include("header.php") ?>
    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <div class="col-md-6">
                <h1>Relatório de Logs</h1>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="usuario_id">Filtrar por Usuário:</label>
                        <select class="form-control" name="usuario_id" id="usuario_id">
                            <option value="">Todos</option>
                            <?php
                            $sql_usuarios = "SELECT id, nome FROM usuario";
                            $result_usuarios = mysqli_query($conexao, $sql_usuarios);
                            while ($row = mysqli_fetch_assoc($result_usuarios)) {
                                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="acao">Filtrar por Ação:</label>
                        <select class="form-control" name="acao" id="acao">
                            <option value="">Todas</option>
                            <option value="login">Login</option>
                            <option value="logout">Logout</option>
                            <option value="inserção">Inserção</option>
                            <option value="atualização">Atualização</option>
                            <option value="exclusão">Exclusão</option>
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
                        <th>Data</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($con)) { ?>
                    <tr>
                        <td><?php echo date("d/m/Y H:i:s", strtotime($row['data_hora'])); ?></td>
                        <td><?php echo $row['nome_usuario']; ?></td>
                        <td><?php echo $row['acao']; ?></td>
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
                <h3>Ações por Usuário:</h3>
                <?php
                $sql_resumo = "SELECT u.nome AS nome_usuario, COUNT(*) AS total_acoes
                FROM log l
                JOIN usuario u ON l.usuario_id = u.id
                WHERE 1 $filtro
                GROUP BY u.nome
                ORDER BY total_acoes DESC";
                $result_resumo = mysqli_query($conexao, $sql_resumo);
                while ($row = mysqli_fetch_assoc($result_resumo)) {
                    echo "<p>{$row['nome_usuario']}: {$row['total_acoes']} ações</p>";
                }
                ?>
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
