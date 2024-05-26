<?php
include("logica-usuario.php");
verificaUsuario();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Cadastro de Cliente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>

<body>
    <?php include("header.php") ?>

    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-5" style="background-color: #58AF9C; color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h1>Alteração de Cadastro</h1>
                    <h1>Cliente</h1> <br>
                    <i class="fas fa-user-friends" style="font-size: 100px;"></i><br><br>
                    <a href="lista_cliente.php" class="btn btn-outline-light mt-4 mr-2" style="border-radius: 25px;">Voltar</a>
                </div>
            </div>
            <div class="col-md-7 p-5">
                <h2 class="text-center" style="color: #58AF9C;"> <i class="far fa-copy"></i> Alterar Cadastro </h2>
                <hr class="mb-5">
                <?php
                include_once('conexao.php');
                $codigo = $_GET['codigo'];
                $sqlconsulta = "SELECT * FROM cliente WHERE id = '$codigo'";
                $resultado = mysqli_query($conexao, $sqlconsulta);
                if (!$resultado) {
                    echo '<div class="alert alert-danger" role="alert">Erro ao buscar dados do cliente.</div>';
                } else {
                    $num = mysqli_num_rows($resultado);
                    if ($num == 0) {
                        echo "<div class='alert alert-warning' role='alert'>Cliente não encontrado.</div>";
                    } else {
                        $dado = mysqli_fetch_array($resultado);
                ?>
                        <form method="post" action="alteracao_cliente.php">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputNome">Nome</label>
                                    <input type="text" class="form-control" name="txtnome" value='<?php echo $dado['nome']; ?>'>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputTelefone">Telefone</label>
                                    <input type="text" class="form-control" name="txttelefone" value='<?php echo $dado['telefone']; ?>'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputState">StateID</label>
                                    <input type="text" class="form-control" name="txtstate_id" value='<?php echo $dado['state_id']; ?>'>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputLimiteCredito">Limite de Crédito</label>
                                    <input type="text" class="form-control" name="txtlimite_credito" value='<?php echo $dado['limite_credito']; ?>'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEndereco">Endereço</label>
                                <input type="text" class="form-control" name="txtendereco" value='<?php echo $dado['endereco']; ?>'>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn m-2 text-center" style="background-color: #58AF9C; color:white; border-radius: 25px;" value="Alterar"><i class="fas fa-pencil-alt"></i> Alterar</button>
                                <a href="ver_excluir_cliente.php?codigo=<?php echo $dado['id']; ?>" class="btn btn-danger m-1 btn-excluir" style="border-radius: 30px;" role="button">
                                    <i class="far fa-trash-alt"></i> Excluir </a>
                            </div>
                        </form>
                <?php
                    }
                }
                mysqli_close($conexao);
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
