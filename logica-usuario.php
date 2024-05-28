<?php
session_start();
include("log_usuario.php");

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
        return $id;
    }
    return null;
}

function logaUsuario($conexao, $usuario){
    include("banco-usuario.php");
    $id = obterIdUsuarioPorNome($conexao, $usuario); // Passando a conexão como parâmetro
    $query = "SELECT nome, stateid FROM usuario WHERE id = '$id'";
    $resultado = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($resultado);
    $_SESSION["usuario_logado"] = array(
        "id" => $id,
        "nome" => $row['nome'],
        "stateid" => $row['stateid']
    );

    // Registra log de login
    registraLog($conexao, $id, $_SESSION["usuario_logado"]["nome"], "Login");

    return $_SESSION["usuario_logado"];
}

function logout(){
    if (isset($_SESSION["usuario_logado"])) {
        include("conexao.php");
        $stateid = $_SESSION["usuario_logado"]["stateid"];
        $nome = $_SESSION["usuario_logado"]["nome"];
        // Registra log de logout
        registraLog($conexao, $stateid, $nome, "Logout");
    }
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

if (!function_exists('registraLog')) {
    function registraLog($conexao, $id, $nome, $acao) {
        $query = "INSERT INTO log (usuario_id, acao, data_hora) VALUES ('$id', '$acao', NOW())";
        mysqli_query($conexao, $query);
    }
}

?>


