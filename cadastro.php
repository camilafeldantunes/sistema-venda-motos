<?php
    include_once('conexao.php');

     if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'], $_POST['login'], $_POST['senha'])) {
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    

    if (isset($_POST['alterar_id']) && !empty($_POST['alterar_id'])) {
        
        $id = $_POST['alterar_id'];

        $sql = "UPDATE funcionario SET nome = :nome, login = :login, senha = :senha WHERE codigo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        
        try {
            $sql = "INSERT INTO funcionario (nome, login, senha) VALUES (:nome, :login, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                echo "<script>alert('Erro: Login já cadastrado.'); window.history.back();</script>";
                exit;
            } else {
                throw $e;
            }
        }
    }
}

$sql = "SELECT * FROM funcionario ORDER BY nome, login";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_id'])) {
    $id = $_POST['deletar_id'];
    $sql = "DELETE FROM funcionario WHERE codigo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Funcionários</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, sans-serif;
      background-color: #E5E5E5;
      margin: 0;
      padding: 0;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #fff;
      padding: 10px 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .logo {
      height: 60px;
      width: auto;
    }

    button {
      background-color: #E53935;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #C62828;
    }

    /* Estilo específico do botão do cabeçalho */
    .top-button {
      /* Não precisa redefinir tudo aqui se o global já cobre */
      margin: 0;
    }

    .top-button {
      background-color: #E53935;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .top-button:hover {
      background-color: #C62828;
    }

    /* Mantém o resto do estilo existente */
    form {
      background: #fff;
      padding: 20px;
      width: 300px;
      margin: 40px auto;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
    }

    input, button {
      margin: 10px 0;
      padding: 10px;
      font-size: 16px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .tabela-container {
      width: 700px;
      margin: 40px auto;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }

    tr:hover {
      background-color: #f9f9f9;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <!-- Cabeçalho com botão e imagem -->
  <header>
    <button class="top-button" onclick="location.href='tela-principal.html'">Voltar Tela Principal</button>
    <img src="logo_laurindo.png" alt="Logo da empresa" class="logo">
  </header>


  <h2>Cadastro de Funcionários</h2>

  <form action="cadastro.php" method="POST">
    <input type="text" name="nome" id="nome" placeholder="Nome" required>
    <input type="text" name="login" id="login" placeholder="Login" required>
    <input type="text" name="senha" id="senha" placeholder="Senha" required>

    <button type="submit" name="submit" id="submit">Salvar</button>
    <button type="button" onclick="alterarFuncionario()">Editar</button>
    <button type="button" name='deletar' id='deletar' onclick="deletarFuncionario()">Deletar</button>
    <button type="button" name='atualizar' id='atualizar' onclick="atualizarLista()">Atualizar Lista</button>
    
  </form>

  
  <div class="tabela-container">
    <h2>Funcionários Cadastrados</h2>
    <table id="tabela-funcionarios">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th>Nome</th>
          <th>Login</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($funcionarios as $funcionario): ?>
        <tr onclick="selecionarLinha(this)" data-id="<?= $funcionario['codigo'] ?>">
          <td><?= htmlspecialchars($funcionario['nome']) ?></td>
          <td><?= htmlspecialchars($funcionario['login']) ?></td>
      </tr>
  <?php endforeach; ?>
</tbody>

    </table>

  </div>


  <script>
let linhaSelecionada = null;

function selecionarLinha(linha) {
  if (linhaSelecionada) {
    linhaSelecionada.style.backgroundColor = '';
  }
  linhaSelecionada = linha;
  linhaSelecionada.style.backgroundColor = '#ffe0e0';
}

function deletarFuncionario() {
  if (!linhaSelecionada) {
    alert("Selecione um funcionário para deletar.");
    return;
  }

  if (confirm("Tem certeza que deseja deletar este funcionário?")) {
    const idFuncionario = linhaSelecionada.getAttribute('data-id');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cadastro.php';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'deletar_id';
    inputId.value = idFuncionario;
    form.appendChild(inputId);

    document.body.appendChild(form);
    form.submit();
  }
}

function alterarFuncionario() {
  if (!linhaSelecionada) {
    alert("Selecione um funcionário para alterar.");
    return;
  }

  const idFuncionario = linhaSelecionada.getAttribute('data-id');
  const nomeAtual = linhaSelecionada.cells[0].innerText;
  const loginAtual = linhaSelecionada.cells[1].innerText;
  

  // Preenche os campos do formulário
  document.getElementById('nome').value = nomeAtual;
  document.getElementById('login').value = loginAtual;
  

  // Define um campo hidden para indicar que será uma alteração
  let inputId = document.getElementById('alterar_id');
  if (!inputId) {
    inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'alterar_id';
    inputId.id = 'alterar_id';
    document.querySelector('form').appendChild(inputId);
  }
  inputId.value = idFuncionario;
}

function atualizarLista() {
  fetch('buscar_funcionarios.php')
    .then(response => response.json())
    .then(funcionarios => {
      const tbody = document.querySelector('#tabela-funcionarios tbody');
      tbody.innerHTML = ''; // limpa a tabela atual

      funcionarios.forEach(funcionario => {
        const tr = document.createElement('tr');
        tr.setAttribute('onclick', 'selecionarLinha(this)');
        tr.setAttribute('data-id', funcionario.codigo);

        tr.innerHTML = `
          <td>${funcionario.nome}</td>
          <td>${funcionario.login}</td>
        `;

        tbody.appendChild(tr);
      });

      linhaSelecionada = null;
    })
    .catch(erro => {
      alert("Erro ao atualizar lista.");
      console.error(erro);
    });
}
</script>


</body>
</html>
