<?php
// Inicia ou recupera a sessão do usuário.
session_start();

// Impede que o navegador armazene a página em cache.
// Ajuda a evitar a exibição de conteúdo antigo após o logout.
header('Cache-Control: no-store, no-cache, must-revalidate');

// Verifica se o usuário está autenticado.
// A variável de sessão 'usuario_id' deve ser definida no login.
if (!isset($_SESSION['usuario_id'])) {

    // Redireciona o usuário para a página de login.
    header('Location: login.php');

    // Encerra a execução do código após o redirecionamento.
    exit;
}

// Importa o arquivo responsável pela conexão com o MySQL.
// Esse arquivo deve disponibilizar a variável $conexao.
require 'conexao.php';

// Executa uma consulta SQL no banco de dados.
$resultadoUsuarios = mysqli_query(

    // Conexão com o banco de dados MySQL.
    // Seleciona os campos desejados da tabela usuario.
    // ORDER BY organiza os registros pelo ID em ordem decrescente.
    // DESC faz com que os maiores IDs apareçam primeiro.
    $conexao,
    "SELECT idUsuario, nome, email, permissao
     FROM usuario
     ORDER BY idUsuario DESC"
);

$resultadoProdutos = mysqli_query(
    $conexao,
    "SELECT idProduto, nome, descricao, preco
     FROM produto
     ORDER BY idProduto DESC"
);

// Verifica se ocorreu algum erro na execução da consulta.
// mysqli_query retorna false quando a consulta falha.
if (!$resultadoUsuarios) {

    // Define o código HTTP 500, indicando erro interno no servidor.
    http_response_code(500);

    // Exibe uma mensagem de erro e encerra a execução.
    exit("Não foi possível carregar os usuários.");
}

// Função auxiliar para exibir informações com segurança no HTML.
// Recebe um valor e converte caracteres especiais em entidades HTML.
// Ajuda a prevenir ataques de Cross-Site Scripting (XSS)
// quando os dados são exibidos no conteúdo da página.
function h($valor)
{
    // Converte o valor recebido para texto (string).
    // ENT_QUOTES converte aspas simples e duplas.
    // ENT_SUBSTITUTE substitui sequências inválidas de caracteres.
    // UTF-8 define a codificação utilizada na conversão.
    return htmlspecialchars(
        (string)$valor,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Crud

// Controle de exibiçãode seções de administração. 
$gerenciarUsuarios = null;
$gerenciarProdutos = null;

// Usuario
if (isset($_POST['atualizar'])) {
    $idUsuario    = $_POST['idUsuario'];
    $nome           = $_POST['nome'];
    $email          = $_POST['email'];
    $permissao = $_POST['permissao'];

    $atualizar = "UPDATE usuario SET nome = '$nome', email = '$email', permissao = '$permissao' WHERE idUsuario = '$idUsuario'";

    if (mysqli_query($conexao, $atualizar)) {
        echo "
        <script>
            alert('Dados atualizado com sucesso');
            window.location='atualizarCurso.php';
        </script>";
        exit();
    } else {
        echo "Erro ai atualizar" . mysqli_error($conexao);
    }
}

$editaUsuario = null;

if (isset($_GET['editarUsuario'])) {

    $idEditar = $_GET['editarUsuario'];

    $resultado = mysqli_query(
        $conexao,
        "SELECT * FROM usuario WHERE idUsuario = $idUsuario"
    );

    $editaUsuario = mysqli_fetch_assoc($resultado);
}

$usuario = mysqli_query(
    $conexao,
    "SELECT * FROM usuario"
);


if (isset($_GET['apagar'])) {
    $idUsuario = $_GET['apagar'];

    $deletar = "DELETE FROM usuario WHERE idUsuario = $idUsuario";

    if (mysqli_query($conexao, $deletar)) {
        echo "
        <script>
            alert('Dado excluido com sucesso!');
            window.location='atualizarCurso.php';
        </script>";
        exit();
    } else {
        echo "Erro ao deletar." . mysqli_error($conexao);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
</head>

<body>

    <main>
        <section>
            <h2>Lista de Usuários</h2>
            <table class="">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Permissão</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = mysqli_fetch_assoc($usuario)): ?>
                        <tr>
                            <td><?= $usuario['idUsuario'] ?></td>
                            <td><?= $usuario['nome'] ?></td>
                            <td><?= $usuario['permissao'] ?></td>
                            <td><a href="?editar=<?= $usuario["idUsuario"] ?>">Editar</a></td>
                            <td><a href="?apagar=<?= $usuario["idUsuario"] ?>">Apagar</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php if ($editaUsuario): ?>
                <h3>Editar Usuário</h3>

                <form method="POST">
                    <input type="hidden" name="idUsuario"
                        value="<?= $editaUsuario['idUsuario'] ?>">

                    <label for="">Nome: </label>
                    <input type="text" name="nome"
                        value="<?= $editaUsuario['nome'] ?>">

                    <label for="">Permissão: </label>
                    <input type="text" name="permissao"
                        value="<?= $editaUsuario['permissao'] ?>">

                    <button type="submit" name="atualizar">Atualizar</button>
                    <a href="atualizarCurso.php">Cancelar</a>
                </form>
            <?php endif; ?>


        </section>
    </main>
</body>

</html>