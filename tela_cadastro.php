<?php
require_once('tcpdf/tcpdf.php');

function gerarContratoPDF($dados, $caminho_pasta) {
    // Gera um nome único para o arquivo PDF
    $nome_arquivo = uniqid('contrato_') . '.pdf';

    // Caminho relativo do contrato
    $caminho_contrato = "contratos/{$nome_arquivo}";

    // Caminho completo do arquivo
    $caminho_arquivo = "{$caminho_pasta}/{$caminho_contrato}";

    // Cria uma instância do TCPDF
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Contrato de Trabalho', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 10, 'Eu, ' . $dados['nome'] . ', aceito as condições do contrato', 0, 'L');

    // Salva o arquivo no caminho especificado
    $pdf->Output($caminho_arquivo, 'F');

    // Retorna o caminho relativo do contrato gerado
    return $caminho_contrato;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sexo = $_POST['sexo'];
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cargo = $_POST['cargo'];
    $salario = $_POST['salario'];
    $tipo_folga = $_POST['tipo_folga'];

    // Tratamento da imagem
    $nome_imagem = $_FILES['foto']['name'];
    $caminho_imagem_temp = $_FILES['foto']['tmp_name'];
    $caminho_imagem_final = "fotos/{$nome_imagem}";

    // Movendo a imagem para o diretório correto
    if (move_uploaded_file($caminho_imagem_temp, $caminho_imagem_final)) {
        
    } else {
        
    }

    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nome_do_banco";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Verifica se o botão "Gerar Contrato em PDF" foi pressionado
    if (isset($_POST['gerar_contrato'])) {
        // Gera contrato em PDF e retorna o caminho relativo do arquivo
        $caminho_pasta = dirname(__FILE__); // Obtém o diretório atual do script
        $caminho_contrato_assinado = gerarContratoPDF($_POST, $caminho_pasta);
        $id_inserido = $conn->insert_id;

        // Atualiza o caminho do contrato assinado no banco de dados
        $sql_update = "UPDATE Funcionarios SET contrato_assinado_path = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $caminho_contrato_assinado, $id_inserido);

        if ($stmt_update->execute()) {
    
        } else {
            echo "" . $stmt_update->error;
        }

        $stmt_update->close();
    }

    // Insere os dados na tabela
    $sql = "INSERT INTO Funcionarios (nome, sexo, data_nascimento, telefone, email, rua, bairro, cidade, estado, cargo, salario, tipo_folga, foto_path, contrato_assinado_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssdsss", $nome, $sexo, $data_nascimento, $telefone, $email, $rua, $bairro, $cidade, $estado, $cargo, $salario, $tipo_folga, $nome_imagem, $caminho_contrato_assinado);

    if ($stmt->execute()) {

    } else {
        echo " " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/cadastro.css">
    <title>Tela de Cadastro</title>
</head>
<body>
    
    <div class="container">
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // ... Seu código de cadastro ...

            // Adicione o seguinte código para exibir o símbolo e o botão após o cadastro bem-sucedido
            echo '<div class="alert alert-success" role="alert">';
            echo '<h4 class="alert-heading">Cadastro realizado com sucesso!</h4>';
            echo '<hr>';
            echo '<a href="pagina_principal.php" class="btn btn-primary">Voltar para a tela principal</a>';
            echo '</div>';
        }
        ?>
        <a href="pagina_principal.php" class="btn btn-secondary back-button">
            <img src="IMG/backbutton_104978.png" alt="Voltar pra tela inicial">
        </a>
        <form action="#" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>

            <label>Sexo:</label>
            <label class="radio-container"><input type="radio" name="sexo" value="Masculino" required><span>M</span></label>
            <label class="radio-container"><input type="radio" name="sexo" value="Feminino" required><span>F</span></label>

            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto" accept="image/*" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" required>

            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" required>

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" required>

            <label for="estado">Estado:</label>
                <select id="estado" name="estado" class="form-control" required>
                    <option value="" selected disabled>Selecione o Estado</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                </select>

            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" required>

            <label for="salario">Salário:</label>
            <input type="number" id="salario" name="salario" step="0.01" required>

            <label>Folga:</label>
            <label class="radio-container"><input type="radio" name="tipo_folga" value="6 por 1" required><span>6/1</span></label>
            <label class="radio-container"><input type="radio" name="tipo_folga" value="12 por 4" required><span>12/4</span></label>
            <label class="radio-container"><input type="radio" name="tipo_folga" value="1 por 1" required><span>1/1</span></label>
            <!-- Checkbox para gerar contrato em PDF -->
            <label><input type="checkbox" name="gerar_contrato"> Gerar Contrato em PDF</label>

            <input type="submit" value="Cadastrar">
        </form>
        <a href="pagina_principal.php" class="btn btn-secondary">Voltar para a tela inicial</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Mostra/oculta o campo de upload do contrato assinado ao marcar/desmarcar a caixa de gerar contrato
        const checkboxGerarContrato = document.querySelector('input[name="gerar_contrato"]');
        const campoContratoAssinado = document.getElementById('contrato-assinado');

        checkboxGerarContrato.addEventListener('change', function() {
            campoContratoAssinado.style.display = this.checked ? 'block' : 'none';
        });
    </script>
</body>
</html>