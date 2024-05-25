<?php
include("logica-usuario.php");

verificaUsuario();

if ($_SESSION['usuario']['perfil'] != 'admin') {
    die('Você não tem permissão para realizar esta ação.');
}

include("conexao.php");

$id = $_POST['id'];

$sql = "DELETE FROM farm WHERE id = '$id'";
$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    die('Query Inválido: ' . mysqli_error($conexao));
} else {
    echo "<script>alert('Registro de farm excluído com sucesso!'); window.location.href='lista_farm.php';</script>";
}
mysqli_close($conexao);
?>
