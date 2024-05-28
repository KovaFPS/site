<?php
include("logica-usuario.php");
verificaUsuario();
include("conexao.php");

// Obter o ID do usuário logado
$usuario_id = usuarioLogado();
$query = "SELECT id, nome FROM usuario WHERE id = '$usuario_id'";
$resultado = mysqli_query($conexao, $query);
$usuario = mysqli_fetch_assoc($resultado);

// Verificar se o usuário foi encontrado
if ($usuario) {
    $usuario_nome = $usuario['nome'];
} else {
    // Se não foi encontrado, definir como vazio ou uma mensagem de erro
    $usuario_nome = 'Nome não encontrado';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Farm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>
<body>
    <?php include("header.php") ?>

    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-5 text-center" style="background: linear-gradient(to right, #58AF9C, #58AF9C, #2E8B57); color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h3> Olá, seja bem vindo!</h3>
                    <h1> Registro de Farm</h1> <br>
                    <i class="fas fa-seedling" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4" value="Voltar" onclick="javascript: location.href='index.php';" style="border-radius: 25px;">Voltar</button>
                </div>
            </div>
            <div class="col-md-7 p-5">
                <h2 class="text-center" style="color: #58AF9C;"> <i class="far fa-copy"></i> Criar Registro de Farm </h2>
                <hr class="mb-5">
                <form method="post" action="efetuar_cad_farm.php">
                <div class="form-row">
            <div class="form-group col-md-6">
                <label for="membro">Membro</label>
                <input type="text" class="form-control" id="membro" value="<?php echo $usuario_nome; ?>" readonly>
                <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
            </div>
                        <div class="form-group col-md-6">
                            <label for="produto">Produto</label>
                            <select class="form-control" name="produto_id" id="produto" required>
                                <?php
                                $sql = "SELECT id, nome_produto FROM produto";
                                $result = mysqli_query($conexao, $sql);
                                if (!$result) {
                                    echo "<option value=''>Erro ao carregar produtos</option>";
                                } else {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['id']}'>{$row['nome_produto']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" id="quantidade" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn m-4" style="background-color: #58AF9C; color:white; border-radius: 25px;" value="Registrar">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
</body>
</html>
