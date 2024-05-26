<?php
include("conexao.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro de Entrada por Compra</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    <form method="post" action="efetuar_cad_compra.php">
        <label for="comprador">Comprador:</label>
        <input type="text" id="comprador" name="comprador" required>

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

        <label for="valor_unitario">Valor Unitário:</label>
        <input type="number" step="0.01" id="valor_unitario" name="valor_unitario" required>

        <p>Total: <span id="total">0.00</span></p>

        <button type="submit">Registrar</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#quantidade, #valor_unitario').on('input', function() {
                var quantidade = parseFloat($('#quantidade').val()) || 0;
                var valorUnitario = parseFloat($('#valor_unitario').val()) || 0;
                var total = quantidade * valorUnitario;
                $('#total').text(total.toFixed(2));
            });
        });
    </script>
</body>
</html>
