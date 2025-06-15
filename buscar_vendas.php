<?php
include_once('conexao.php');
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT  v.codigo, v.cod_cliente, v.cod_moto, v.cod_funcionario, cl.nome AS nome_cliente, md.nome AS modelo_nome, c.nome AS cor_nome, m.tipo, f.nome AS nome_funcionario, v.data_venda AS data_venda, 
    CASE v.forma_pagamento WHEN 1 THEN 'Dinheiro' WHEN 2 THEN 'Cartão' WHEN 3 THEN 'Financiamento' WHEN 4 THEN 'PIX' END AS forma_pagamento
  FROM moto m
  JOIN modelo md ON m.cod_modelo = md.codigo
  JOIN cor c ON m.cod_cor = c.codigo
  JOIN venda v ON v.cod_moto = m.codigo
  JOIN funcionario f ON v.cod_funcionario = f.codigo
  JOIN cliente cl ON v.cod_cliente = cl.codigo
  WHERE m.status = 1
  ORDER BY cl.nome");
    $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($vendas);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar vendas: ' . $e->getMessage()]);
}
?>