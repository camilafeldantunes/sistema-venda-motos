# 🏍️ Sistema de Venda de Motos

## 📌 Descrição do Projeto  
Sistema completo para **cadastro, gerenciamento e venda de motos**.  
Permite controlar motos, clientes, vendas, revisões e usuários do sistema.  

🛠️ Desenvolvido com **PHP + HTML + CSS + JavaScript + PostgreSQL**

---

## 🚀 Funcionalidades

✅ Cadastro e edição de:
- 🏍️ Motos  
- 👤 Clientes  
- 🔧 Revisões  
- 💰 Vendas  

🗂️ Cada tela possui um **CRUD** completo (*Create, Read, Update, Delete*)  
🖱️ Interface interativa com **JavaScript** para seleção e atualização  
🗃️ Armazenamento dos dados com **PostgreSQL**

---

## 🧰 Tecnologias Utilizadas

- ⚙️ PHP 8.x  
- 🐘 PostgreSQL  
- 🖼️ HTML5 + 🎨 CSS3  
- ✨ JavaScript  

---

## 💻 Como Rodar o Projeto

### ✅ Pré-requisitos
- 🔹 Servidor local (XAMPP, WAMP, Laragon, etc.)  
- 🔹 PostgreSQL  
- 🔹 Git (opcional para clonar)

---

### 🧪 Passo a Passo

1. Clone o projeto:
   ```bash
   git clone https://github.com/seuusuario/sistema-venda-motos.git

2. Importe o banco de dados:

    Acesse o pgAdmin4 ou outro gerenciador e importe o arquivo banco.sql que está na pasta database

3. Configure a conexão:

    No arquivo conexao.php, edite os dados:
    ```
    $host = "localhost";
    $dbname = "seu_banco";
    $user = "seu_usuario";
    $pass = "sua_senha";
    ```
4. Execute o projeto:

    Coloque os arquivos no diretório do servidor local (ex: htdocs/ no XAMPP)

5. Acesse no navegador:
    ```
    http://localhost/sistema-venda-motos/index.php
    ``` 

## 🗃️ Estrutura do Banco de Dados

| Tabela        | Descrição              |
| ------------- |:---------------------: |
| Moto          | Registro das motos     |
| Cliente       | Registro dos clientes  |
| Venda         | Registro das vendas    |
| Cor           | Cores das motos        |
| Modelo        | Modelo das motos       |
| Revisão       | Cadastro das revisões  |
| Funcionário   | Cadastro funcionários  |


📬 Contato
Em caso de dúvidas ou sugestões, fique à vontade para entrar em contato: camifeldantunes@gmail.com 


Feito com ❤️ por Camila

