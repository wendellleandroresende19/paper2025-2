<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// pega os dados enviados pelo formulário
$titulo = $_POST['titulo'] ?? '';
$imagem_url = $_POST['imagem_url'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';

// conecta no banco
$servername = "localhost";
$username = "root";
$password = ""; // vazio no XAMPP padrão
$dbname = "portal_noticias";

$conn = new mysqli($servername, $username, $password, $dbname);

// se der erro de conexão:
if ($conn->connect_error) {
    $mensagem = "Falha na conexão com o banco: " . $conn->connect_error;
} else {
    // monta o INSERT
    $sql = "INSERT INTO noticias (titulo, conteudo, imagem_url, data_publicacao)
            VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $mensagem = "Erro ao preparar inserção: " . $conn->error;
    } else {
        $stmt->bind_param("sss", $titulo, $conteudo, $imagem_url);

        if ($stmt->execute()) {
            $mensagem = "Notícia salva com sucesso!";
        } else {
            $mensagem = "Erro ao salvar: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Notícia salva</title>
    <link rel="stylesheet" href="style.css">
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
    <section class="form-card" style="text-align:center;">
        <h2><?php echo htmlspecialchars($mensagem); ?></h2>
        <div class="actions-row" style="justify-content:center;">
            <a class="btn-primary" href="index.php">Ver notícias</a>
            <a class="btn-outline" href="nova_noticia.php">Cadastrar outra</a>
        </div>
    </section>
</main>

<footer class="site-footer">
    <p>Projeto acadêmico • Publicação registrada no banco de dados</p>
</footer>

</body>
</h
