<?php

//FUNCAO PARA EFETUARLOGIN

function efetuaLogin($conexao, $stateid, $senha){
    $query = "select * from usuario where stateid='{$stateid}' and senha='{$senha}'";

    $resultado = mysqli_query($conexao, $query);

$usuariologado = mysqli_fetch_assoc($resultado);

return $usuariologado;

}