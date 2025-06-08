<?php
include_once('conexao.php');
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT codigo, nome FROM cor ORDER BY nome");
    $cores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($cores);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar cores: ' . $e->getMessage()]);
}
?>