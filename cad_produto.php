<?php
include("logica-usuario.php");
verificaUsuario();
?>
<!DOCTYPE html>
<html lang="pt-br">

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
        <div class="row mt-5">
            <!--COLUNA 1-->
            <div class="col-md-5"  style="background: linear-gradient(to bottom, #EE82EE, #EE82EE, #DB7093); color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h3> Olá, seja bem vindo!</h3>
                    <h1> Cadastro de Produto</h1> <br>
                    <i class="fas fa-dolly" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4 mr-2" value="Voltar" onclick="javascript: location.href='index.php';" style="border-radius: 25px;"> Voltar</button>
                    <!--redireciona para a tela indicada-->
                </div>
            </div>
            <!--COLUNA 2-->
            <div class="col-md-7 p-5 ">
                <h2 class="text-center" style="color: #EE82EE;"> <i class="far fa-copy"></i> Criar Cadastro </h2>
                <hr class="mb-5">
                <!-- Ele envia os dados para tela de cadastro-->
                <form method="post" action="efetuar_cad_produto.php" enctype="multipart/form-data">
                    <!-- 1° linha-->
                    <div class="form-row">
                        <div class="col-md-7 mb-3">
                            <label for="exampleInputNomeProduto"> Nome do Produto</label>
                            <input type="text" class="form-control" name="txtnome_produto" id="exampleInputNomeProduto" aria-describedby="produto" placeholder="Nome do produto">
                        </div>
                    </div>

                    <!-- 2° linha-->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="exampleInputValorMercado"> Valor de Mercado </label>
                            <input type="text" class="form-control" name="txtvalor_mercado" id="exampleInputValorMercado" aria-describedby="preco" placeholder="Digite o Valor de Mercado">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="exampleInputValorCustoMedio"> Valor de Custo Médio </label>
                            <input type="text" class="form-control" name="txtvalor_custo_medio" id="exampleInputValorCustoMedio" aria-describedby="custo" placeholder="Digite o Valor de Custo Médio">
                        </div>
                    </div>

                    <!-- 3° linha-->
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="ondePega">Onde Pega</label>
                            <select class="form-control" name="txtonde_pega" id="ondePega" onchange="checkOndePega()">
                                <option value="Farm Sanitation">Farm Sanitation</option>
                                <option value="Farm Madeira">Farm Madeira</option>
                                <option value="Farm Prisão">Farm Prisão</option>
                                <option value="Farm Plantação Weed">Farm Plantação Weed</option>
                                <option value="Compra">Compra</option>
                                <option value="Blueprint">Blueprint</option>
                                <option value="App Drone">App Drone</option>
                                <option value="Heist's">Heist's</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4° linha-->
                    <div class="form-row" id="extraFields" style="display: none;">
                        <div class="col-md-12 mb-3" id="blueprintField" style="display: none;">
                            <label for="exampleInputNomeBlueprint"> Nome da Blueprint </label>
                            <input type="text" class="form-control" name="txtnome_blueprint" id="exampleInputNomeBlueprint" placeholder="Digite o nome da Blueprint">
                        </div>
                        <div class="col-md-12 mb-3" id="heistField" style="display: none;">
                            <label for="exampleInputQualHeist"> Qual Heist </label>
                            <input type="text" class="form-control" name="txtqual_heist" id="exampleInputQualHeist" placeholder="Digite o nome do Heist">
                        </div>
                    </div>

                    <!-- 5° linha-->
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="exampleInputImagem"> Foto do Item </label>
                            <input type="file" name="txtimagem" id="exampleInputImagem" aria-describedby="imagem">
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="btnca" class="btn m-4" style="background-color: #EE82EE; color:white; border-radius: 25px;" value="cadastrar">Cadastrar</button>
                        <!--Submeter via post para a pag efetuar cadastro.php-->
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    
    <script>
        function checkOndePega() {
            const ondePega = document.getElementById('ondePega').value;
            const extraFields = document.getElementById('extraFields');
            const blueprintField = document.getElementById('blueprintField');
            const heistField = document.getElementById('heistField');
            
            extraFields.style.display = 'none';
            blueprintField.style.display = 'none';
            heistField.style.display = 'none';

            if (ondePega === 'Blueprint') {
                extraFields.style.display = 'block';
                blueprintField.style.display = 'block';
            } else if (ondePega === "Heist's") {
                extraFields.style.display = 'block';
                heistField.style.display = 'block';
            }
        }
    </script>
</body>

</html>
