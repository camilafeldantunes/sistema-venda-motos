<?php
    
    include_once('conexao.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'], $_POST['cpf'], $_POST['telefone'])) {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];

    if (isset($_POST['alterar_id']) && !empty($_POST['alterar_id'])) {
        // Atualizar cliente existente
        $id = $_POST['alterar_id'];

        $sql = "UPDATE cliente SET nome = :nome, cpf = :cpf, telefone = :telefone WHERE codigo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        // Inserir novo cliente
        try {
            $sql = "INSERT INTO cliente (nome, cpf, telefone) VALUES (:nome, :cpf, :telefone)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                echo "<script>alert('Erro: CPF já cadastrado.'); window.history.back();</script>";
                exit;
            } else {
                throw $e;
            }
        }
    }
}


 // Buscar todos os clientes cadastrados
$sql = "SELECT * FROM cliente";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_id'])) {
    $id = $_POST['deletar_id'];
    $sql = "DELETE FROM cliente WHERE codigo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Cliente</title>
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


  <h2>Cadastro de Cliente</h2>

  <form action="cadastro_cliente.php" method="POST">
    <input type="text" name="nome" id="nome" placeholder="Nome" required>
    <input type="text" name="cpf" id="cpf" placeholder="CPF" required>
    <input type="text" name="telefone" id="telefone" placeholder="Telefone" required>

    <button type="submit" name="submit" id="submit">Salvar</button>
    <button type="button" onclick="alterarCliente()">Editar</button>
    <button type="button" name='deletar' id='deletar' onclick="deletarCliente()">Deletar</button>
    <button type="button" name='atualizar' id='atualizar' onclick="atualizarLista()">Atualizar Lista</button>
    
  </form>

  <!-- Tabela com clientes-->
  <div class="tabela-container">
    <h2>Clientes Cadastrados</h2>
    <table id="tabela-clientes">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th>Nome</th>
          <th>CPF</th>
          <th>Telefone</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($clientes as $cliente): ?>
        <tr onclick="selecionarLinha(this)" data-id="<?= $cliente['codigo'] ?>">
          <td><?= htmlspecialchars($cliente['nome']) ?></td>
          <td><?= htmlspecialchars($cliente['cpf']) ?></td>
          <td><?= htmlspecialchars($cliente['telefone']) ?></td>
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

function deletarCliente() {
  if (!linhaSelecionada) {
    alert("Selecione um cliente para deletar.");
    return;
  }

  if (confirm("Tem certeza que deseja deletar este cliente?")) {
    const idCliente = linhaSelecionada.getAttribute('data-id');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cadastro_cliente.php';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'deletar_id';
    inputId.value = idCliente;
    form.appendChild(inputId);

    document.body.appendChild(form);
    form.submit();
  }
}

function alterarCliente() {
  if (!linhaSelecionada) {
    alert("Selecione um cliente para alterar.");
    return;
  }

  const idCliente = linhaSelecionada.getAttribute('data-id');
  const nomeAtual = linhaSelecionada.cells[0].innerText;
  const cpfAtual = linhaSelecionada.cells[1].innerText;
  const telefoneAtual = linhaSelecionada.cells[2].innerText;

  // Preenche os campos do formulário
  document.getElementById('nome').value = nomeAtual;
  document.getElementById('cpf').value = cpfAtual;
  document.getElementById('telefone').value = telefoneAtual;

  // Define um campo hidden para indicar que será uma alteração
  let inputId = document.getElementById('alterar_id');
  if (!inputId) {
    inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'alterar_id';
    inputId.id = 'alterar_id';
    document.querySelector('form').appendChild(inputId);
  }
  inputId.value = idCliente;
}

function atualizarLista() {
  fetch('buscar_clientes.php')
    .then(response => response.json())
    .then(clientes => {
      const tbody = document.querySelector('#tabela-clientes tbody');
      tbody.innerHTML = ''; // limpa a tabela atual

      clientes.forEach(cliente => {
        const tr = document.createElement('tr');
        tr.setAttribute('onclick', 'selecionarLinha(this)');
        tr.setAttribute('data-id', cliente.codigo);

        tr.innerHTML = `
          <td>${cliente.nome}</td>
          <td>${cliente.cpf}</td>
          <td>${cliente.telefone}</td>
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


