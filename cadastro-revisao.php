<?php
  include_once("conexao.php");

  $sqlMotos = "SELECT mt.codigo, md.nome AS modelo_nome, c.nome AS cor_nome, mt.tipo
  FROM moto mt
  JOIN modelo md ON mt.cod_modelo = md.codigo 
  JOIN cor c ON mt.cod_cor = c.codigo
  WHERE status = 2 AND mt.tipo = 1
  ORDER BY modelo_nome, cor_nome";
  $stmtMotos = $pdo->query($sqlMotos);
  $motos = $stmtMotos->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['cod_moto'], $_POST['situacao'], $_POST['data_revisao'])) {
    $cod_moto = $_POST['cod_moto'];
    $situacao = $_POST['situacao'];
    $data_revisao = $_POST['data_revisao'];

    if (isset($_POST['alterar_id']) && !empty($_POST['alterar_id'])) {
        
        $id = $_POST['alterar_id'];

        $sql = "UPDATE revisao SET cod_moto = :cod_moto, situacao = :situacao, data_revisao = :data_revisao WHERE codigo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cod_moto', $cod_moto, PDO::PARAM_INT);
        $stmt->bindParam(':situacao', $situacao, PDO::PARAM_INT);
        $stmt->bindParam(':data_revisao', $data_revisao);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        // Inserir novo modelo
        try {
            $sql = "INSERT INTO revisao (cod_moto, situacao, data_revisao) VALUES (:cod_moto, :situacao, :data_revisao)";
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':cod_moto', $cod_moto, PDO::PARAM_INT);
           $stmt->bindParam(':situacao', $situacao, PDO::PARAM_INT);
           $stmt->bindParam(':data_revisao', $data_revisao);
           $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                echo "<script>alert('Erro:'); window.history.back();</script>";
                exit;
            } else {
                throw $e;
            }
        }
    }
}


  $sql = "SELECT r.codigo, r.cod_moto, md.nome AS modelo_nome, c.nome AS cor_nome, m.tipo, r.data_revisao AS data_revisao, 
    CASE r.situacao WHEN 1 THEN 'Pendente' WHEN 2 THEN 'Em andamento' WHEN 3 THEN 'Concluída' END AS situacao
  FROM moto m
  JOIN modelo md ON m.cod_modelo = md.codigo
  JOIN cor c ON m.cod_cor = c.codigo
  JOIN revisao r ON r.cod_moto = m.codigo
  ORDER BY r.data_revisao";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $revisoes = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_id'])) {
    $id = $_POST['deletar_id'];
    $sql = "DELETE FROM revisao WHERE codigo = :id";
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

  <h2>Cadastro de Revisões</h2>

  <form action="cadastro-revisao.php" method="POST">
    <div class="select-moto">
    <select name="cod_moto" required>
        <option value="">Selecione a moto</option>
        <?php foreach ($motos as $moto): ?>
          <option value="<?= $moto['codigo'] ?>"><?= htmlspecialchars($moto['modelo_nome']. ' - ' .$moto['cor_nome']. ' - ' .($moto['tipo']== 1 ? "Nova" : "Usada")) ?></option>
        <?php endforeach; ?>
  </select>
        </div>

    <select name="situacao" required>
      <option name="situacao" value="">Selecione a Situação</option>
      <option value="1">Pendente</option>
      <option value="2">Em andamento</option>
      <option value="3">Concluída</option>

      <input name="data_revisao" type="date" required>
    </select>

   <button type="submit" name="submit" id="submit">Salvar</button>
    <button type="button" onclick="alterarRevisao()">Editar</button>
    <button type="button" name='deletar' id='deletar' onclick="deletarRevisao()">Deletar</button>
    <button type="button" name='atualizar' id='atualizar' onclick="atualizarLista()">Atualizar Lista</button>
  </form>


  <div class="tabela-container">
    <h2>Revisões Cadastradas</h2>
    <div id="divBusca">
    <input type="text" id="txtBusca" placeholder="Buscar..."/>
     <button id="btnBusca">Buscar</button>
  </div>
    <table id="tabela-revisao">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th>Moto</th>
          <th>Situação</th>
          <th>Data da revisão</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($revisoes as $revisao): ?>
        <tr onclick="selecionarLinha(this)" data-id="<?= $revisao['codigo'] ?>">
        <td><?= htmlspecialchars($revisao['modelo_nome'] . ' - ' . $revisao['cor_nome']. ' - ' .($revisao['tipo']== 1 ? "Nova" : "Usada")) ?></td>
        <td><?= htmlspecialchars($revisao['situacao']) ?></td>
        <td><?= htmlspecialchars($revisao['data_revisao'])?></td>
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

   function alterarRevisao() {
  if (!linhaSelecionada) {
    alert("Selecione uma revisão para alterar.");
    return;
  }

  const idRevisao = linhaSelecionada.getAttribute('data-id');
  const motoAtual = linhaSelecionada.cells[0].innerText;
  const situacaoAtual = linhaSelecionada.cells[1].innerText;
  const dataRevisaoAtual = linhaSelecionada.cells[2].innerText;

  const mapaSituacao = {
    'Pendente': '1',
    'Em andamento': '2',
    'Concluída': '3',
  };
  // Preenche os campos do formulário
  document.querySelector('[name="cod_moto"]').value = getOptionValueByText('cod_moto', motoAtual);
  document.querySelector('[name="situacao"]').value = mapaSituacao[situacaoAtual] || "";;
  document.querySelector('[name="data_revisao"]').value = dataRevisaoAtual;

  // Campo hidden para indicar edição
  let inputId = document.getElementById('alterar_id');
  if (!inputId) {
    inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'alterar_id';
    inputId.id = 'alterar_id';
    document.querySelector('form').appendChild(inputId);
  }
  inputId.value = idRevisao;
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
  fetch('buscar_revisao.php')
    .then(response => response.json())
    .then(revisoes => {
      const tbody = document.querySelector('#tabela-revisao tbody');
      tbody.innerHTML = ''; // limpa a tabela atual

      revisoes.forEach(revisao => {
        const tr = document.createElement('tr');
        tr.setAttribute('onclick', 'selecionarLinha(this)');
        tr.setAttribute('data-id', revisao.codigo);

        tr.innerHTML = `
          <td>${revisao.modelo_nome} - ${revisao.cor_nome} - ${revisao.tipo == 1 ? 'Nova' : 'Usada'}</td>
          <td>${revisao.situacao}</td>
          <td>${revisao.data_revisao}</td>
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

    function deletarRevisao() {
  if (!linhaSelecionada) {
    alert("Selecione uma revisão para deletar.");
    return;
  }

  if (confirm("Tem certeza que deseja deletar esta revisão?")) {
    const idRevisao = linhaSelecionada.getAttribute('data-id');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cadastro-revisao.php';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'deletar_id';
    inputId.value = idRevisao;
    form.appendChild(inputId);

    document.body.appendChild(form);
    form.submit();
  }
}

document.getElementById('btnBusca').addEventListener('click', function () {
  const termoBusca = document.getElementById('txtBusca').value.toLowerCase();
  const linhas = document.querySelectorAll('#tabela-revisao tbody tr');

  linhas.forEach(linha => {
    const moto = linha.cells[0].innerText.toLowerCase();
    const situacao = linha.cells[1].innerText.toLowerCase();
    const data_revisao = linha.cells[2].innerText.toLowerCase();

    if (
      moto.includes(termoBusca) ||
      situacao.includes(termoBusca) ||
      data_revisao.includes(termoBusca) 
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
