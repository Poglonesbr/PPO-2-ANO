<?php

// Conexão com o banco de dados
try {
    $conn = new PDO("mysql:host=localhost;dbname=seu_banco_de_dados", "seu_usuario", "sua_senha");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $comentarioTexto = $_POST['comentario'];
    $idusuario = $_POST['idusuario']; // Assumindo que o ID do usuário é enviado via formulário

    // Insere o comentário no banco de dados
    try {
        $query = "INSERT INTO comentarios (comentario, idusuario) VALUES (:comentario, :idusuario)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':comentario' => $comentarioTexto,
            ':idusuario' => $idusuario
        ]);

        echo "Comentário inserido com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao inserir comentário: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários</title>
    <style>
        /* Estilo geral */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h2 {
            color: #00b894;
        }

        /* Formulário de comentários */
        .comentario-form {
            width: 100%;
            max-width: 600px;
            margin-bottom: 20px;
        }

        .comentario-form textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #00b894;
            border-radius: 5px;
            background-color: #000;
            color: #fff;
            resize: vertical;
        }

        .comentario-form button {
            background-color: #00b894;
            color: #000;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .comentario-form button:hover {
            background-color: #0984e3;
        }

        /* Exibição de comentários */
        .comentarios {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }

        .comentario {
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid #00b894;
            border-radius: 5px;
            background-color: #000;
        }

        .comentario h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #00b894;
        }

        .comentario p {
            margin: 5px 0;
        }

        .comentario small {
            color: #b2bec3;
        }

        /* Respostas */
        .resposta {
            margin-top: 10px;
            margin-left: 20px;
            border-left: 2px solid #00b894;
            padding-left: 15px;
        }

        .resposta h4 {
            margin: 0 0 5px;
            font-size: 16px;
            color: #74b9ff;
        }

        .resposta-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #00b894;
            border-radius: 5px;
            background-color: #000;
            color: #fff;
            resize: vertical;
        }

        .resposta-form button {
            background-color: #74b9ff;
            color: #000;
            border: none;
            padding: 5px 15px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .resposta-form button:hover {
            background-color: #00b894;
        }
    </style>
</head>
<body>
    <h2>Deixe seu comentário</h2>
    <form class="comentario-form" method="post" action="">
        <input type="hidden" name="action" value="comentario">
        <textarea name="comentario" rows="4" required placeholder="Digite seu comentário..."></textarea>
        <button type="submit">Comentar</button>
    </form>

    <div class="comentarios">
        <?php
      $stmt = $conn->query("SELECT c.*, u.nomeUs FROM comentarios c JOIN usuario u ON c.idusuario = u.idusuario ORDER BY c.data DESC");
      while ($comentario = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<div class='comentario'>";
          echo "<h3>{$comentario['nomeUs']}</h3>"; // Exibe o nome do usuário
          echo "<p>{$comentario['comentario']}</p>";
          echo "<small>Comentado em: {$comentario['data']}</small>";
      
          // Exibe as respostas para o comentário
          $stmt2 = $conn->prepare("SELECT r.*, u.nomeUs FROM respostas r JOIN usuario u ON r.idusuario = u.idusuario WHERE r.idcomentario = :idcomentario ORDER BY r.data ASC");
          $stmt2->execute(['idcomentario' => $comentario['id']]);
          while ($resposta = $stmt2->fetch(PDO::FETCH_ASSOC)) {
              echo "<div class='resposta'>";
              echo "<h4>{$resposta['nomeUs']} respondeu:</h4>"; // Exibe o nome do usuário que respondeu
              echo "<p>{$resposta['resposta']}</p>";
              echo "<small>Respondido em: {$resposta['data']}</small>";
              echo "</div>";
          }
      }
        ?>
    </div>
</body>
</html>
