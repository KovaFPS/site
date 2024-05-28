<?php
session_start();

function usuarioEstaLogado(){
    return isset($_SESSION["usuario_logado"]);
}

function verificaUsuario(){
    if(!usuarioEstaLogado()){
        header("Location: erro.php");
        die();
    }
}

function usuarioLogado(){
    if(usuarioEstaLogado()){
        $id = $_SESSION["usuario_logado"]["id"];
        // Adicione esta linha para depurar
        echo "ID do usuário logado: $id<br>";
        return $id;
    }
    return null;
}



function logaUsuario($conexao, $usuario){
    include("banco-usuario.php");
    $id = obterIdUsuarioPorNome($conexao, $usuario); // Passando a conexão como parâmetro
    $query = "SELECT nome FROM usuario WHERE id = '$id'";
    $resultado = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($resultado);
    $_SESSION["usuario_logado"] = array(
        "id" => $id,
        "nome" => $row['nome']
    );

    return $_SESSION["usuario_logado"];
}

function logout(){
    session_destroy();
}

function obterIdUsuarioLogado() {
    if (isset($_SESSION["usuario_logado"]["id"])) {
        return $_SESSION["usuario_logado"]["id"];
    } else {
        return null;
    }
}

function obterNomeUsuarioLogado() {
    if (isset($_SESSION["usuario_logado"]["nome"])) {
        return $_SESSION["usuario_logado"]["nome"];
    } else {
        return null;
    }
}
?>
