<?php
  include_once("conexao.php");
  session_start();
  $nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : '';
  $id_funcionario = isset($_SESSION['id_funcionario']) ? $_SESSION['id_funcionario'] : null;

  if ($id_funcionario === null) {
    echo "<script>alert('Erro: Sessão expirada ou usuário não logado. Faça login novamente.'); location.href='tela-de-login.html';</script>";
    exit;
}

  $sqlClientes = "SELECT codigo, nome FROM cliente ORDER BY nome";
  $stmtClientes = $pdo->query($sqlClientes);
  $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

  $sqlMotos = "SELECT mt.codigo, md.nome AS modelo_nome, c.nome AS cor_nome, mt.tipo
  FROM moto mt
  JOIN modelo md ON mt.cod_modelo = md.codigo 
  JOIN cor c ON mt.cod_cor = c.codigo
  WHERE status = 1
  ORDER BY modelo_nome, cor_nome";
  $stmtMotos = $pdo->query($sqlMotos);
  $motos = $stmtMotos->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cod_cliente'], $_POST['cod_funcionario'], $_POST['cod_moto'], $_POST['data_venda'], $_POST['forma_pagamento'])) {
    $cod_cliente = $_POST['cod_cliente'];
    $cod_funcionario = $_POST['cod_funcionario'];
    $cod_moto = $_POST['cod_moto'];
    $data_venda = $_POST['data_venda'];
    $forma_pagamento = $_POST['forma_pagamento'];

    if (isset($_POST['alterar_id']) && !empty($_POST['alterar_id'])) {
        
        $id = $_POST['alterar_id'];

        $sql = "UPDATE venda SET cod_cliente = :cod_cliente, cod_moto = :cod_moto, data_venda = :data_venda, forma_pagamento = :forma_pagamento WHERE codigo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cod_cliente', $cod_cliente, PDO::PARAM_INT);
        //$stmt->bindParam(':cod_funcionario', $cod_funcionario, PDO::PARAM_INT);
        $stmt->bindParam(':cod_moto', $cod_moto, PDO::PARAM_INT);
        $stmt->bindParam(':data_venda', $data_venda);
        $stmt->bindParam(':forma_pagamento', $forma_pagamento, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        // Inserir novo modelo
        try {
            $sql = "INSERT INTO venda (cod_cliente, cod_funcionario, cod_moto, data_venda, forma_pagamento) VALUES (:cod_cliente, :cod_funcionario, :cod_moto, :data_venda, :forma_pagamento)";
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':cod_cliente', $cod_cliente, PDO::PARAM_INT);
           $stmt->bindParam(':cod_funcionario', $cod_funcionario, PDO::PARAM_INT);
           $stmt->bindParam(':cod_moto', $cod_moto, PDO::PARAM_INT);
           $stmt->bindParam(':data_venda', $data_venda);
           $stmt->bindParam(':forma_pagamento', $forma_pagamento, PDO::PARAM_INT);
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


  $sql = "SELECT  v.codigo, v.cod_cliente, v.cod_moto, v.cod_funcionario, cl.nome AS nome_cliente, md.nome AS modelo_nome, c.nome AS cor_nome, m.tipo, f.nome AS nome_funcionario, v.data_venda AS data_venda,
    CASE v.forma_pagamento WHEN 1 THEN 'Dinheiro' WHEN 2 THEN 'Cartão' WHEN 3 THEN 'Financiamento' WHEN 4 THEN 'PIX' END AS forma_pagamento
  FROM moto m
  JOIN modelo md ON m.cod_modelo = md.codigo
  JOIN cor c ON m.cod_cor = c.codigo
  JOIN venda v ON v.cod_moto = m.codigo
  JOIN funcionario f ON v.cod_funcionario = f.codigo
  JOIN cliente cl ON v.cod_cliente = cl.codigo
  WHERE m.status = 1
  ORDER BY cl.nome";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_id'])) {
    $id = $_POST['deletar_id'];
    $sql = "DELETE FROM venda WHERE codigo = :id";
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


  <h2>Cadastro de Vendas</h2>

  <form action="cadastro-venda.php" method="POST">
    <div class="select-cliente">
      <select name="cod_cliente" required>
        <option value="">Selecione o cliente</option>
        <?php foreach ($clientes as $cliente): ?>
          <option value="<?= $cliente['codigo'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
        <?php endforeach; ?>
  </select>
    </div>
    
    <div class="select-moto">
    <select name="cod_moto" required>
        <option value="">Selecione a moto</option>
        <?php foreach ($motos as $moto): ?>
          <option value="<?= $moto['codigo'] ?>"><?= htmlspecialchars($moto['modelo_nome']. ' - ' .$moto['cor_nome']. ' - ' .($moto['tipo']== 1 ? "Nova" : "Usada")) ?></option>
        <?php endforeach; ?>
  </select>
        </div>

    <input type="hidden" name="cod_funcionario" value="<?php echo $id_funcionario; ?>">
    <input type="text" value="<?php echo htmlspecialchars($nome); ?>" readonly>

    <input name="data_venda" type="date" required>

    <select name="forma_pagamento" required>
      <option name="forma_pagamento" value="">Selecione a forma de pagamento</option>
      <option value="1">Dinheiro</option>
      <option value="2">Cartão</option>
      <option value="3">Financiamento</option>
      <option value="4">PIX</option>
    </select>

   <button type="submit" name="submit" id="submit">Salvar</button>
    <button type="button" onclick="alterarVenda()">Editar</button>
    <button type="button" name='deletar' id='deletar' onclick="deletarVenda()">Deletar</button>
    <button type="button" name='atualizar' id='atualizar' onclick="atualizarLista()">Atualizar Lista</button>
  </form>

 <div class="tabela-container">
    <h2>Vendas Cadastradas</h2>
    <div id="divBusca">
    <input type="text" id="txtBusca" placeholder="Buscar..."/>
     <button id="btnBusca">Buscar</button>
  </div>
    <table id="tabela-vendas">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th>Cliente</th>
          <th>Moto</th>
          <th>Vendedor</th>
          <th>Data da venda</th>
          <th>Pagamento</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($vendas as $venda): ?>
        <tr onclick="selecionarLinha(this)" data-id="<?= $venda['codigo'] ?>" data-funcionario-id="<?= $venda['cod_funcionario'] ?>">
        <td><?= htmlspecialchars($venda['nome_cliente']) ?></td>
        <td><?= htmlspecialchars($venda['modelo_nome'] . ' - ' . $venda['cor_nome']. ' - ' .($venda['tipo']== 1 ? "Nova" : "Usada")) ?></td>
        <td><?= htmlspecialchars($venda['nome_funcionario']) ?></td>
        <td><?= htmlspecialchars($venda['data_venda']) ?></td>
        <td><?= htmlspecialchars($venda['forma_pagamento'])?></td>
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

   function alterarVenda() {
  if (!linhaSelecionada) {
    alert("Selecione uma venda para alterar.");
    return;
  }

  const idVenda = linhaSelecionada.getAttribute('data-id');
  const clienteAtual = linhaSelecionada.cells[0].innerText;
  const modeloAtual = linhaSelecionada.cells[1].innerText;
  const funcionarioAtual = linhaSelecionada.cells[2].innerText;
  const dataAtual = linhaSelecionada.cells[3].innerText;
  const pagamentoAtual = linhaSelecionada.cells[4].innerText;

  const mapaPagamento = {
    'Dinheiro': '1',
    'Cartão': '2',
    'Financiamento': '3',
    'PIX': '4'
  };
  // Preenche os campos do formulário
  document.querySelector('[name="cod_cliente"]').value = getOptionValueByText('cod_cliente', clienteAtual);
  document.querySelector('[name="cod_moto"]').value = getOptionValueByText('cod_moto', modeloAtual);
  document.querySelector('[name="data_venda"]').value = dataAtual;
  document.querySelector('[name="forma_pagamento"]').value = mapaPagamento[pagamentoAtual] || "";

  // Campo hidden para indicar edição
  let inputId = document.getElementById('alterar_id');
  if (!inputId) {
    inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'alterar_id';
    inputId.id = 'alterar_id';
    document.querySelector('form').appendChild(inputId);
  }
  inputId.value = idVenda;
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
  fetch('buscar_vendas.php')
    .then(response => response.json())
    .then(vendas => {
      const tbody = document.querySelector('#tabela-vendas tbody');
      tbody.innerHTML = ''; // limpa a tabela atual

      vendas.forEach(venda => {
        const tr = document.createElement('tr');
        tr.setAttribute('onclick', 'selecionarLinha(this)');
        tr.setAttribute('data-id', venda.codigo);

        tr.innerHTML = `
          <td>${venda.nome_cliente}</td>
          <td>${venda.modelo_nome} - ${venda.cor_nome} - ${venda.tipo == 1 ? 'Nova' : 'Usada'}</td>
          <td>${venda.nome_funcionario}</td>
          <td>${venda.data_venda}</td>
          <td>${venda.forma_pagamento}</td>
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

    function deletarVenda() {
  if (!linhaSelecionada) {
    alert("Selecione uma venda para deletar.");
    return;
  }

  if (confirm("Tem certeza que deseja deletar esta venda?")) {
    const idVenda = linhaSelecionada.getAttribute('data-id');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cadastro-venda.php';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'deletar_id';
    inputId.value = idVenda;
    form.appendChild(inputId);

    document.body.appendChild(form);
    form.submit();
  }
}

document.getElementById('btnBusca').addEventListener('click', function () {
  const termoBusca = document.getElementById('txtBusca').value.toLowerCase();
  const linhas = document.querySelectorAll('#tabela-vendas tbody tr');

  linhas.forEach(linha => {
    const cliente = linha.cells[0].innerText.toLowerCase();
    const moto = linha.cells[1].innerText.toLowerCase();
    const vendedor = linha.cells[2].innerText.toLowerCase();
    const dataVenda = linha.cells[3].innerText.toLowerCase();
    const pagamento = linha.cells[4].innerText.toLowerCase();

    if (
      cliente.includes(termoBusca) ||
      moto.includes(termoBusca) ||
      vendedor.includes(termoBusca) ||
      dataVenda.includes(termoBusca) ||
      pagamento.includes(termoBusca)
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