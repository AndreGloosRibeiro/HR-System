<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o usuário e a senha são válidos
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === "Admin" && $password === "Admin123") {
        // Redireciona para a página principal ou realiza alguma ação desejada
        header("Location: pagina_principal.php");
        exit();
    } else {
        // Exibe mensagem de erro
        $error_message = "Usuário ou senha incorretos.";
    }
}
?>
