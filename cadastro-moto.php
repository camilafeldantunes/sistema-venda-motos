<?php
include_once("conexao.php");

// Buscar cores da outra tabela
$sqlCores = "SELECT codigo, nome FROM cor ORDER BY nome";
$stmtCores = $pdo->query($sqlCores);
$cores = $stmtCores->fetchAll(PDO::FETCH_ASSOC);

// Buscar modelos da outra tabela
$sqlModelos = "SELECT codigo, nome FROM modelo ORDER BY nome";
$stmtModelos = $pdo->query($sqlModelos);
$modelos = $stmtModelos->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cor_codigo'], $_POST["modelo_codigo"], $_POST["status"], $_POST["data"], $_POST["tipo"], $_POST["placa"])) {
    $cor_codigo = $_POST['cor_codigo'];
    $modelo_codigo = $_POST['modelo_codigo'];
    $placa = empty($_POST['placa']) ? NULL : $_POST['placa'];
    $status = $_POST['status'];
    $data = $_POST['data'];
    $tipo = $_POST['tipo'];

    if (isset($_POST['alterar_id']) && !empty($_POST['alterar_id'])) {
        // Atualizar modelo existente
        $id = $_POST['alterar_id'];

        $sql = "UPDATE moto SET cod_cor = :cor_codigo, cod_modelo = :modelo_codigo, status = :status, data_fabricacao = :data, tipo = :tipo, placa = :placa  WHERE codigo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cor_codigo', $cor_codigo);
        $stmt->bindParam(':modelo_codigo', $modelo_codigo);
        $stmt->bindParam(':placa', $placa);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        // Inserir novo modelo
        try {
            $sql = "INSERT INTO moto (cod_cor, cod_modelo, status, data_fabricacao, tipo, placa) VALUES (:cor_codigo, :modelo_codigo, :status, :data, :tipo, :placa)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cor_codigo', $cor_codigo);
            $stmt->bindParam(':modelo_codigo', $modelo_codigo);
            $stmt->bindParam(':placa', $placa);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':data', $data);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                echo "<script>alert('Erro: Placa já cadastrada.'); window.history.back();</script>";
                exit;
            } else {
                throw $e;
            }
        }
    }
}

$sql = "SELECT mt.codigo, md.nome AS modelo_nome, c.nome AS cor_nome, mt.status, mt.data_fabricacao, mt.tipo, mt.placa  
FROM cor c 
JOIN moto mt ON mt.cod_cor = c.codigo 
JOIN modelo md ON mt.cod_modelo = md.codigo ORDER BY md.nome, c.nome";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$motos = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_id'])) {
    $id = $_POST['deletar_id'];
    $sql = "DELETE FROM moto WHERE codigo = :id";
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

    input, button, select {
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


  <h2>Cadastro de Moto</h2>

  <form action="cadastro-moto.php" method="POST">
    <div class="select-cor">
      <select name="cor_codigo" required>
        <option value="">Selecione a cor</option>
        <?php foreach ($cores as $cor): ?>
          <option value="<?= $cor['codigo'] ?>"><?= htmlspecialchars($cor['nome']) ?></option>
        <?php endforeach; ?>
  </select>
      <button type="button" onclick="location.href='cadastro-cor.php'">+ Cor</button>
    </div>
    
    <div class="select-modelo">
    <select name="modelo_codigo" required>
        <option value="">Selecione o modelo</option>
        <?php foreach ($modelos as $modelo): ?>
          <option value="<?= $modelo['codigo'] ?>"><?= htmlspecialchars($modelo['nome']) ?></option>
        <?php endforeach; ?>
  </select>
       <button type="button" onclick="location.href='cadastro-modelo.php'">+ Modelo</button>
        </div>
    

    <input type="text" name="placa" placeholder="Placa" >

    <select name="status" required>
      <option value="">Selecione o Status</option>
      <option value="1">Disponível</option>
      <option value="2">Vendida</option>
    </select>

    <input name="data" type="date" required>

    <select name="tipo" required>
      <option name="tipo" value="">Selecione o Tipo</option>
      <option value="1">Nova</option>
      <option value="2">Usada</option>
    </select>

   <button type="submit" name="submit" id="submit">Salvar</button>
    <button type="button" onclick="alterarMoto()">Editar</button>
    <button type="button" name='deletar' id='deletar' onclick="deletarMoto()">Deletar</button>
    <button type="button" name='atualizar' id='atualizar' onclick="atualizarLista()">Atualizar Lista</button>
  </form>

 <div class="tabela-container">
    <h2>Motos Cadastradas</h2>
    <table id="tabela-motos">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th>Modelo</th>
          <th>Cor</th>
          <th>Data Fabricação</th>
          <th>Status</th>
          <th>Tipo</th>
          <th>Placa</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($motos as $moto): ?>
        <tr onclick="selecionarLinha(this)" data-id="<?= $moto['codigo'] ?>">
          <td><?= htmlspecialchars($moto['modelo_nome']) ?></td>
          <td><?= htmlspecialchars($moto['cor_nome']) ?></td>
          <td><?= htmlspecialchars($moto['data_fabricacao']) ?></td>
          <td><?= htmlspecialchars($moto['status']== 1 ? "Disponível" : "Vendida")?></td>
          <td><?= htmlspecialchars($moto['tipo'] == 1 ? "Nova" : "Usada" ) ?></td>
          <td><?= htmlspecialchars($moto['placa']) ?></td>
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

   function alterarMoto() {
  if (!linhaSelecionada) {
    alert("Selecione uma Moto para alterar.");
    return;
  }

  const idMoto = linhaSelecionada.getAttribute('data-id');
  const modeloAtual = linhaSelecionada.cells[0].innerText;
  const corAtual = linhaSelecionada.cells[1].innerText;
  const dataAtual = linhaSelecionada.cells[2].innerText;
  const statusAtual = linhaSelecionada.cells[3].innerText;
  const tipoAtual = linhaSelecionada.cells[4].innerText;
  const placaAtual = linhaSelecionada.cells[5].innerText;

  // Mapear nomes para valores
  const statusValor = statusAtual === "Disponível" ? "1" : "2";
  const tipoValor = tipoAtual === "Nova" ? "1" : "2";

  // Preenche os campos do formulário
  document.querySelector('[name="cor_codigo"]').value = getOptionValueByText('cor_codigo', corAtual);
  document.querySelector('[name="modelo_codigo"]').value = getOptionValueByText('modelo_codigo', modeloAtual);
  document.querySelector('[name="placa"]').value = placaAtual;
  document.querySelector('[name="status"]').value = statusValor;
  document.querySelector('[name="data"]').value = dataAtual;
  document.querySelector('[name="tipo"]').value = tipoValor;

  // Campo hidden para indicar edição
  let inputId = document.getElementById('alterar_id');
  if (!inputId) {
    inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'alterar_id';
    inputId.id = 'alterar_id';
    document.querySelector('form').appendChild(inputId);
  }
  inputId.value = idMoto;
}

// Função auxiliar para obter o value de uma <option> a partir do texto exibido
function getOptionValueByText(selectName, texto) {
  const select = document.querySelector(`[name="${selectName}"]`);
  for (let i = 0; i < select.options.length; i++) {
    if (select.options[i].text === texto) {
      return select.options[i].value;
    }
  }
  return ""; // fallback
}

function atualizarLista() {
  fetch('buscar_motos.php')
    .then(response => response.json())
    .then(motos => {
      const tbody = document.querySelector('#tabela-motos tbody');
      tbody.innerHTML = ''; // limpa a tabela atual

      motos.forEach(moto => {
        const tr = document.createElement('tr');
        tr.setAttribute('onclick', 'selecionarLinha(this)');
        tr.setAttribute('data-id', moto.codigo);

        tr.innerHTML = `
          <td>${moto.modelo_nome}</td>
          <td>${moto.cor_nome}</td>
          <td>${moto.data_fabricacao}</td>
          <td>${moto.status_nome}</td>
          <td>${moto.tipo_nome}</td>
          <td>${moto.placa}</td>
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

    function deletarMoto() {
  if (!linhaSelecionada) {
    alert("Selecione uma moto para deletar.");
    return;
  }

  if (confirm("Tem certeza que deseja deletar esta moto?")) {
    const idMoto = linhaSelecionada.getAttribute('data-id');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cadastro-moto.php';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'deletar_id';
    inputId.value = idMoto;
    form.appendChild(inputId);

    document.body.appendChild(form);
    form.submit();
  }
}
  </script>

</body>
</html>


