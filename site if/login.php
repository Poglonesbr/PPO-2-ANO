<?php
session_start();

// Inclua o arquivo de conexão com o banco de dados
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'login') {
        $gmail = $_POST['gmail'];
        $senha = $_POST['senha'];

        try {
            // Verificando as credenciais do usuário
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE gmail = :gmail");
            $stmt->execute(['gmail' => $gmail]);

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificando a senha (agora comparando com a senha em texto puro)
                if ($senha == $user['senha']) {
                    // Iniciar a sessão e armazenar as informações do usuário
                    $_SESSION['idusuario'] = $user['idusuario'];
                    $_SESSION['usuario_nome'] = $user['nomeUs'];

                    header("Location: perguntas.php");
                    exit();
                } else {
                    $errorLogin = "E-mail ou senha incorretos.";
                }
            } else {
                $errorLogin = "E-mail ou senha incorretos.";
            }
        } catch (PDOException $e) {
            $errorLogin = "Erro ao tentar fazer login: " . $e->getMessage();
        }
    } elseif ($_POST['action'] == 'cadastro') {
        $nomeUs = $_POST['nomeUs'];
        $gmail = $_POST['gmail'];
        $senha = $_POST['senha'];  // Senha será salva em texto puro

        try {
            // Verifica se o e-mail já está registrado
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE gmail = :gmail");
            $stmt->execute(['gmail' => $gmail]);

            if ($stmt->rowCount() > 0) {
                $errorCadastro = "Este e-mail já está registrado.";
                $showCadastro = true;  // Mostrar o formulário de cadastro se houver erro
            } else {
                // Inserindo o novo usuário no banco de dados com a senha em texto puro
                $stmt = $conn->prepare("INSERT INTO usuario (nomeUs, gmail, senha) VALUES (:nomeUs, :gmail, :senha)");
                $stmt->execute(['nomeUs' => $nomeUs, 'gmail' => $gmail, 'senha' => $senha]);

                // Redireciona para o login se o cadastro for bem-sucedido
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $errorCadastro = "Erro ao cadastrar: " . $e->getMessage();
            $showCadastro = true;  // Mostrar o formulário de cadastro se houver erro
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos gerais */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #2d3436;
            overflow: hidden;
        }

        /* Container principal de login e cadastro */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            position: absolute;
            transition: transform 0.6s ease, opacity 0.6s ease;
            opacity: 0;
            transform: translateY(100vh);
        }

        /* Mostrar container */
        .show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Esconder container para fora da tela */
        .hide {
            transform: translateY(100vh);
        }

        /* Título */
        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #0984e3;
        }

        /* Labels */
        .container label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 15px;
        }

        /* Campos de entrada */
        .container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #dfe6e9;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s;
        }

        .container input:focus {
            border-color: #74b9ff;
            outline: none;
        }

        /* Botões de ação */
        .container button {
            width: 100%;
            padding: 12px;
            background-color: #0984e3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }

        .container button:hover {
            background-color: #74b9ff;
            transform: scale(1.05);
        }

        .container button:active {
            background-color: #0984e3;
            transform: scale(0.98);
        }

        /* Link para alternar entre login e cadastro */
        .toggle-link {
            margin-top: 15px;
            display: block;
            font-size: 14px;
            color: #0984e3;
            text-decoration: none;
            cursor: pointer;
        }

        .toggle-link:hover {
            text-decoration: underline;
        }

        /* Mensagem de erro */
        .error {
            color: #d63031;
            margin-top: 10px;
            font-weight: bold;
        }

        /* Estilo para dispositivos móveis */
        @media (max-width: 400px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .container h2 {
                font-size: 20px;
            }

            .container button {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div id="login-container" class="container <?php echo isset($showCadastro) && $showCadastro ? 'hide' : 'show'; ?>">
        <h2>Login</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="login">
            <label for="gmail">e-mail:</label>
            <input type="email" id="gmail" name="gmail" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit">Logar</button>
        </form>
        <?php if (isset($errorLogin)) { echo "<p class='error'>$errorLogin</p>"; } ?>
        <!-- Link para cadastro -->
        <span class="toggle-link" onclick="toggleForms()">Cadastre-se</span>
    </div>

    <div id="cadastro-container" class="container <?php echo isset($showCadastro) && $showCadastro ? 'show' : 'hide'; ?>">
        <h2>Cadastro</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="cadastro">
            <label for="nomeUs">Nome:</label>
            <input type="text" id="nomeUs" name="nomeUs" required>

            <label for="gmail">e-mail:</label>
            <input type="email" id="gmail" name="gmail" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit">Cadastrar</button>
        </form>
        <?php if (isset($errorCadastro)) { echo "<p class='error'>$errorCadastro</p>"; } ?>
        <!-- Link para login -->
        <span class="toggle-link" onclick="toggleForms()">Já tem uma conta? Faça login</span>
    </div>

    <script>
        function toggleForms() {
            var loginContainer = document.getElementById('login-container');
            var cadastroContainer = document.getElementById('cadastro-container');

            if (loginContainer.classList.contains('show')) {
                loginContainer.classList.remove('show');
                loginContainer.classList.add('hide');
                cadastroContainer.classList.remove('hide');
                setTimeout(function() {
                    cadastroContainer.classList.add('show');
                }, 600);
            } else {
                cadastroContainer.classList.remove('show');
                cadastroContainer.classList.add('hide');
                loginContainer.classList.remove('hide');
                setTimeout(function() {
                    loginContainer.classList.add('show');
                }, 600);
            }
        }
    </script>
</body>
</html>
