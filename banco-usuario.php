<?php

// Verifica se a função efetuaLogin já existe antes de defini-la
if (!function_exists('efetuaLogin')) {
    // Função para efetuar o login e retornar os dados do usuário
    function efetuaLogin($conexao, $stateid, $senha){
        $query = "SELECT * FROM usuario WHERE stateid='{$stateid}' AND senha='{$senha}'";
        $resultado = mysqli_query($conexao, $query);
        $usuariologado = mysqli_fetch_assoc($resultado);
        return $usuariologado;
    }
}

// Verifica se a função obterIdUsuarioPorNome já existe antes de defini-la
if (!function_exists('obterIdUsuarioPorNome')) {
    // Função para obter o ID do usuário com base no nome de usuário
    function obterIdUsuarioPorNome($conexao, $usuario) {
        // Substitua este trecho de código com sua lógica real de banco de dados
        // Este é apenas um exemplo:
        $id_usuario = null;
        $query = "SELECT id FROM usuario WHERE stateid = '$usuario'";
        $resultado = mysqli_query($conexao, $query);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            $id_usuario = $linha['id'];
        }
        return $id_usuario;
    }
}

?>
