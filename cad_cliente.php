<?php

include("logica-usuario.php");

//VERIFICA SE FOI LOGADO
verificaUsuario();
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
        <div class="row mt-5">
            <div class="col-md-5 text-center" style="background: linear-gradient(to right, #58AF9C, #58AF9C, #2E8B57); color:white">
                <br><br><br><br><br><br><br>
                <div style="position: fixed;" class="ml-3 pl-5 text-center">
                    <h3> Olá, seja bem vindo!</h3>
                    <h1> Cadastro de Cliente</h1> <br>
                    <i class="fas fa-user-friends" style="font-size: 100px;"></i><br><br>
                    <button type="button" class="btn btn-outline-light mt-4" value="Voltar" onclick="javascript: location.href='index.php';" style="border-radius: 25px;">Voltar</button>
                    <!--redireciona para a tela indicada-->
                </div>
            </div>
            <div class="col-md-7 p-5">
                <h2 class="text-center" style="color: #58AF9C;"> <i class="far fa-copy"></i> Criar Cadastro </h2>
                <hr class="mb-5">
                <form method="post" action="efetuar_cad_cliente.php">
                    <!-- Ele envia os dados para tela de cadastro-->
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="exampleInputNome">Nome</label>
                            <input type="text" class="form-control " name="txtnome" id="exampleInputNome" aria-describedby="nome" placeholder="Digite o nome">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputStateID">State ID</label>
                            <input type="text" class="form-control " name="txtstate_id" id="exampleInputStateID" aria-describedby="state_id" placeholder="Digite o State ID">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleInputTelefone">Telefone</label>
                            <input type="text" class="form-control " name="txttelefone" id="exampleInputTelefone" aria-describedby="telefone" placeholder="Digite o telefone">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputTurfOrganizacaoEmpresa">Turf/Organização/Empresa</label>
                            <input type="text" class="form-control" name="txtturforganizacaoempresa" id="exampleInputTurfOrganizacaoEmpresa" aria-describedby="turf_organizacao_empresa" placeholder="Digite a Turf/Organização/Empresa">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleInputLimiteCredito">Limite de Crédito</label>
                            <input type="number" step="0.01" class="form-control" name="txtlimitecredito" id="exampleInputLimiteCredito" aria-describedby="limite_credito" placeholder="Digite o Limite de Crédito">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEndereco">Endereço</label>
                            <input type="text" class="form-control" name="txtendereco" id="exampleInputEndereco" aria-describedby="endereco" placeholder="Digite o Endereço">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="btnca" class="btn m-4" style="background-color: #58AF9C; color:white; border-radius: 25px;" value="cadastrar">Cadastrar</button>
                    </div>
                    <!--Submeter via post para a pag efetuar cadastro.php-->
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