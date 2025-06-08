<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro e Login de Funcionário</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, sans-serif;
      background-color: #E5E5E5;
      padding: 0px;
    }

    form {
      background: #fff;
      padding: 20px;
      width: 300px;
      margin: auto;
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

    button {
      background-color: #E53935;
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #C62828;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .tabela-container {
      width: 600px;
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

    .botoes-tabela {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }

    .botoes-tabela button {
      width: 48%;
    }

    .toggle-btns {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
    }

  </style>
</head>
<body>

  <img src="logo_laurindo.png" alt="Logo da empresa" width="100" height="auto">

  <h2 id="formTitle">Cadastro de Funcionário</h2>

  <!-- Botões de alternância na parte inferior do formulário -->
  <div class="toggle-btns">
    <button type="button" id="toggleCadastroBtn" onclick="toggleForm(true)">Cadastrar</button>
    <button type="button" id="toggleLoginBtn" onclick="toggleForm(false)">Login</button>
  </div>

  <form id="formCadastro" style="display: block;">
    <input type="text" placeholder="Nome" id="nomeCadastro" required>
    <input type="text" placeholder="Login" id="loginCadastro" required>
    <input type="password" placeholder="Senha" id="senhaCadastro" required>
    <button type="submit">Cadastrar</button>
    <button class="menu-button" onclick="location.href='tela-principal.html'">Cancelar</button>
  </form>

  <form id="formLogin" style="display: none;">
    <input type="text" placeholder="Login" id="loginLogin" required>
    <input type="password" placeholder="Senha" id="senhaLogin" required>

    <button type="submit">Logar</button>

    <button class="menu-button" onclick="location.href='tela-principal.html'">Cancelar</button>
  </form>

  <!-- Tabela com funcionários hipotéticos -->
  <div class="tabela-container">
    <h2>Funcionários Cadastrados</h2>
    <table id="tabela-funcionarios">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th>Código</th>
          <th>Nome</th>
          <th>Login</th>
        </tr>
      </thead>
      <tbody>
        <tr onclick="selecionarLinha(this)">
          <td>1</td>
          <td>João Martins</td>
          <td>joao.m</td>
        </tr>
        <tr onclick="selecionarLinha(this)">
          <td>2</td>
          <td>Maria Clara</td>
          <td>maria.c</td>
        </tr>
        <tr onclick="selecionarLinha(this)">
          <td>3</td>
          <td>Lucas Pereira</td>
          <td>lucas.p</td>
        </tr>
      </tbody>
    </table>

    <div class="botoes-tabela">
      <button onclick="alterarFuncionario()">Alterar</button>
      <button onclick="deletarFuncionario()">Deletar</button>
    </div>
  </div>

  <script>
    let linhaSelecionada = null;

    // Função para alternar entre Cadastro e Login
    function toggleForm(isCadastro) {
      const formCadastro = document.getElementById('formCadastro');
      const formLogin = document.getElementById('formLogin');
      const formTitle = document.getElementById('formTitle');
      
      if (isCadastro) {
        formCadastro.style.display = 'block';
        formLogin.style.display = 'none';
        formTitle.innerText = 'Cadastro de Funcionário';
      } else {
        formCadastro.style.display = 'none';
        formLogin.style.display = 'block';
        formTitle.innerText = 'Login de Funcionário';
      }
    }

    function selecionarLinha(linha) {
      if (linhaSelecionada) {
        linhaSelecionada.style.backgroundColor = '';
      }
      linhaSelecionada = linha;
      linhaSelecionada.style.backgroundColor = '#ffe0e0';
    }

    function alterarFuncionario() {
      if (!linhaSelecionada) {
        alert("Selecione um funcionário para alterar.");
        return;
      }

      const nome = prompt("Novo nome:", linhaSelecionada.cells[1].innerText);
      const login = prompt("Novo login:", linhaSelecionada.cells[2].innerText);

      if (nome && login) {
        linhaSelecionada.cells[1].innerText = nome;
        linhaSelecionada.cells[2].innerText = login;
      }
    }

    function deletarFuncionario() {
      if (!linhaSelecionada) {
        alert("Selecione um funcionário para deletar.");
        return;
      }

      if (confirm("Tem certeza que deseja deletar este funcionário?")) {
        linhaSelecionada.remove();
        linhaSelecionada = null;
      }
    }
  </script>

</body>
</html>

