<?php
include("conexao.php");

if (!function_exists('registraLog')) {
    function registraLog($conexao, $id, $nome, $acao) {
        // Use $id em vez de $stateid para registrar o id do usuário
        $query = "INSERT INTO log (usuario_id, acao, data_hora) VALUES ('$id', '$acao', NOW())";
        mysqli_query($conexao, $query);
    }
}
?>
