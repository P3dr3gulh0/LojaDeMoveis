<?php

$conexao = mysqli_connect(
    "localhost",
    "root",
    "",
    "lojaDeMoveis",
    3306
);

if(!$conexao){
    die("Não foi possível conectar ao banco de dados.". mysqli_connect_error());
}

mysqli_set_charset($conexao,"utf8mb4");

?>