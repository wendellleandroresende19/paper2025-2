<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal_noticias";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Captura o termo de busca (GET)
$busca = $_GET['q'] ?? '';

$result = null;
if ($busca !== '') {
    $sql = "SELECT id, titulo, conteudo, imagem_url, data_publicacao
            FROM noticias
            WHERE titulo LIKE ?
            ORDER BY data_publicacao DESC";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $like = "%".$busca."%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        // Se por algum motivo der erro em prepare, não deixa a página sumir
        $result = false;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pesquisar notícias</title>
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

    <!-- barra de busca -->
    <form method="GET" action="buscar.php" class="search-bar">
        <input
            type="text"
            name="q"
            placeholder="Digite parte do título..."
            value="<?php echo htmlspecialchars($busca); ?>"
        >
        <button type="submit" class="btn-primary">Buscar</button>
    </form>

    <?php
    // estado inicial: sem buscar ainda
    if ($busca === '') {
        echo "<div class='msg-status'>Digite um termo acima para procurar notícias pelo título.</div>";

    // erro inesperado na query
    } else if ($result === false) {
        echo "<div class='msg-status'>Erro ao realizar a busca.</div>";

    // resultados encontrados
    } else if ($result && $result->num_rows > 0) {
        echo "<section class='noticias-grid'>";
        while ($row = $result->fetch_assoc()) {
            echo "<article class='card-noticia'>";

            if (!empty($row['imagem_url'])) {
                echo "<img src='" . htmlspecialchars($row['imagem_url']) . "' alt='Imagem da notícia'>";
            }

            echo "<h2>" . htmlspecialchars($row['titulo']) . "</h2>";
            echo "<time>" . $row['data_publicacao'] . "</time>";

            $previa = mb_substr($row['conteudo'], 0, 200);
            if (mb_strlen($row['conteudo']) > 200) {
                $previa .= "...";
            }
            echo "<p>" . htmlspecialchars($previa) . "</p>";

            echo "<a class='btn-outline' href='noticia.php?id=" . $row['id'] . "'>Ler mais</a>";

            echo "</article>";
        }
        echo "</section>";

    } else {
        echo "<div class='msg-status'>Nenhuma notícia encontrada com esse termo.</div>";
    }
    ?>

</main>

<footer class="site-footer">
    <p>Projeto acadêmico • Pesquisa de notícias • PHP + MySQL</p>
</footer>

</body>
</html>
