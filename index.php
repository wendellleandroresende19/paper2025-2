<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // padrão do XAMPP
$dbname = "portal_noticias";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica erro de conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta todas as notícias
$sql = "SELECT id, titulo, conteudo, imagem_url, data_publicacao
        FROM noticias
        ORDER BY data_publicacao DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Portal de Notícias</title>
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

    <?php
    if ($result === false) {
        echo "<div class='msg-status'>Erro na consulta SQL.</div>";
    } else if ($result->num_rows > 0) {
        echo "<section class='noticias-grid'>";
       while ($row = $result->fetch_assoc()) {

    echo "<article class='card-noticia'>";

    // imagem clicável também
    if (!empty($row['imagem_url'])) {
        echo "<a href='noticia.php?id=" . $row['id'] . "' style='text-decoration:none; color:inherit;'>";
        echo "<img src='" . htmlspecialchars($row['imagem_url']) . "' alt='Imagem da notícia'>";
        echo "</a>";
    }

    // título vira link clicável
    echo "<h2 style='margin-bottom:4px;'>";
    echo "<a href='noticia.php?id=" . $row['id'] . "' style='text-decoration:none; color:inherit;'>";
    echo htmlspecialchars($row['titulo']);
    echo "</a>";
    echo "</h2>";

    // data
    $dataFormatada = date("d/m/Y H:i", strtotime($row['data_publicacao']));
    echo "<time>" . $dataFormatada . "</time>";

    // resumo do conteúdo
    $previa = mb_substr($row['conteudo'], 0, 200);
    if (mb_strlen($row['conteudo']) > 200) {
        $previa .= "...";
    }
    echo "<p>" . htmlspecialchars($previa) . "</p>";

    // botão 'Ler mais'
    echo "<a class='btn-outline' href='noticia.php?id=" . $row['id'] . "'>Ler mais</a>";

    echo "</article>";
}

        echo "</section>";
    } else {
        echo "<div class='sem-noticias'>Nenhuma notícia cadastrada ainda.</div>";
    }

    $conn->close();
    ?>

</main>

<footer class="site-footer">
    <p>Projeto acadêmico • Portal de Notícias • Desenvolvido em PHP + MySQL</p>
</footer>

</body>
</html>
