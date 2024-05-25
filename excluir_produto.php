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
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center" style="margin-top: 100px">
                <?php
                include_once('conexao.php');
                
                //RECUPERAÇÃO
                if (isset($_POST['txtid'])) {
                    $codigo = intval($_POST['txtid']);

                    // Prepare the SQL statement to avoid SQL injection
                    $stmt = $conexao->prepare("DELETE FROM produto WHERE id = ?");
                    $stmt->bind_param("i", $codigo);
                    
                    // Execute the prepared statement
                    if ($stmt->execute()) {
                        echo '
                        <div class="alert alert-success" role="alert">
                            <div class="p-5">
                                <i class="far fa-grin-beam-sweat h1"></i>          
                                <p> Registro deletado com sucesso </p>
                            </div>
                        </div>';
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                            <div class="p-5">
                                <i class="far fa-frown h1"></i>          
                                <p> Erro ao deletar o registro: ' . htmlspecialchars($stmt->error) . '</p>
                            </div>
                        </div>';
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                        <div class="p-5">
                            <i class="far fa-frown h1"></i>          
                            <p> ID do produto não fornecido. </p>
                        </div>
                    </div>';
                }
                ?>
                <a href="lista_produto.php" class="btn mt-3" style="background-color: #EE82EE; color:white; border-radius: 25px;">Voltar para consulta</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
