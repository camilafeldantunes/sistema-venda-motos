<?php
include_once('conexao.php');
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT codigo, nome, cpf, telefone FROM cliente ORDER BY nome");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clientes);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar clientes: ' . $e->getMessage()]);
}
?>
