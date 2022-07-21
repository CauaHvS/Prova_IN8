<?php
//Configurações gerais
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "prova_in8";


//Conexão
$pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario,$senha);


function limparPost($dado){
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);
    return $dado;
}

?>