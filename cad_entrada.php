<?php
include("logica-usuario.php");
verificaUsuario();
include("conexao.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada (Compra)</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Registrar Entrada (Compra)</h2>
        <form action="efetuar_cad_entrada.php" method="POST">
            <div class="form-group">
                <label for="id_fornecedor">Fornecedor</label>
                <select class="form-control" name="id_fornecedor" required>
                    <?php
                    $result = mysqli_query($conexao, "SELECT id, nome FROM fornecedor");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data_entrada">Data da Entrada</label>
                <input type="datetime-local" class="form-control" name="data_entrada" required>
            </div>
            <div class="form-group">
                <label for="observacao">Observação</label>
                <textarea class="form-control" name="observacao" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="produtos">Produtos</label>
                <div id="produtos-container">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="produto_id">Produto</label>
                            <select class="form-control" name="produtos[0][id_produto]" required>
                                <?php
                                $result = mysqli_query($conexao, "SELECT id, nome_produto FROM produto");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['nome_produto']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" name="produtos[0][quantidade]" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="preco">Preço</label>
                            <input type="text" class="form-control" name="produtos[0][preco]" required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="add-produto">Adicionar Produto</button>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <script>
        document.getElementById('add-produto').addEventListener('click', function () {
            var container = document.getElementById('produtos-container');
            var index = container.children.length;
            var row = document.createElement('div');
            row.className = 'form-row';
            row.innerHTML = `
                <div class="form-group col-md-6">
                    <label for="produto_id">Produto</label>
                    <select class="form-control" name="produtos[${index}][id_produto]" required>
                        <?php
                        $result = mysqli_query($conexao, "SELECT id, nome_produto FROM produto");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}'>{$row['nome_produto']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="quantidade">Quantidade</label>
                    <input type="number" class="form-control" name="produtos[${index}][quantidade]" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="preco">Preço</label>
                    <input type="text" class="form-control" name="produtos[${index}][preco]" required>
                </div>
            `;
            container.appendChild(row);
        });
    </script>
</body>
</html>
