<?php
include("conexao.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro de Saída por Uso</title>
</head>
<body>
    <form method="post" action="efetuar_cad_uso.php">
        <label for="produto_id">Produto:</label>
        <select id="produto_id" name="produto_id" required>
            <?php
            $sql = "SELECT id, nome_produto FROM produto";
            $result = mysqli_query($conexao, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['nome_produto']}</option>";
            }
            ?>
        </select>

        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" required>

        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" required>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>
