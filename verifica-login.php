 <?php
if (isset($_POST['submit']) && !empty($_POST['login']) && !empty($_POST['senha'])) {
    include_once('conexao.php');

    $login = $_POST['login'];
    $senhaDigitada = $_POST['senha'];

    $sql = "SELECT * FROM funcionario WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC); // apenas uma linha

    if ($funcionario && password_verify($senhaDigitada, $funcionario['senha'])) {
        session_start();
        $_SESSION['funcionario'] = $funcionario['login'];
        $_SESSION['id_funcionario'] = $funcionario['codigo'];
        $_SESSION['nome'] = $funcionario['nome'];
        header('Location: tela-principal.html');
        exit;
    } else {
        header('Location: tela-de-login.html');
        exit;
    }

    
} else {
    header('Location: tela-de-login.html');
    exit;
}
?>