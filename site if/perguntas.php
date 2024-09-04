<?php
// Inclua o arquivo de conexão
include 'connection.php';

// Inicializa uma variável para mensagens
$mensagem = '';

// Verifica se o formulário de comentário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentarioTexto = $_POST['comentario'];
    $idusuario = $_POST['idusuario'];

    try {
        $query = "INSERT INTO comentarios (comentario, idusuario) VALUES (:comentario, :idusuario)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':comentario' => $comentarioTexto,
            ':idusuario' => $idusuario
        ]);

        $mensagem = "Comentário inserido com sucesso!";
        // Redireciona para evitar o reenvio do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        $mensagem = "Erro ao inserir comentário: " . $e->getMessage();
    }
}

// Verifica se o formulário de resposta foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resposta'])) {
    $respostaTexto = $_POST['resposta'];
    $idcomentario = $_POST['idcomentario'];
    $idusuario = $_POST['idusuario'];

    try {
        $query = "INSERT INTO respostas (idcomentario, idusuario, resposta) VALUES (:idcomentario, :idusuario, :resposta)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':idcomentario' => $idcomentario,
            ':idusuario' => $idusuario,
            ':resposta' => $respostaTexto
        ]);

        $mensagem = "Resposta inserida com sucesso!";
        // Redireciona para evitar o reenvio do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        $mensagem = "Erro ao inserir resposta: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Perguntas</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' type='text/css' media='screen' href='perguntas.css'>
    <script src='perguntas.js' defer></script>
    <style>
        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .comentario-form, .resposta-form {
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comentario-form textarea, .resposta-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .comentario-form button, .resposta-form button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .comentario-form button:hover, .resposta-form button:hover {
            background-color: #45a049;
        }

        .comentarios {
            margin-top: 20px;
        }

        .comentario {
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comentario h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .comentario p {
            margin-bottom: 10px;
        }

        .comentario small {
            display: block;
            color: #777;
            margin-bottom: 10px;
        }

        .respostas {
            margin-top: 10px;
        }

        .resposta {
            background: #f9f9f9;
            padding: 10px;
            margin-top: 10px;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }

        .resposta h4 {
            margin-bottom: 5px;
            color: #333;
        }

        .ver-mais {
            color: #4CAF50;
            cursor: pointer;
            display: block;
            margin-top: 10px;
        }

        .ver-mais:hover {
            text-decoration: underline;
        }

        .respostas .resposta {
            display: none;
        }

        .respostas.mostrar-repostas .resposta {
            display: block;
        }
    </style>
</head>

<body>
    <div class="preloader" id="preloader">
        <div class="loader" id="loader">
            <div class="circle" id="circle"></div>
            <div class="circle" id="circle2"></div>
            <div class="circle" id="circle3"></div>
            <div class="shadow" id="shadow"></div>
            <div class="shadow" id="shadow"></div>
            <div class="shadow" id="shadow"></div>
        </div>
    </div>

    <nav class="nav-bar">
        <div class="logo">
            <a href="/site if/Index/index.html">
                <img src="/site if/Assets/logo_ifc.png" alt="Logo">
            </a>
        </div>
        <div class="nav-list">
            <ul>
                <li class="nav-item"><a href="/site if/Sigaa/sigaa.html" class="btn-txt">SIGAA</a></li>
                <li class="nav-item"><a href="/site if/Cursos/Cursos.html" class="btn-txt">AULAS</a></li>
                <li class="nav-item"><a href="/site if/Horarios/horarios.html" class="btn-txt">HORÁRIOS</a></li>
                <li class="nav-item"><a href="/site if/Eventos/eventos.html" class="btn-txt">EVENTOS</a></li>
                <li class="nav-item"><a
                        href="https://ingresso.ifc.edu.br/?_gl=1%2A1ujxkot%2A_ga%2ANTYzOTE1MTU3LjE3MTE5ODY2NzU.%2A_ga_FM2DQDNX9M%2AMTcxNDMyMzIwNy4xLjAuMTcxNDMyMzIwNy4wLjAuMA.."
                        target="_blank" class="btn-txt">QUERO ESTUDAR NO IFC</a></li>
            </ul>
        </div>

        <script src="/site if/Index/index.js"></script>
        <script src="https://kit.fontawesome.com/998c60ef77.js" crossorigin="anonymous"></script>
        <div class="pg-button">
            <button>
                <a href="/site if/Index/index.html">VOLTAR</a>
            </button>
        </div>
    </nav>
   <main>
    <h2>Deixe seu comentário</h2>
    <form class="comentario-form" method="post" action="">
        <input type="hidden" name="idusuario" value="1"> <!-- Substitua pelo ID real do usuário -->
        <textarea name="comentario" rows="4" required placeholder="Digite seu comentário..."></textarea>
        <button type="submit">Comentar</button>
    </form>

    <?php if (!empty($mensagem)): ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <div class="comentarios">
        <?php
        $stmt = $conn->query("SELECT c.*, u.nomeUs FROM comentarios c JOIN usuario u ON c.idusuario = u.idusuario ORDER BY c.data DESC");
        while ($comentario = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='comentario'>";
            echo "<h3>{$comentario['nomeUs']}</h3>";
            echo "<p>{$comentario['comentario']}</p>";
            echo "<small>Comentado em: {$comentario['data']}</small>";

            // Formulário para responder ao comentário
            echo "<form class='resposta-form' method='post' action=''>";
            echo "<input type='hidden' name='idcomentario' value='{$comentario['id']}'>";
            echo "<input type='hidden' name='idusuario' value='1'> <!-- Substitua pelo ID real do usuário -->";
            echo "<textarea name='resposta' rows='2' required placeholder='Responda ao comentário...'></textarea>";
            echo "<button type='submit'>Responder</button>";
            echo "</form>";

            // Exibe as respostas para o comentário
            $stmt2 = $conn->prepare("SELECT r.*, u.nomeUs FROM respostas r JOIN usuario u ON r.idusuario = u.idusuario WHERE r.idcomentario = :idcomentario ORDER BY r.data ASC");
            $stmt2->execute(['idcomentario' => $comentario['id']]);
            $respostas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // Adiciona o botão para mostrar/ocultar respostas
            $mostrarRespostas = count($respostas) > 3 ? 'mostrar-repostas' : '';
            echo "<a href='#' class='ver-mais' onclick='toggleRespostas(this)'>Mostrar respostas</a>";

            echo "<div class='respostas {$mostrarRespostas}'>";
            $contador = 0;
            foreach ($respostas as $resposta) {
                echo "<div class='resposta'" . ($contador >= 3 ? " style='display: none;'" : "") . ">";
                echo "<h4>{$resposta['nomeUs']} respondeu:</h4>";
                echo "<p>{$resposta['resposta']}</p>";
                echo "<small>Respondido em: {$resposta['data']}</small>";
                echo "</div>";
                $contador++;
            }

            // Adiciona o botão para mostrar mais respostas, se houver
            if (count($respostas) > 3) {
                echo "<a href='#' class='ver-mais' onclick='toggleMaisRespostas(this)'>Ver mais respostas</a>";
            }
            echo "</div>";

            echo "</div>";
        }
        ?>
    </div>
    </main> 
    <script>
        // Função para mostrar ou ocultar respostas
function toggleRespostas(link) {
    var respostasDiv = link.parentNode.querySelector('.respostas');
    if (respostasDiv.classList.contains('mostrar-repostas')) {
        respostasDiv.classList.remove('mostrar-repostas');
        link.textContent = 'Mostrar respostas';
    } else {
        respostasDiv.classList.add('mostrar-repostas');
        link.textContent = 'Ocultar respostas';
    }
}

// Função para mostrar mais ou menos respostas
function toggleMaisRespostas(link) {
    var respostasDiv = link.parentNode.querySelector('.respostas');
    var todasRespostas = respostasDiv.querySelectorAll('.resposta');
    var mostrar = link.textContent === 'Ver mais respostas';

    todasRespostas.forEach((resposta, index) => {
        if (index >= 3) {
            resposta.style.display = mostrar ? 'block' : 'none';
        }
    });

    link.textContent = mostrar ? 'Ver menos respostas' : 'Ver mais respostas';
}

    </script>

    <style>
        
        h2 {
    margin-bottom: 20px;
    color: white;
}

.comentario-form, .resposta-form {
    background: #333;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.comentario-form textarea, .resposta-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    color: white;
    background-color: #777;
}

.comentario-form button, .resposta-form button {
    background-color: #4CAF50   ;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.comentario-form button:hover, .resposta-form button:hover {
    background-color: #333;
}

.comentarios {
    margin-top: 20px;
}

.comentario {
    background: #333;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.comentario h3 {
    margin-bottom: 10px;
    color: #fff;
}

.comentario p {
    color: #fff;
    font-size: 25px;
    margin-bottom: 10px;
}

.comentario small {
    display: block;
    color: #fff;
    font-size: 25px;
    margin-bottom: 10px;
}

.respostas {
    color: #fff;
    margin-top: 10px;
}

.resposta {
    background: #333;
    padding: 10px;
    margin-top: 10px;
    border-left: 4px solid #4CAF50;
    border-radius: 4px;
}

.resposta h4 {
    margin-bottom: 5px;
    color: #fff;
}

.ver-mais {
    color: #4CAF50;
    cursor: pointer;
    display: block;
    margin-top: 10px;
}

.ver-mais:hover {
    text-decoration: underline;
}

.respostas .resposta {
    display: none;
}

.respostas.mostrar-repostas .resposta {
    display: block;
}


    </style>
    
    <footer>
        <div id="footer_começo">
            <div class="logo">
                <a href="/site if/Index/index.html">
                    <img src="/site if/Assets/logo_ifc.png" alt="Logo">
                </a>
            </div>

            <div id="footer_meio">

            </div>

            <div id="footer_final">
                <a href="#" target="_blank" class="footer-link" id="gmail">
                    <?xml version="1.0" ?><svg height="48px" viewBox="0 0 48 48" width="48px"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M45,16.2l-5,2.75l-5,4.75L35,40h7c1.657,0,3-1.343,3-3V16.2z" fill="#4caf50" />
                        <path d="M3,16.2l3.614,1.71L13,23.7V40H6c-1.657,0-3-1.343-3-3V16.2z" fill="#1e88e5" />
                        <polygon fill="#e53935"
                            points="35,11.2 24,19.45 13,11.2 12,17 13,23.7 24,31.95 35,23.7 36,17" />
                        <path
                            d="M3,12.298V16.2l10,7.5V11.2L9.876,8.859C9.132,8.301,8.228,8,7.298,8h0C4.924,8,3,9.924,3,12.298z"
                            fill="#c62828" />
                        <path
                            d="M45,12.298V16.2l-10,7.5V11.2l3.124-2.341C38.868,8.301,39.772,8,40.702,8h0 C43.076,8,45,9.924,45,12.298z"
                            fill="#fbc02d" />
                    </svg>
                </a>
                <a href="https://www.instagram.com/ifc.oficial.camboriu/" target="_blank" class="footer-link"
                    id="instagram">
                    <?xml version="1.0" ?><svg data-name="Layer 1" id="Layer_1" viewBox="0 0 512 512"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <radialGradient cx="193.09" cy="493.29" fx="159.10076116445987"
                                gradientUnits="userSpaceOnUse" id="radial-gradient" r="466">
                                <stop offset="0" stop-color="#fed77b" />
                                <stop offset="0.11" stop-color="#fed277" />
                                <stop offset="0.2" stop-color="#fdc067" />
                                <stop offset="0.36" stop-color="#f4794f" />
                                <stop offset="0.46" stop-color="#ee483f" />
                                <stop offset="0.78" stop-color="#af2c94" />
                                <stop offset="1" stop-color="#5658d1" />
                            </radialGradient>
                        </defs>
                        <title />
                        <rect fill="url(#radial-gradient)" height="412.22" rx="55.43" width="412.22" x="49.89" y="49.89" />
                        <path
                            d="M256,315.88a61.7,61.7,0,1,1,61.7-61.7A61.75,61.75,0,0,1,256,315.88Zm0-106.61a44.92,44.92,0,1,0,44.91,44.91A45,45,0,0,0,256,209.27Z"
                            fill="#fefefe" />
                        <path
                            d="M314.41,379.83H198.53a67.13,67.13,0,0,1-67.05-67.05V196.9a67.13,67.13,0,0,1,67.05-67.05H314.41a67.13,67.13,0,0,1,67,67.05V312.78A67.13,67.13,0,0,1,314.41,379.83ZM202.12,147.29a53.28,53.28,0,0,0-53.21,53.21V309.18a53.28,53.28,0,0,0,53.21,53.21H310.81A53.27,53.27,0,0,0,364,309.18V200.5a53.27,53.27,0,0,0-53.21-53.21Z"
                            fill="#fefefe" />
                        <circle cx="325.02" cy="186.34" fill="#fefefe" r="14.74" />
                    </svg>
                </a>
            </div>

        </div>
        <div id="footer_copyright">
            &#169; 2024
        </div>
    </footer>
</body>
</html>
