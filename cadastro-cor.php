<?php
  include_once("conexao.php");
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    $nome = $_POST['nome'];

    if (isset($_POST['alterar_id']) && !empty($_POST['alterar_id'])) {
        // Atualizar cor existente
        $id = $_POST['alterar_id'];

        $sql = "UPDATE cor SET nome = :nome WHERE codigo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        // Inserir nova cor
        try {
            $sql = "INSERT INTO cor (nome) VALUES (:nome)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                echo "<script>alert('Erro: Cor já cadastrado.'); window.history.back();</script>";
                exit;
            } else {
                throw $e;
            }
        }
    }
}


 // Buscar todos as cores cadastradas
$sql = "SELECT * FROM cor ORDER BY nome";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$cores = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_id'])) {
    $id = $_POST['deletar_id'];
    $sql = "DELETE FROM cor WHERE codigo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}
  

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Moto</title>
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
      width: 400px;
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
    <button class="top-button" onclick="location.href='cadastro-moto.php'">Voltar</button>
    <img src="logo_laurindo.png" alt="Logo da empresa" class="logo">
  </header>


  <h2>Cadastro de Cor</h2>
  <form action="cadastro-cor.php" method="POST">
    <input type="text" name="nome" id="nome" placeholder="Nome" required>

    <button type="submit" name="submit" id="submit">Salvar</button>
    <button type="button" onclick="alterarCor()">Editar</button>
    <button type="button" name='deletar' id='deletar' onclick="deletarCor()">Deletar</button>
    <button type="button" name='atualizar' id='atualizar' onclick="atualizarLista()">Atualizar Lista</button>
    
  </form>
  <!-- Tabela com clientes-->
  <div class="tabela-container">
    <h2>Cores Cadastradas</h2>
     <div id="divBusca">
      <input type="text" id="txtBusca" placeholder="Buscar..."/>
     <button id="btnBusca">Buscar</button>
      </div>
    <table id="tabela-cores">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th>Nome</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($cores as $cor): ?>
        <tr onclick="selecionarLinha(this)" data-id="<?= $cor['codigo'] ?>">
          <td><?= htmlspecialchars($cor['nome']) ?></td>
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

function deletarCor() {
  if (!linhaSelecionada) {
    alert("Selecione uma cor para deletar.");
    return;
  }

  if (confirm("Tem certeza que deseja deletar esta cor?")) {
    const idCor = linhaSelecionada.getAttribute('data-id');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cadastro-cor.php';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'deletar_id';
    inputId.value = idCor;
    form.appendChild(inputId);

    document.body.appendChild(form);
    form.submit();
  }
}

function alterarCor() {
  if (!linhaSelecionada) {
    alert("Selecione uma Cor para alterar.");
    return;
  }

  const idCor = linhaSelecionada.getAttribute('data-id');
  const nomeAtual = linhaSelecionada.cells[0].innerText;


  // Preenche os campos do formulário
  document.getElementById('nome').value = nomeAtual;

  // Define um campo hidden para indicar que será uma alteração
  let inputId = document.getElementById('alterar_id');
  if (!inputId) {
    inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'alterar_id';
    inputId.id = 'alterar_id';
    document.querySelector('form').appendChild(inputId);
  }
  inputId.value = idCor;
}

function atualizarLista() {
  fetch('buscar_cores.php')
    .then(response => response.json())
    .then(cores => {
      const tbody = document.querySelector('#tabela-cores tbody');
      tbody.innerHTML = ''; // limpa a tabela atual

      cores.forEach(cor => {
        const tr = document.createElement('tr');
        tr.setAttribute('onclick', 'selecionarLinha(this)');
        tr.setAttribute('data-id', cor.codigo);

        tr.innerHTML = `
          <td>${cor.nome}</td>
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
document.getElementById('btnBusca').addEventListener('click', function () {
  const termoBusca = document.getElementById('txtBusca').value.toLowerCase();
  const linhas = document.querySelectorAll('#tabela-cores tbody tr');

  linhas.forEach(linha => {
    const nome = linha.cells[0].innerText.toLowerCase();
    if (
      nome.includes(termoBusca) 

    ) {
      linha.style.display = '';
    } else {
      linha.style.display = 'none';
    }
  });
});
document.getElementById('txtBusca').addEventListener('input', function () {
  document.getElementById('btnBusca').click();
});
</script>


</body>
</html>
