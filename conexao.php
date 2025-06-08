<?php

$endereco = 'localhost';
$banco = 'sistema_motos';
$usuario = 'postgres';
$senha = 'camila26';

try {
    $pdo = new PDO("pgsql:host=$endereco; port=5432;dbname=$banco", $usuario, $senha, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    //echo "Conectado no Banco de dados";
} catch (PDOException $e) {
    //echo "Falha ao conectar ao Banco de Dados <br/>";
    //die($e ->getMessage());
}

?>