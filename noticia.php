<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal_noticias";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Pega o id da notícia que vem pela URL 
$id = $_GET['id'] ?? 0;
$id = intval($id);

// Busca os dados da notícia
$sql = "SELECT titulo, conteudo, imagem_url, data_publicacao
        FROM noticias
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>
        <?php echo $noticia ? htmlspecialchars($noticia['titulo']) : "Notícia não encontrada"; ?>
    </title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>

<header class="site-header">
    <div class="content">

        <!-- Logo clicável que volta pra home -->
        <a class="brand" href="index.php" style="text-decoration:none; color:inherit;">
            <div class="brand-logo">IN</div>
            <div class="brand-name">
                <span>Infinity News</span>
                <span class="sub">Atualização em tempo real</span>
            </div>
        </a>

        <!-- Navegação -->
        <nav>
            <a class="btn alt" href="buscar.php">Pesquisar</a>
            <a class="btn" href="nova_noticia.php">+ Nova notícia</a>
        </nav>

    </div>
</header>


<main class="page">
    <?php if ($noticia): ?>
        <article class="form-card" style="max-width:800px;">

            <?php if (!empty($noticia['imagem_url'])): ?>
                <img
                    src="<?php echo htmlspecialchars($noticia['imagem_url']); ?>"
                    alt="Imagem da notícia"
                    style="width:100%; max-height:400px; object-fit:cover; border-radius:12px; margin-bottom:20px; border:1px solid #ccc;"
                >
            <?php endif; ?>

            <h2 style="font-size:1.4rem; line-height:1.3; font-weight:600; margin-bottom:8px;">
                <?php echo htmlspecialchars($noticia['titulo']); ?>
            </h2>

            <p style="color:#666; font-size:0.85rem; margin-bottom:20px;">
                Publicada em
                <?php echo date("d/m/Y H:i", strtotime($noticia['data_publicacao'])); ?>
            </p>

            <p style="white-space:pre-line; font-size:1rem; line-height:1.7; color:#1a1a1a;">
                <?php echo htmlspecialchars($noticia['conteudo']); ?>
            </p>

            <!-- Bloco de ações -->
            <div class="actions-row" style="margin-top:24px; flex-wrap:wrap;">

                <a class="btn-outline" href="index.php">
                    ← Voltar para página inicial
                </a>

                <a class="btn-outline" href="buscar.php">
                    Fazer outra busca
                </a>

                <!-- Botão: excluir notícia -->
                <a
                    class="btn-primary"
                    style="background-color:#b30000; border-color:#b30000; box-shadow:0 10px 20px rgba(179,0,0,0.4);"
                    href="excluir_noticia.php?id=<?php echo $id; ?>"
                    onclick="return confirm('Tem certeza que deseja excluir esta notícia? Essa ação não pode ser desfeita.');"
                >
                    Excluir notícia
                </a>

            </div>

        </article>
    <?php else: ?>
        <div class="msg-status">Notícia não encontrada.</div>
    <?php endif; ?>
</main>

<footer class="site-footer">
    <p>Projeto acadêmico • Leitura de notícia completa • PHP + MySQL</p>
</footer>

</body>
</html>
