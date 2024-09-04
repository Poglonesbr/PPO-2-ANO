<?php
// Configurações de conexão ao banco de dados MySQL
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bancoifc";

try {
    // Opções adicionais do PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Criando a conexão usando PDO com as opções adicionais
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password, $options);

} catch(PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
    exit; // Interrompe a execução caso a conexão falhe
}
?>
