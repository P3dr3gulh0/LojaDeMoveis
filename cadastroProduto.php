<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome =  $_POST['nome'];
    $categoria = $_POST['categoria'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    require("conexao.php");

    $inserir = "INSERT INTO produto (nome, categoria, quantidade, preco, descricao) VALUES (?,?,?,?,?)";

    $stmt = mysqli_prepare($conexao, $inserir);


    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $nome,
        $categoria,
        $quantidade,
        $preco,
        $descricao
    );


    if (mysqli_stmt_execute($stmt)) {

        echo "
        <script>

        alert('Cadastro Realizado com sucesso!');
        window.location.href = 'cadastrarProduto.php';
        
        </script>";
        exit();
    } else {
        echo "
        <script>

        alert('Erro ao cadastrar:';
        window.history.back();
        
        </script>";
        exit();
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Curso</title>
</head>

<body>
    <h2>Cadastro de Curso</h2>

    <form method="POST" action="">
        <label>Nome: </label>
        <input type="text" name="nome"> <br>

        <label>Categoria: </label>
        <input type="text" name="categoria"> <br>

        <label>Quantidade: </label>
        <input type="number" name="quantidade"> <br>

        <label>preco: </label>
        <input type="number" name="preco"> <br>

        <label>descricao: </label>
        <input type="text" name="descricao"> <br>

        <button type="reset">Limpar</button>
        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>