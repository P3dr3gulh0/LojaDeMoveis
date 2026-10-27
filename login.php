<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    require("conexao.php");

    $consulta = "SELECT email, senha FROM usuario WHERE email = ?";

    $stmt = mysqli_prepare($conexao, $consulta);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );

    if( mysqli_stmt_execute($stmt)) {
        
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
    <title>Login/Cadastro</title>
</head>

<body>
    <section>
        <h1>Login</h1>
        <form method="post" action="">
            <label for="">Nome</label>
            <input type="text" placeholder="Digite seu nome" name="nome">
            <label for="">Senha</label>
            <input type="password" placeholder="Digite seu nome" name="senha">
        </form>
    </section>

</body>

</html>