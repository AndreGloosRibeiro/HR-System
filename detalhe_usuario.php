<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nome_do_banco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para obter todos os registros da tabela Funcionarios
$sql = "SELECT * FROM Funcionarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Usuário</title>
    <link rel="stylesheet" href="css/detalhe_usuario.css">
</head>
<body>

<header>
    <div class="back-button">
        <a href="pagina_principal.php">
            <img src="IMG/backbutton_104978 branco.png" alt="Voltar pra tela inicial">
        </a>
    </div>
    <h1>Detalhes do Usuário</h1>
</header>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="user-details">';
            echo '<div class="user-image">';
            if (!empty($row["foto_path"]) && file_exists("fotos/" . $row["foto_path"])) {
                echo '<img src="fotos/' . $row["foto_path"] . '" alt="Foto do Usuário">';
            }
            echo '</div>';
            echo '<div class="user-info">';
            echo "<p><strong>Nome:</strong> " . $row["nome"] . "</p>";
            echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
            echo "<p><strong>Cargo:</strong> " . $row["cargo"] . "</p>";
            echo '</div>';
            echo '<a class="ver-detalhes-btn" href="mais_detalhes.php?id=' . $row["id"] . '">Ver Detalhes</a>';
            echo '</div>';
        }
    } else {
        echo "Nenhum usuário encontrado.";
    }
    ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
