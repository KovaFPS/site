<?php
include("logica-usuario.php");
verificaUsuario();
?>
<?php
include("conexao.php");
$consulta = "SELECT farm.*, produto.nome_produto AS nome_produto, usuario.nome AS quem_farmou FROM farm 
             INNER JOIN produto ON farm.produto_id = produto.id
             INNER JOIN usuario ON farm.usuario_id = usuario.id
             ORDER BY farm.data DESC";

if (isset($_GET['txtpesquisa'])) {
    $pesquisa = $_GET['txtpesquisa'];
    $consulta = "SELECT farm.*, produto.nome_produto AS nome_produto, usuario.nome AS quem_farmou FROM farm 
                 INNER JOIN produto ON farm.produto_id = produto.id
                 INNER JOIN usuario ON farm.usuario_id = usuario.id
                 WHERE usuario.nome LIKE '%$pesquisa%' OR produto.nome_produto LIKE '%$pesquisa%'
                 ORDER BY farm.data DESC";
}

$con = @mysqli_query($conexao, $consulta) or die($mysql->error);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Farm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include("header.php") ?>
    <div class="container-fluid">
        <div class="row m-5 ">
            <div class="col-md-12 mt-4">
                <form method="get" action="lista_farm.php">
                    <div class="d-flex justify-content-between mt-5 mb-2 mr-2">
                        <div>
                            <h2 class="ml-2"> <i class="far fa-copy"></i> Consulta de Farm </h2>
                        </div>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <input type="text" class="form-control" name="txtpesquisa" id="exampleInputPesquisar" aria-describedby="pesquisa" placeholder="Pesquisa">
                            </li>
                            <li class="list-inline-item">
                                <button type="submit" class="btn"><i class="fas fa-search"></i> Pesquisar</button>
                            </li>
                            <li class="list-inline-item">
                                <a href="cad_farm.php" class="btn btn-secondary"> <i class="fas fa-plus"></i> Novo</a>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <table class="table table-borderless table-responsive-md table-hover">
                    <thead>
                        <tr>
                            <th style="display: none;">CÓDIGO</th>
                            <th>PRODUTO</th>
                            <th>QUANTIDADE</th>
                            <th>DATA E HORA</th>
                            <th>QUEM FARMOU</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <?php while ($dado = $con->fetch_array()) { ?>
                        <tbody>
                            <tr>
                                <td style="display: none;"><?php echo $dado['id']; ?></td>
                                <td><?php echo $dado['nome_produto']; ?></td>
                                <td><?php echo $dado['quantidade']; ?></td>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($dado['data'])); ?></td>
                                <td><?php echo $dado['quem_farmou']; ?></td>
                                <td class="d-flex">
                                    <!-- Adicione aqui os botões de ação, como editar, excluir, etc. -->
                                </td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
