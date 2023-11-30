<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Usuário</title>
    <link rel="stylesheet" href="css/mais_detalhes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
</head>
<body>

<header>
    <!-- Adicione o botão de voltar -->
    <div class="back-button">
        <a href="javascript:history.back()">
            <img src="IMG/backbutton_104978 branco.png" alt="Voltar">
        </a>
    </div>
    <h1>Detalhes do Usuário</h1>
</header>

<div class="container">
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
    require_once('calendario_events.php');
    // Verifica se o parâmetro de ID foi passado na URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consulta SQL para obter os detalhes do funcionário com base no ID
        $sql = "SELECT * FROM Funcionarios WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Saída de dados de cada linha
            while ($row = $result->fetch_assoc()) {
                echo "<div class='user-details'>";
                echo "<div class='user-image'>";
                if (!empty($row["foto_path"]) && file_exists("fotos/" . $row["foto_path"])) {
                    echo "<img src='fotos/" . $row["foto_path"] . "' alt='Foto do Usuário'>";
                }
                echo "</div>";
                echo "<div class='user-info'>";
                echo "<p><strong>Nome:</strong> " . $row["nome"] . "</p>";
                echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
                echo "<p><strong>Sexo:</strong> " . $row["sexo"] . "</p>";
                echo "<p><strong>Data de Nascimento:</strong> " . $row["data_nascimento"] . "</p>";
                echo "<p><strong>Telefone:</strong> " . $row["telefone"] . "</p>";
                echo "<p><strong>Rua:</strong> " . $row["rua"] . "</p>";
                echo "<p><strong>Bairro:</strong> " . $row["bairro"] . "</p>";
                echo "<p><strong>Cidade:</strong> " . $row["cidade"] . "</p>";
                echo "<p><strong>Estado:</strong> " . $row["estado"] . "</p>";
                echo "<p><strong>Salário:</strong> R$ " . number_format($row["salario"], 2, ',', '.') . "</p>";
                echo "<p><strong>Contrato:</strong></p>"; 
                echo "<embed src='" . $row["contrato_assinado_path"] . "' type='application/pdf' width='700px' height='1000px' />";
                echo "<h1><strong>Calendário:</strong></h1>";
                // Adiciona o script para inicializar o FullCalendar com os eventos
                echo "<div id='calendario' class='calendario'>";

                    // Adiciona o script para inicializar o FullCalendar com os eventos
                    echo "<script>";
                    echo "$(document).ready(function() {";
                    echo "$('#calendario').fullCalendar({";
                    echo "events: " . json_encode(obterEventosCalendario($row['tipo_folga'])) . ",";
                    echo "header: {";
                    echo "left: 'prev,next today',";
                    echo "center: 'title',";
                    echo "right: 'month,agendaWeek,agendaDay'";
                    echo "}";
                    echo "});";
                    echo "});";
                    echo "</script>";
                echo "</div>";
            }
        } else {
            echo "Nenhum usuário encontrado com o ID fornecido.";
        }
    } else {
        echo "ID não fornecido na URL.";
    }

    $conn->close();
    ?>
</div>
</body>
</html>