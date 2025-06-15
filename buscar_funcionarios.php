<?php
include_once('conexao.php');
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT codigo, nome, login FROM funcionario ORDER BY nome, login");
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($funcionarios);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar funcionarios: ' . $e->getMessage()]);
}
?>
