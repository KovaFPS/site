<header>
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #1C1C1C; color: white">
        <button class="navbar-toggler" style="border: 2px solid white; color: white;" type="button" data-toggle="collapse" data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Alterna navegação">
            <span class="navbar-toggler-icon"><i class="fas fa-bars p-1"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
            <div class="container d-flex justify-content-between">
                <ul class="navbar-nav d-none d-lg-block" style="color: white;">
                    <li class="nav-item mt-2">
                        La Puerta
                    </li>
                </ul>
                <ul class="navbar-nav" id="navegacao">

                    <li class="nav-item px-2">
                        <a class="nav-link" href="index.php" style="color: white;">
                            <i class="fas fa-home"></i> HOME
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" style="cursor: pointer" aria-haspopup="true" aria-expanded="false">
                            CADASTRO
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown" id="dropdown">
                            <a class="dropdown-item" href="cad_usuario.php">Usuário</a>
                            <a class="dropdown-item" href="cad_cliente.php">Cliente</a>
                            <a class="dropdown-item" href="cad_produto.php">Produto</a>

                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" style="cursor: pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            CONSULTA
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown" id="dropdown">
                            <a class="dropdown-item" href="lista_usuario.php">Usuário</a>
                            <a class="dropdown-item" href="lista_cliente.php">Cliente</a>
                            <a class="dropdown-item" href="lista_produto.php">Produto</a>
                            <a class="dropdown-item" href="lista_farm.php">Farm</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" style="cursor: pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            RELATÓRIO
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown" id="dropdown">
                            <a class="dropdown-item" target="_blank" href="relatorio_venda.php">Venda</a>
                            <a class="dropdown-item" target="_blank" href="relatorio_farm.php">Farm</a>
                            <a class="dropdown-item" target="_blank" href="lista_log.php">Log</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" style="cursor: pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            MOVIMENTAÇÕES
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown" id="dropdown">
                            <a class="dropdown-item" href="cad_farm.php">Farm</a>
                            <a class="dropdown-item" href="cad_lavagem.php">Lavagem de Dinheiro</a>
                            <a class="dropdown-item" href="cad_saida_venda.php">Vendas</a>
                            <a class="dropdown-item" href="cad_saida_financa.php">Compras</a>
                            <a class="dropdown-item" href="cad_saida_uso.php">Retirada para Uso</a>
                        </div>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="logout.php" style="color: white;">
                            SAIR
                        </a>
                </ul>
                <ul class="navbar-nav d-none d-lg-block" style="color: white;">
                    <li class="nav-item px-1">
                        <i class="fab fa-php h1 px-1" style="color: white;"> </i>
                    </li>
                </ul>
            </div>
        </div>

    </nav>

</header>