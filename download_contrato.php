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

// Verifica se o parâmetro de ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para obter o caminho do contrato com base no ID
    $sql = "SELECT contrato_assinado_path FROM Funcionarios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $caminho_contrato = $row["contrato_assinado_path"];

        // Verifica se o caminho do contrato existe
        if (!empty($caminho_contrato) && file_exists($caminho_contrato)) {
            // Força o download do arquivo
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($caminho_contrato) . '"');
            readfile($caminho_contrato);
        } else {
            echo "Contrato não encontrado.";
        }
    } else {
        echo "Nenhum usuário encontrado com o ID fornecido.";
    }
} else {
    echo "ID não fornecido na URL.";
}

$conn->close();
?>
