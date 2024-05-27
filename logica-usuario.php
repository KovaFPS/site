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
    return $_SESSION["usuario_logado"];
}

function logaUsuario($usuario){
    $_SESSION["usuario_logado"] = $usuario;
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
?>
