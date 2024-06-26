<?php
include("logica-usuario.php");

// Verifica se o usuário está logado
verificaUsuario();

// Inclui o arquivo de conexão com o banco de dados
include("conexao.php");

// Consulta SQL para selecionar todos os produtos ordenados por nome_produto
$consulta = "SELECT * FROM produto ORDER BY nome_produto ASC";

// Executa a consulta
$resultado = mysqli_query($conexao, $consulta);

// Verifica se a consulta retornou resultados
if (!$resultado) {
    die("Erro ao consultar produtos: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>

<body>
    <?php include("header.php") ?>
    <div class="container-fluid">
        <div class="row m-5">
            <div class="col-md-12 mt-4">
                <!-- Início dos botões e formulário de pesquisa -->
                <form method="get" action="lista_produto.php">
                    <div class="d-flex justify-content-between mt-5 mb-2 mr-2">
                        <div>
                            <h2 class="ml-2" style="color: #EE82EE;"> <i class="far fa-copy"></i> Consulta de Produto </h2>
                        </div>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <input type="text" class="form-control" name="txtpesquisa" id="exampleInputPesquisar" aria-describedby="pesquisa" placeholder="Pesquisa" style="width: 350px; border-radius: 30px;">
                            </li>
                            <li class="list-inline-item">
                                <button type="submit" class="btn" style="background-color: #EE82EE; color:white; border-radius: 30px;"><i class="fas fa-search"></i> Pesquisar</button>
                            </li>
                            <li class="list-inline-item">
                                <a href="cad_produto.php" class="btn btn-secondary" style=" border-radius: 30px;"> <i class="fas fa-plus"></i> Novo</a>
                            </li>
                        </ul>
                    </div>
                </form>
                <!-- Fim dos botões e formulário de pesquisa -->

                <!-- Início da Tabela -->
                <table class="table table-borderless table-responsive-md table-hover">
                    <thead>
                        <tr style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; color: #4F4F4F">
                            <th>CÓDIGO</th>
                            <th>DESCRIÇÃO DO PRODUTO</th>
                            <th>VALOR DE MERCADO</th>
                            <th>CUSTO MÉDIO</th>
                            <th>ONDE PEGA</th>
                            <th>BLUEPRINT</th>
                            <th>HEIST</th>
                            <th>QUANTIDADE</th>
                            <th>IMAGEM</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($linha = mysqli_fetch_assoc($resultado)) { ?>
                            <tr style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; color: #4F4F4F">
                                <td><?php echo $linha['id']; ?></td>
                                <td><?php echo $linha['nome_produto']; ?></td>
                                <td><?php echo $linha['valor_mercado']; ?></td>
                                <td><?php echo $linha['valor_custo_medio']; ?></td>
                                <td><?php echo $linha['onde_pega']; ?></td>
                                <td><?php echo $linha['nome_blueprint']; ?></td>
                                <td><?php echo $linha['qual_heist']; ?></td>
                                <td><?php echo $linha['quantidade']; ?></td>
                                <td><img src="assets/img/produto/<?php echo $linha['imagem']; ?>" width="50px" height="50px"></td>
                                <td class="d-flex">
                                    <a href="ver_cad_produto.php?codigo=<?php echo $linha['id']; ?>" class="btn btn-alterar btn-sm m-1" style="background-color: #EE82EE; color:white;  border-radius: 30px;" role="button">
                                        <i class="fas fa-pencil-alt"></i> </a>
                                    <a href="ver_excluir_produto.php?codigo=<?php echo $linha['id']; ?>" class="btn btn-danger btn-sm m-1  btn-excluir" style=" border-radius: 30px;" role="button">
                                        <i class="far fa-trash-alt"></i> </a>
                                    <a target="_blank" href="relatorio_produto_individual.php?codigo=<?php echo $linha['id']; ?>" class="btn btn-success btn-sm m-1 btn-excluir" style=" border-radius: 30px;" role="button">
                                        <i class="fas fa-print"></i> </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <!-- Fim da Tabela -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
