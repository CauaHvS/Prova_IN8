<?php
//Configurações gerais
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "prova_in8";


//Conexão
$pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario,$senha);

?>