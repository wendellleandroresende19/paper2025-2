<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar notícia</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validarFormulario() {
            const titulo = document.getElementById('titulo');
            const conteudo = document.getElementById('conteudo');
            const erro = document.getElementById('erro');

            if (titulo.value.trim() === '' || conteudo.value.trim() === '') {
                erro.style.display = 'block';
                erro.textContent = 'Preencha pelo menos Título e Conteúdo.';
                return false;
            }

            erro.style.display = 'none';
            return true;
        }
    </script>
</head>
<body>

<header class="site-header">
    <div class="content">

        <a class="brand" href="index.php" style="text-decoration:none; color:inherit;">
            <div class="brand-logo">IN</div>
            <div class="brand-name">
                <span>Infinity News</span>
                <span class="sub">Atualização em tempo real</span>
            </div>
        </a>

        <nav>
            <a class="btn alt" href="buscar.php">Pesquisar</a>
            <a class="btn" href="nova_noticia.php">+ Nova notícia</a>
        </nav>

    </div>
</header>



<main class="page">

    <section class="form-card">
        <h2>Publicar notícia</h2>
        <p class="descricao">Preencha os campos abaixo e clique em "Salvar notícia".</p>

        <div id="erro" class="form-erro"></div>

        <form action="salvar_noticia.php" method="POST" onsubmit="return validarFormulario();">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label for="imagem_url">Imagem (URL)</label>
                <input type="text" id="imagem_url" name="imagem_url" placeholder="https://exemplo.com/imagem.jpg">
            </div>

            <div class="form-group">
                <label for="conteudo">Conteúdo</label>
                <textarea id="conteudo" name="conteudo" rows="6" required></textarea>
            </div>

            <div class="actions-row">
                <button class="btn-primary" type="submit">Salvar notícia</button>
                <a class="btn-outline" href="index.php">Cancelar</a>
            </div>
        </form>
    </section>

</main>

<footer class="site-footer">
    <p>Projeto acadêmico • Portal de Notícias • Cadastro de conteúdo</p>
</footer>

</body>
</html>
