<?php
// Configurações de conexão ao banco de dados MySQL
$host = "localhost";
$user = "seu_usuario";
$password = "sua_senha";
$dbname = "museu";

try {
    // Criando a conexão usando PDO
    $conn = new PDO("mysql:host=$host", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criando o banco de dados
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);
    echo "Banco de dados criado com sucesso!<br>";

    // Selecionando o banco de dados
    $conn->exec("USE $dbname");

    // Criando a tabela 'obras'
    $sql = "CREATE TABLE IF NOT EXISTS obras (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(100) NOT NULL,
        artista VARCHAR(100) NOT NULL,
        ano YEAR NOT NULL,
        descricao TEXT
    )";
    $conn->exec($sql);
    echo "Tabela 'obras' criada com sucesso!<br>";

    // --- CREATE: Inserir Dados ---
    $sql = "INSERT INTO obras (titulo, artista, ano, descricao) VALUES (:titulo, :artista, :ano, :descricao)";
    $stmt = $conn->prepare($sql);
    $titulo = "Mona Lisa";
    $artista = "Leonardo da Vinci";
    $ano = 1503;
    $descricao = "Um dos retratos mais famosos do mundo.";
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':artista', $artista);
    $stmt->bindParam(':ano', $ano);
    $stmt->bindParam(':descricao', $descricao);

    if ($stmt->execute()) {
        echo "Nova obra inserida com sucesso!<br>";
    } else {
        echo "Erro ao inserir obra!<br>";
    }

    // --- READ: Selecionar Dados ---
    $sql = "SELECT id, titulo, artista, ano, descricao FROM obras";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        foreach ($result as $row) {
            echo "ID: " . $row["id"] . " - Título: " . $row["titulo"] . " - Artista: " . $row["artista"] . " - Ano: " . $row["ano"] . " - Descrição: " . $row["descricao"] . "<br>";
        }
    } else {
        echo "0 resultados<br>";
    }

    // --- UPDATE: Atualizar Dados ---
    $sql = "UPDATE obras SET titulo = :novo_titulo WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $novo_titulo = "Mona Lisa Atualizada";
    $id_para_atualizar = 1; // ID do registro que será atualizado
    $stmt->bindParam(':novo_titulo', $novo_titulo);
    $stmt->bindParam(':id', $id_para_atualizar);

    if ($stmt->execute()) {
        echo "Obra atualizada com sucesso!<br>";
    } else {
        echo "Erro ao atualizar obra!<br>";
    }

    // --- DELETE: Deletar Dados ---
    $sql = "DELETE FROM obras WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $id_para_deletar = 1; // ID do registro que será deletado
    $stmt->bindParam(':id', $id_para_deletar);

    if ($stmt->execute()) {
        echo "Obra deletada com sucesso!<br>";
    } else {
        echo "Erro ao deletar obra!<br>";
    }

} catch(PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}

// Fechar a conexão
$conn = null;
?>