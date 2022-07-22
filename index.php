<?php
require("db/conexao.php");
//selecionar tabela
$sql = $pdo->prepare("SELECT * FROM cadastros");
$sql->execute();
$dados = $sql->fetchAll();

$erroNome = " ";
$erroEmail = " ";
$erroTelefone = " ";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Verificar se está vazio o post nome
    if (empty($_POST['nome'])) {
        $erroNome = " ";
    } else {
        //Pegar o valor vindo de post e limpar
        $nome = limpaPost($_POST['nome']);

        //Verificar se tem somente letras
        if (!preg_match("/^[a-zA-z-']*$/", $nome)) {
            $erroNome = "Apenas aceitamos letras e espaços em branco";
        }
    }

    if (empty($_POST['email'])) {
        $erroEmail = " ";
    } else {
        $email = limpaPost($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroEmail = "Email invalido";
        }
    }
    if (empty($_POST['telefone'])) {
        $erroTelefone = " ";
    } else {
        $telefone = limpaPost($_POST['telefone']);
        if (strlen($telefone) < 6) {
            $erroTelefone = "A telefone precisa ter no minimo 6 digitos";
        }
    }
}


function limpaPost($valor)
{
    $valor = trim($valor);
    $valor = stripslashes($valor);
    $valor = htmlspecialchars($valor);

    return $valor;
}



?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prova</title>
    <link rel="stylesheet" href="style.css">
    <style>

    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-nav">
                <nav>
                    <label class="logo"><img src="icon/logo-in8-dev.svg" alt="Logo IN8"></label>
                    <ul>
                        <li><a href="#">cadastro</a></li>
                        <span>&#8226;</span>
                        <li><a href="#">lista</a></li>
                        <span>&#8226;</span>
                        <li><a href="#">sobre mim</a></li>
                    </ul>
                </nav>
            </div>

            <div class="header-content">
                <h1>ESTÁGIO</h1>
                <h2>PROVA DE SELEÇÃO</h2>
            </div>
        </div>
    </header>

    <main>
        <section>
            <div class="container-cadastro">

                <div class="section-form">
                    <div>
                        <h2 id="">Cadastro</h2>
                        <form method="POST">
                            <fieldsets>
                                <div class="inputBox">
                                    <label for="nome">Nome</label>
                                    <input type="text" name="nome" id="nome" class="InputUser" required>
                                    <br><span class="erro"><?php echo $erroNome ?></span>
                                </div>
                                <div class="inputBox">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="InputUser" required>
                                    <br><span class="erro"><?php echo $erroEmail ?></span>
                                </div>
                                <div class="inputBox">
                                    <label for="nome">Nascimento</label>
                                    <input type="date" name="nascimento" id="nascimento" class="InputUser" required>
                                    <br><span class="erro"></span>
                                </div>
                                <div class="inputBox">
                                    <label for="nome">Telefone</label>
                                    <input type="tel" name="telefone" id="telefone" class="InputUser" required>
                                    <br><span class="erro"><?php echo $erroTelefone ?></span>
                                </div>
                                <div id="btn">
                                    <button name="cadastrar">CADASTRAR</button>
                                </div>
                            </fieldsets>

                            <?php
                            if (isset($_POST['cadastrar']) && isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['nascimento']) && isset($_POST['telefone'])) {
                                $nome = limparPost($_POST['nome']);
                                $email = limparPost($_POST['email']);
                                $nascimento = limparPost($_POST['nascimento']);
                                $telefone = limparPost($_POST['telefone']);

                                //Validação de campo vazio
                                if ($nome == "" || $nome == null) {
                                    echo "<p>O campo nome não pode ser vazio</p>";
                                    exit();
                                }
                                if ($email == "" || $email == null) {
                                    echo "<p>O campo nome não pode ser vazio</p>";
                                    exit();
                                }
                                if ($nascimento == "" || $nascimento == null) {
                                    echo "<p>O campo nome não pode ser vazio</p>";
                                    exit();
                                }
                                if ($telefone == "" || $telefone == null) {
                                    echo "<p>O campo nome não pode ser vazio</p>";
                                    exit();
                                }

                                //validações de dados

                                if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
                                    echo "<p>Somente permitido letras e espaços em branco no nome</p>";
                                    exit();
                                  }

                                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    echo "<p>Formato de email inválido</p>";
                                    exit();
                                  }

                                $query = sprintf("SELECT nome FROM `cadastros` WHERE nome = '$nome';");
                                $mysqli = new mysqli("$servidor", "$usuario", "", "prova_in8");
                                $testeExiste = $mysqli->query($query);
                                $count = mysqli_num_rows($testeExiste);

                                if($count == 0){
                                    $sql = $pdo->prepare("INSERT INTO cadastros VALUES (null,?,?,?,?)");
                                $sql->execute(array($nome, $email, $nascimento, $telefone));

                                echo "<p>Cadastrado com sucesso!</p>";
                                }else{
                                    echo " ";
                                }  
                            }
                            ?>
                        </form>
                    </div>
                </div>

            </div>
        </section>

        <section>
            <div class="container-list">
                <div class="section-list">
                    <h2>LISTA DE CADASTRO</h2>

                    <?php
                    if (count($dados) > 0) {
                        echo "<table id='table'>
                        <tr>
                            <th id='id'></th>
                            <th>NOME</th>
                            <th>E-MAIL</th>
                            <th>NASCIMENTO</th>
                            <th>TELEFONE</th>
                        </tr>";
                        foreach ($dados as $chave => $valor) {
                            echo "<tr>
                            <td data-label = '' class='tbId'>" . $valor['id'] . "</td>
                            <td data-label = 'NOME'>" . $valor['nome'] . "</td>
                            <td data-label = 'E-MAIL'>" . $valor['email'] . "</td>
                            <td data-label = 'NASCIMENTO'>" . $valor['nascimento'] . "</td>
                            <td data-label = 'TELEFONE'>" . $valor['telefone'] . "</td>
                        </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Nenhum usuário cadastrado</p>";
                    }
                    ?>


                </div>

            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <p>Fulano Beltrano de Oliveira Silva</p>
                <p>fulanobos@gmail.com</p>
                <p>(31)9 9666-1111</p>
                <p>Faculdade de Belo Horizonte</p>
            </div>
        </div>
    </footer>
</body>
</html>